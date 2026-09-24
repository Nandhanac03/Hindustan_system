<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CompanyBankAccount;
use App\Models\PartnerContribution;
use App\Models\Payee;
use App\Models\PaymentMode;
use App\Models\Project;
use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PartnerContributionController extends Controller
{
    /**
     * Display a listing of partner contributions.
     */
    public function index(Request $request): View
    {
        $systemId = auth()->user()->system_id ?? 1;

        // Ensure default partners exist if payees list is empty
        $partnerCount = Payee::where('type', 'Partner')->count();
        if ($partnerCount === 0) {
            $basheerAcc = Account::firstOrCreate(
                ['code' => 'PRT-ACC-01'],
                [
                    'system_id' => $systemId,
                    'name' => 'Basheer Capital',
                    'type' => 'liability',
                    'is_active' => true,
                ]
            );
            Payee::firstOrCreate(
                ['type' => 'Partner', 'name' => 'Basheer'],
                [
                    'system_id' => $systemId,
                    'linked_account_id' => $basheerAcc->id,
                    'is_active' => true,
                ]
            );

            $pavoorAcc = Account::firstOrCreate(
                ['code' => 'PRT-ACC-02'],
                [
                    'system_id' => $systemId,
                    'name' => 'Pavoor Capital',
                    'type' => 'liability',
                    'is_active' => true,
                ]
            );
            Payee::firstOrCreate(
                ['type' => 'Partner', 'name' => 'Pavoor'],
                [
                    'system_id' => $systemId,
                    'linked_account_id' => $pavoorAcc->id,
                    'is_active' => true,
                ]
            );
        }

        $projects = Project::orderBy('name')->get();
        $partners = Payee::where('type', 'Partner')->orderBy('name')->get();
        $companyBankAccounts = CompanyBankAccount::orderByDesc('is_default')->orderBy('bank_name')->get();
        $paymentModes = PaymentMode::where('status', 'active')->orWhereNull('status')->orderBy('id')->get();
        if ($paymentModes->isEmpty()) {
            $paymentModes = PaymentMode::all();
        }

        $allContributionsRaw = PartnerContribution::with(['partner', 'project', 'companyBankAccount', 'paymentMode'])
            ->orderByDesc('contribution_date')
            ->orderByDesc('id')
            ->get();

        $allContributions = $allContributionsRaw->map(function ($item) {
            $bankAcc = $item->companyBankAccount;
            $bankFormatted = $bankAcc
                ? ($bankAcc->bank_name . ' ' . substr((string)$bankAcc->account_number, -4) . ' (****' . substr((string)$bankAcc->account_number, -4) . ')')
                : '-';

            return [
                'id'                      => $item->id,
                'partner_id'              => $item->partner_id,
                'partner_name'            => $item->partner?->name ?? 'N/A',
                'project_id'              => $item->project_id,
                'project_name'            => $item->project?->name ?? 'N/A',
                'company_bank_account_id' => $item->company_bank_account_id,
                'bank_name_formatted'     => $bankFormatted,
                'contribution_date'       => $item->contribution_date ? $item->contribution_date->format('Y-m-d') : '',
                'formatted_date'          => $item->contribution_date ? $item->contribution_date->format('d-M-Y') : '-',
                'amount'                  => (float) $item->amount,
                'formatted_amount'        => number_format((float)$item->amount, 2),
                'payment_mode_id'         => $item->payment_mode_id,
                'payment_mode'            => $item->paymentMode?->name ?? 'Bank Transfer',
                'reference_no'            => $item->reference_no ?: '-',
                'remarks'                 => $item->remarks ?: '-',
                'status'                  => $item->status ?? 'Posted',
            ];
        });

        return view('partner-contributions.index', compact(
            'allContributions',
            'projects',
            'partners',
            'companyBankAccounts',
            'paymentModes'
        ));
    }

    /**
     * Store a newly created partner contribution.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'partner_id'              => 'required|exists:payees,id',
            'project_id'              => 'required|exists:projects,id',
            'company_bank_account_id' => 'required|exists:company_bank_accounts,id',
            'contribution_date'       => 'required|date',
            'amount'                  => 'required|numeric|min:0.01',
            'payment_mode_id'         => 'required|exists:payment_modes,id',
            'reference_no'            => 'nullable|string|max:255',
            'remarks'                 => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $validated['system_id']  = auth()->user()->system_id ?? 1;
            $validated['created_by'] = auth()->id();
            $validated['status']     = 'Posted';

            $contribution = PartnerContribution::create($validated);

            // Increment the company bank account balance with the contribution
            $bankAccount = CompanyBankAccount::lockForUpdate()->find($validated['company_bank_account_id']);
            if ($bankAccount) {
                $bankAccount->increment('current_balance', (float) $validated['amount']);
            }

            // Create corresponding system Voucher & Double Entry Accounting Postings
            try {
                $partner = Payee::find($validated['partner_id']);
                $project = Project::find($validated['project_id']);
                $pm = PaymentMode::find($validated['payment_mode_id']);
                $partnerName = $partner?->name ?? 'Partner';
                $projectName = $project?->name ?? 'Project';
                $paymentModeName = $pm?->name ?? 'Bank Transfer';

                $narrationText = 'Partner Contribution from ' . $partnerName . ' (' . $projectName . ')';
                if (!empty($validated['remarks'])) {
                    $narrationText .= ' — ' . $validated['remarks'];
                }

                $voucher = \App\Models\Voucher::create([
                    'system_id'               => $validated['system_id'],
                    'voucher_number'          => 'PRTC/' . str_pad((string)$contribution->id, 5, '0', STR_PAD_LEFT),
                    'type'                    => 'Receipt',
                    'date'                    => $validated['contribution_date'],
                    'company_bank_account_id' => $validated['company_bank_account_id'],
                    'reference_no'            => $validated['reference_no'] ?? null,
                    'narration'               => $narrationText,
                    'created_by'              => $validated['created_by'],
                    'status'                  => 'Posted',
                ]);

                // 1. Debit Bank Account (Asset increases)
                $bankAccountChart = \App\Models\Account::where('system_id', $validated['system_id'])
                    ->where('type', 'Asset')
                    ->where(function($q) use ($bankAccount) {
                        $q->where('name', 'like', '%' . ($bankAccount?->bank_name ?? 'bank') . '%')->orWhere('code', 'like', '%bank%');
                    })->first();
                $payAccountId = $bankAccountChart ? $bankAccountChart->id : 1;

                $debitLine = \App\Models\VoucherLine::create([
                    'voucher_id'     => $voucher->id,
                    'account_id'     => $payAccountId,
                    'debit'          => (float)$validated['amount'],
                    'credit'         => 0.00,
                    'line_narration' => 'Partner Capital Contribution Received in Bank Account',
                ]);

                \App\Models\LedgerEntry::create([
                    'system_id'       => $validated['system_id'],
                    'account_id'      => $payAccountId,
                    'voucher_id'      => $voucher->id,
                    'voucher_line_id' => $debitLine->id,
                    'date'            => $validated['contribution_date'],
                    'debit'           => (float)$validated['amount'],
                    'credit'          => 0.00,
                    'running_balance' => 0.00,
                ]);

                // 2. Credit Partner Capital / Equity Account
                $partnerAccountId = $partner?->linked_account_id ?? 2;
                $creditLine = \App\Models\VoucherLine::create([
                    'voucher_id'     => $voucher->id,
                    'account_id'     => $partnerAccountId,
                    'debit'          => 0.00,
                    'credit'         => (float)$validated['amount'],
                    'line_narration' => 'Credit Partner Capital Contribution (' . $partnerName . ')',
                ]);

                \App\Models\LedgerEntry::create([
                    'system_id'       => $validated['system_id'],
                    'account_id'      => $partnerAccountId,
                    'voucher_id'      => $voucher->id,
                    'voucher_line_id' => $creditLine->id,
                    'date'            => $validated['contribution_date'],
                    'debit'           => 0.00,
                    'credit'          => (float)$validated['amount'],
                    'running_balance' => 0.00,
                ]);

                // 3. Create Double Entry Accounting Postings in journal_vouchers & journal_entries
                $allPartners = Payee::where('type', 'Partner')->orderBy('id')->get();
                $partnerIndex = $allPartners->search(function ($item) use ($partner) {
                    return $item?->id == $partner?->id;
                });
                $partnerCode = (string)(3001 + ($partnerIndex !== false ? $partnerIndex : 0));

                $requiredAccounts = [
                    $partnerCode => ['name' => 'Partner ' . $partnerName . ' Capital Account', 'type' => 'EQUITY'],
                    '1001'       => ['name' => 'Bank Balances', 'type' => 'ASSET'],
                ];
                foreach ($requiredAccounts as $accCode => $accInfo) {
                    \App\Models\ChartOfAccount::firstOrCreate(
                        ['account_code' => $accCode],
                        [
                            'account_name' => $accInfo['name'],
                            'account_type' => $accInfo['type'],
                            'is_active'    => true,
                        ]
                    );
                }

                $voucherType = \App\Models\VoucherType::firstOrCreate(
                    ['code' => 'PARTNER_CONTRIBUTION'],
                    [
                        'name'        => 'Partner Capital Contribution Voucher',
                        'prefix'      => 'JV-PC',
                        'description' => 'Generated on partner capital contribution deposit',
                        'is_active'   => true,
                    ]
                );

                $jvNo = 'JV-PA-' . date('Y', strtotime($validated['contribution_date'])) . '-' . str_pad((string)$contribution->id, 4, '0', STR_PAD_LEFT);
                $journalVoucher = \App\Models\JournalVoucher::create([
                    'voucher_no'      => $jvNo,
                    'voucher_type_id' => $voucherType->id,
                    'voucher_date'    => $validated['contribution_date'],
                    'reference_id'    => $contribution->id,
                    'narration'       => 'Partner contribution from ' . strtolower($partnerName),
                    'is_active'       => true,
                ]);

                // Debit 1001 Bank Account (Asset increases)
                \App\Models\JournalEntry::create([
                    'voucher_id'     => $journalVoucher->id,
                    'account_id'     => '1001',
                    'debit_amount'   => (float)$validated['amount'],
                    'credit_amount'  => 0.00,
                    'entity_type'    => 'BANK',
                    'entity_id'      => $validated['company_bank_account_id'],
                    'line_narration' => 'Bank Account Deposit (Asset Increases)',
                ]);

                // // Credit Partner Capital Account (Equity increases)
                // \App\Models\JournalEntry::create([
                //     'voucher_id'     => $journalVoucher->id,
                //     'account_id'     => $partnerCode,
                //     'debit_amount'   => 0.00,
                //     'credit_amount'  => (float)$validated['amount'],
                //     'entity_type'    => 'PARTNER',
                //     'entity_id'      => $partner?->id,
                //     'line_narration' => 'Partner Capital Contribution Credited (' . $partnerName . ')',
                // ]);
            } catch (\Exception $e) {}

            DB::commit();

            return redirect()->route('partner-contributions.index')
                ->with('success', 'Partner contribution of ₹' . number_format((float)$validated['amount'], 2) . ' recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to record contribution: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified partner contribution.
     */
    public function destroy(PartnerContribution $contribution): RedirectResponse
    {
        DB::beginTransaction();
        try {
            // Revert bank account balance
            if ($contribution->company_bank_account_id) {
                $bankAccount = CompanyBankAccount::lockForUpdate()->find($contribution->company_bank_account_id);
                if ($bankAccount) {
                    $bankAccount->decrement('current_balance', (float) $contribution->amount);
                }
            }

            $contribution->delete();

            DB::commit();

            return redirect()->route('partner-contributions.index')
                ->with('success', 'Partner contribution record removed and bank balance adjusted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete contribution: ' . $e->getMessage());
        }
    }
}
