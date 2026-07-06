<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Payee;
use App\Models\Project;
use App\Models\PartnerShare;
use App\Models\PartnerAllocation;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function index(): View
    {
        $systemId = Auth::user()->system_id;

        // Fetch partners
        $partners = Payee::where('system_id', $systemId)
            ->where('type', 'Partner')
            ->with(['partnerShares.project'])
            ->get();

        // Calculate balances
        foreach ($partners as $partner) {
            $totalAllocated = PartnerAllocation::where('partner_id', $partner->id)->sum('allocated_amount');
            
            // Payouts can be queried from bill_payments table where payee is partner
            $totalPaid = DB::table('bill_payments')
                ->where('payee_id', $partner->id)
                ->sum('amount');

            $partner->total_allocated = (float)$totalAllocated;
            $partner->total_paid = (float)$totalPaid;
            $partner->balance = (float)$totalAllocated - (float)$totalPaid;
        }

        $projects = Project::where('is_active', true)->get();

        return view('partners.index', compact('partners', 'projects'));
    }

    public function storePartner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $systemId = Auth::user()->system_id;

        DB::transaction(function () use ($validated, $systemId) {
            // 1. Create a linked liability ledger account
            $account = Account::create([
                'system_id' => $systemId,
                'code' => 'PRT-' . strtoupper(bin2hex(random_bytes(3))),
                'name' => $validated['name'] . ' Current Account',
                'type' => 'liability',
                'is_active' => true,
            ]);

            // 2. Create payee profile
            Payee::create([
                'system_id' => $systemId,
                'type' => 'Partner',
                'name' => $validated['name'],
                'linked_account_id' => $account->id,
            ]);
        });

        return redirect()->route('partners.index')
            ->with('status', 'Partner profile registered successfully with linked ledger account.');
    }

    public function shares(Project $project): View
    {
        $systemId = Auth::user()->system_id;

        $partners = Payee::where('system_id', $systemId)
            ->where('type', 'Partner')
            ->get();

        $existingShares = PartnerShare::where('project_id', $project->id)
            ->get()
            ->keyBy('partner_id');

        return view('partners.shares', compact('project', 'partners', 'existingShares'));
    }

    public function updateShares(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'shares' => ['required', 'array'],
            'shares.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $totalShare = array_sum(array_filter($validated['shares']));

        if ($totalShare > 100.0) {
            return redirect()->back()
                ->withErrors(['shares' => 'Total share percentages across partners cannot exceed 100%. Current sum: ' . $totalShare . '%']);
        }

        $systemId = Auth::user()->system_id;

        DB::transaction(function () use ($validated, $project, $systemId) {
            foreach ($validated['shares'] as $partnerId => $sharePct) {
                if ($sharePct > 0) {
                    PartnerShare::updateOrCreate(
                        [
                            'project_id' => $project->id,
                            'partner_id' => $partnerId,
                        ],
                        [
                            'system_id' => $systemId,
                            'share_pct' => (float)$sharePct,
                        ]
                    );
                } else {
                    PartnerShare::where('project_id', $project->id)
                        ->where('partner_id', $partnerId)
                        ->delete();
                }
            }
        });

        return redirect()->route('partners.index')
            ->with('status', "Partner share percentages for Project '{$project->name}' updated successfully.");
    }

    public function statement(Request $request, Payee $partner): View
    {
        $systemId = Auth::user()->system_id;
        if ($partner->system_id !== $systemId || $partner->type !== 'Partner') {
            abort(403, 'Unauthorized.');
        }

        $projectId = $request->input('project_id');

        // Credits: allocations
        $allocationsQuery = PartnerAllocation::where('partner_id', $partner->id)->with(['payment.booking']);
        if ($projectId) {
            $allocationsQuery->where('project_id', $projectId);
        }
        $allocations = $allocationsQuery->get()->map(function ($alloc) {
            return [
                'date' => $alloc->date,
                'type' => 'Collection Share',
                'description' => "Collection share from Booking " . ($alloc->payment->booking->booking_number ?? 'N/A') . " (Receipt: " . $alloc->payment->receipt_number . ")",
                'credit' => (float)$alloc->allocated_amount,
                'debit' => 0.0,
            ];
        });

        // Debits: payouts (bill_payments where payee is partner)
        $paymentsQuery = DB::table('bill_payments')
            ->where('payee_id', $partner->id);
        if ($projectId) {
            $paymentsQuery->where('project_id', $projectId);
        }
        $payouts = $paymentsQuery->get()->map(function ($pay) {
            return [
                'date' => \Carbon\Carbon::parse($pay->payment_date),
                'type' => 'Payout',
                'description' => "Payout processed (" . ($pay->payment_mode ?? 'Direct Bank') . " Ref: " . ($pay->reference_number ?? 'N/A') . ")",
                'credit' => 0.0,
                'debit' => (float)$pay->amount,
            ];
        });

        // Combine and sort by date
        $ledger = $allocations->concat($payouts)->sortBy('date')->values();

        // Calculate running balances
        $runningBalance = 0.0;
        foreach ($ledger as &$entry) {
            $runningBalance += ($entry['credit'] - $entry['debit']);
            $entry['balance'] = $runningBalance;
        }

        $projects = Project::where('is_active', true)->get();

        return view('partners.statement', compact('partner', 'ledger', 'projects', 'projectId'));
    }
}
