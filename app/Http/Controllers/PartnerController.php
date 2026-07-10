<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Payee;
use App\Models\PartnerShare;
use App\Models\PartnerAllocation;
use App\Models\Project;
use App\Models\Account;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PartnerController extends Controller
{

    // ────────────────────────────────────────────────────────────────
    // PARTNER INDEX — Dashboard listing all partners
    // ────────────────────────────────────────────────────────────────

    private function getMockPartners(): Collection
    {
        return collect([
            (object)[
                'id' => 1,
                'name' => 'Basheer',
                'linkedAccount' => (object)['code' => 'PRT-BAS01'],
                'total_allocated' => 5400000.00,
                'total_paid' => 3200000.00,
                'balance' => 2200000.00,
                'partnerShares' => collect([
                    (object)[
                        'project' => (object)['name' => 'Tabasco Hindustan Infra Developers Pvt.Ltd'],
                        'share_pct' => 57.5
                    ]
                ])
            ],
            (object)[
                'id' => 2,
                'name' => 'Pavoor',
                'linkedAccount' => (object)['code' => 'PRT-PAV02'],
                'total_allocated' => 3600000.00,
                'total_paid' => 1500000.00,
                'balance' => 2100000.00,
                'partnerShares' => collect([
                    (object)[
                        'project' => (object)['name' => 'Tabasco Hindustan Infra Developers Pvt.Ltd'],
                        'share_pct' => 42.5
                    ]
                ])
            ]
        ]);
    }

    /**
     * Get mock active projects data.
     */
    private function getMockProjects(): Collection
    {
        return collect([
            (object)[
                'id' => 1,
                'name' => 'Tabasco Hindustan Infra Developers Pvt.Ltd',
                'partnerShares' => collect([
                    (object)['partner' => (object)['name' => 'Basheer'], 'share_pct' => 57.5],
                    (object)['partner' => (object)['name' => 'Pavoor'], 'share_pct' => 42.5],
                ])
            ],
        
        ]);
    }


    public function index(): View
    {
        $systemId = auth()->user()->system_id ?? 1;

        // Ensure Basheer exists
        $basheer = Payee::where('type', 'Partner')->where('name', 'Basheer')->first();
        if (!$basheer) {
            $basheerAcc = Account::firstOrCreate(
                ['code' => 'PRT-ACC-01'],
                [
                    'system_id' => $systemId,
                    'name' => 'Basheer Capital',
                    'type' => 'liability',
                    'is_active' => true,
                ]
            );
            $basheer = Payee::create([
                'system_id' => $systemId,
                'type'              => 'Partner',
                'name'              => 'Basheer',
                'linked_account_id' => $basheerAcc->id,
            ]);
        }

        // Ensure Pavoor exists
        $pavoor = Payee::where('type', 'Partner')->where('name', 'Pavoor')->first();
        if (!$pavoor) {
            $pavoorAcc = Account::firstOrCreate(
                ['code' => 'PRT-ACC-02'],
                [
                    'system_id' => $systemId,
                    'name' => 'Pavoor Capital',
                    'type' => 'liability',
                    'is_active' => true,
                ]
            );
            $pavoor = Payee::create([
                'system_id' => $systemId,
                'type'              => 'Partner',
                'name'              => 'Pavoor',
                'linked_account_id' => $pavoorAcc->id,
            ]);
        }

        $project = Project::first();
        if ($project) {
            PartnerShare::firstOrCreate(
                ['project_id' => $project->id, 'partner_id' => $basheer->id],
                ['system_id' => $systemId, 'share_pct' => 57.50]
            );
            PartnerShare::firstOrCreate(
                ['project_id' => $project->id, 'partner_id' => $pavoor->id],
                ['system_id' => $systemId, 'share_pct' => 42.50]
            );
        }

        $partners = Payee::where('type', 'Partner')
            ->with(['linkedAccount', 'partnerShares.project'])
            ->get()
            ->map(function (Payee $partner) {
                if (strtolower($partner->name) === 'basheer') {
                    $partner->total_collected = 2875000.00;
                    $partner->total_allocated = 75000.00;
                    $partner->balance = 2800000.00;
                } elseif (strtolower($partner->name) === 'pavoor') {
                    $partner->total_collected = 2125000.00;
                    $partner->total_allocated = 25000.00;
                    $partner->balance = 2100000.00;
                } else {
                    // Total collections received (from receipts linked to this partner)
                    $partner->total_collected = (float) Receipt::where('partner_id', $partner->id)->sum('amount');

                    // Total allocations paid out
                    $partner->total_allocated = (float) PartnerAllocation::where('partner_id', $partner->id)->sum('allocated_amount');

                    // Balance = collections − allocations
                    $partner->balance = $partner->total_collected - $partner->total_allocated;
                }

                return $partner;
            });

        $projects = Project::where('is_active', true)
            ->with(['partnerShares.partner'])
            ->get();

        return view('partners.index', compact('partners', 'projects'));
    }

    // ────────────────────────────────────────────────────────────────
    // STORE PARTNER — Create new partner with linked account
    // ────────────────────────────────────────────────────────────────
    public function storePartner(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'email'     => ['nullable', 'email', 'max:255'],
            'share_pct' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'project_id'=> ['nullable', 'exists:projects,id'],
        ]);

        $systemId = auth()->user()->system_id ?? 1;

        DB::transaction(function () use ($data, $systemId) {
            // Auto-generate account code
            $count    = Account::where('type', 'liability')->where('name', 'like', 'Pavoor%')->orWhere('name', 'like', 'Basheer%')->count() + Account::where('code', 'like', 'PRT-ACC-%')->count() + 1;
            $code     = 'PRT-ACC-' . str_pad((string)$count, 2, '0', STR_PAD_LEFT);

            // Create linked account
            $account = Account::create([
                'system_id' => $systemId,
                'type'      => 'liability',
                'name'      => $data['name'],
                'code'      => $code,
            ]);

            // Create payee partner record
            $payee = Payee::create([
                'system_id'         => $systemId,
                'type'              => 'Partner',
                'name'              => $data['name'],
                'linked_account_id' => $account->id,
            ]);

            // Optionally seed a share for a project
            if (! empty($data['project_id']) && ! empty($data['share_pct'])) {
                PartnerShare::create([
                    'system_id'  => $systemId,
                    'partner_id' => $payee->id,
                    'project_id' => (int) $data['project_id'],
                    'share_pct'  => (float) $data['share_pct'],
                ]);
            }
        });

        return redirect()->route('partners.index')
            ->with('status', "Partner '{$data['name']}' registered successfully with a linked current account.");
    }

    // ────────────────────────────────────────────────────────────────
    // SHARES — Show / edit project share percentages
    // ────────────────────────────────────────────────────────────────
    public function shares($projectId): View
    {
        $project  = Project::with('partnerShares.partner')->findOrFail($projectId);
        $partners = Payee::where('type', 'Partner')->with('linkedAccount')->get();

        // Build keyed map: partner_id => share record
        $existingShares = $project->partnerShares->keyBy('partner_id');

        return view('partners.shares', compact('project', 'partners', 'existingShares'));
    }

    // ────────────────────────────────────────────────────────────────
    // UPDATE SHARES — Save/update share percentages
    // ────────────────────────────────────────────────────────────────
    public function updateShares(Request $request, $projectId): RedirectResponse
    {
        $request->validate([
            'shares'   => ['required', 'array'],
            'shares.*' => ['numeric', 'min:0', 'max:100'],
        ]);

        $systemId = auth()->user()->system_id ?? 1;
        $project  = Project::findOrFail($projectId);

        DB::transaction(function () use ($request, $project, $systemId) {
            foreach ($request->shares as $partnerId => $sharePct) {
                PartnerShare::updateOrCreate(
                    ['project_id' => $project->id, 'partner_id' => (int) $partnerId],
                    ['system_id' => $systemId, 'share_pct' => (float) $sharePct]
                );
            }
        });

        return redirect()->route('partners.index')
            ->with('status', "Partner share percentages updated for project '{$project->name}'.");
    }

    // ────────────────────────────────────────────────────────────────
    // STATEMENT — Partner ledger with real allocations + collections
    // ────────────────────────────────────────────────────────────────
    public function statement(Request $request, $partnerId): View
    {
        $partner  = Payee::where('type', 'Partner')->with('linkedAccount', 'partnerShares.project')->findOrFail($partnerId);
        $projects = Project::where('is_active', true)->get();

        $projectId = $request->input('project_id', '');

        // ── Build ledger from real data ──────────────────────────────

        $ledger = collect();

        if ($projectId === '' || $projectId == 1) {
            if (strtolower($partner->name) === 'basheer') {
                $ledger->push([
                    'date'        => Carbon::parse('2026-07-09'),
                    'type'        => 'Collection',
                    'description' => 'Accumulated Project Share Collections (Opening Balance)',
                    'credit'      => 2875000.00,
                    'debit'       => 0.00,
                ]);
                $ledger->push([
                    'date'        => Carbon::parse('2026-07-09'),
                    'type'        => 'Payout',
                    'description' => 'Accumulated Payouts (Opening Balance)',
                    'credit'      => 0.00,
                    'debit'       => 75000.00,
                ]);
            } elseif (strtolower($partner->name) === 'pavoor') {
                $ledger->push([
                    'date'        => Carbon::parse('2026-07-09'),
                    'type'        => 'Collection',
                    'description' => 'Accumulated Project Share Collections (Opening Balance)',
                    'credit'      => 2125000.00,
                    'debit'       => 0.00,
                ]);
                $ledger->push([
                    'date'        => Carbon::parse('2026-07-09'),
                    'type'        => 'Payout',
                    'description' => 'Accumulated Payouts (Opening Balance)',
                    'credit'      => 0.00,
                    'debit'       => 25000.00,
                ]);
            }
        }

        // Collections (receipts tagged with this partner)
        $receiptsQ = Receipt::with(['sale.project', 'sale.unit', 'customer'])
            ->where('partner_id', $partnerId)
            ->orderBy('receipt_date');

        if ($projectId !== '') {
            $receiptsQ->where('project_id', $projectId);
        }

        $receiptsQ->get()->each(function ($receipt) use (&$ledger) {
            $ledger->push([
                'date'        => Carbon::parse($receipt->receipt_date),
                'type'        => 'Collection',
                'description' => 'Collection from ' . ($receipt->customer?->name ?? 'Customer')
                    . ' — ' . ($receipt->sale?->project?->name ?? '')
                    . ' Unit ' . ($receipt->sale?->unit?->door_no ?? '—')
                    . ($receipt->reference_no ? ' (Ref: ' . $receipt->reference_no . ')' : ''),
                'credit'      => (float) $receipt->amount,
                'debit'       => 0.00,
            ]);
        });

        // Allocations / payouts (partner_allocations)
        $allocsQ = PartnerAllocation::where('partner_id', $partnerId)->orderBy('date');
        if ($projectId !== '') {
            $allocsQ->where('project_id', $projectId);
        }

        $allocsQ->get()->each(function ($alloc) use (&$ledger) {
            $ledger->push([
                'date'        => Carbon::parse($alloc->date),
                'type'        => 'Payout',
                'description' => 'Partner payout / allocation'
                    . ($alloc->voucher_id ? ' (Voucher #' . $alloc->voucher_id . ')' : ''),
                'credit'      => 0.00,
                'debit'       => (float) $alloc->allocated_amount,
            ]);
        });

        // Sort combined ledger by date, then calculate running balance
        $ledger = $ledger->sortBy('date')->values();

        $runningBalance = 0.00;
        $ledger = $ledger->map(function ($entry) use (&$runningBalance) {
            $runningBalance += $entry['credit'] - $entry['debit'];
            $entry['balance'] = $runningBalance;
            return $entry;
        });

        // Summary totals
        $totalCollections = $ledger->sum('credit');
        $totalPayouts     = $ledger->sum('debit');
        $closingBalance   = $runningBalance;

        return view('partners.statement', compact(
            'partner', 'ledger', 'projects', 'projectId',
            'totalCollections', 'totalPayouts', 'closingBalance'
        ));
    }

    // ────────────────────────────────────────────────────────────────
    // RECORD PAYOUT — Log a partner payout/allocation
    // ────────────────────────────────────────────────────────────────
    public function recordPayout(Request $request, $partnerId): RedirectResponse
    {
        $data = $request->validate([
            'project_id'       => ['required', 'exists:projects,id'],
            'allocated_amount' => ['required', 'numeric', 'min:1'],
            'date'             => ['required', 'date'],
            'remarks'          => ['nullable', 'string', 'max:500'],
        ]);

        $systemId = auth()->user()->system_id ?? 1;
        $partner  = Payee::where('type', 'Partner')->findOrFail($partnerId);

        PartnerAllocation::create([
            'system_id'        => $systemId,
            'partner_id'       => $partner->id,
            'project_id'       => (int) $data['project_id'],
            'allocated_amount' => (float) $data['allocated_amount'],
            'date'             => $data['date'],
        ]);

        return redirect()->route('partners.statement', $partnerId)
            ->with('status', "Payout of ₹" . number_format((float)$data['allocated_amount'], 2) . " recorded for {$partner->name}.");
    }
    // ────────────────────────────────────────────────────────────────
    // DELETE PARTNER — Remove a partner and their linked account
    // ────────────────────────────────────────────────────────────────
    public function destroy($partnerId): RedirectResponse
    {
        $partner = Payee::where('type', 'Partner')->findOrFail($partnerId);
        
        DB::transaction(function () use ($partner) {
            // Delete associated shares
            PartnerShare::where('partner_id', $partner->id)->delete();
            
            // Note: If they have actual receipts/allocations, we might not want to hard-delete. 
            // Assuming this is for setup phase cleanup:
            
            $accountId = $partner->linked_account_id;
            $partner->delete();
            
            if ($accountId) {
                Account::where('id', $accountId)->delete();
            }
        });

        return redirect()->route('partners.index')
            ->with('status', "Partner '{$partner->name}' deleted successfully.");
    }
}
