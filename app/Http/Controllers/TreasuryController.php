<?php

namespace App\Http\Controllers;

use App\Models\CompanyBankAccount;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class TreasuryController extends Controller
{
    /**
     * Show the treasury dashboard.
     */
 public function dashboard(Request $request): View
    {
        $bankAccounts = CompanyBankAccount::orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $totalBalance = (float) $bankAccounts->sum('current_balance');
        $availableBalance = (float) $bankAccounts->where('status', '!=', 'inactive')->sum('current_balance');
        if ($availableBalance <= 0) {
            $availableBalance = $totalBalance;
        }

        // Pending Instruments Pipeline (Waiting for Realization)
        $pendingStatuses = ['pending', 'cheque_in_hand', 'deposited', 'in_clearing'];
        $totalPendingCount = Receipt::whereIn('realization_status', $pendingStatuses)->count();
        $totalPendingAmount = (float) Receipt::whereIn('realization_status', $pendingStatuses)->sum('amount');

        // Realized Metrics
        $totalRealizedCount = Receipt::where('realization_status', 'realized')->count();
        $totalRealizedAmount = (float) Receipt::where('realization_status', 'realized')->sum('amount');

        // Today's Realized
        $todayRealizedCount = Receipt::where('realization_status', 'realized')
            ->whereDate('realized_at', Carbon::today())
            ->count();
        $todayRealizedAmount = (float) Receipt::where('realization_status', 'realized')
            ->whereDate('realized_at', Carbon::today())
            ->sum('amount');

        // Bounced / Attention Required
        $bouncedCount = Receipt::where('realization_status', 'bounced')->count();
        $bouncedAmount = (float) Receipt::where('realization_status', 'bounced')->sum('amount');

        // Populate per-bank metrics (Receipts + Partner Contributions)
        foreach ($bankAccounts as $account) {
            $rcptCount = Receipt::where('company_bank_account_id', $account->id)
                ->where('realization_status', 'realized')
                ->count();
            $rcptSum = (float) Receipt::where('company_bank_account_id', $account->id)
                ->where('realization_status', 'realized')
                ->sum('amount');

            $pcBase = \App\Models\PartnerContribution::where('company_bank_account_id', $account->id)
                ->where(function ($q) {
                    $q->where('is_historical', false)->orWhereNull('is_historical');
                });
            $pcCount = (clone $pcBase)->count();
            $pcSum = (float) (clone $pcBase)->sum('amount');

            $account->realized_count = $rcptCount + $pcCount;
            $account->realized_sum = $rcptSum + $pcSum;

            $account->pending_count = Receipt::where('company_bank_account_id', $account->id)
                ->whereIn('realization_status', $pendingStatuses)
                ->count();
            $account->pending_sum = (float) Receipt::where('company_bank_account_id', $account->id)
                ->whereIn('realization_status', $pendingStatuses)
                ->sum('amount');
        }

        // Fetch recent transactions (Credits: Realized Receipts & Partner Contributions, Debits: RA Bill & Site Expense Payments)
        $recentTransactions = [];
        $allRecentTxns = [];

        $allReceipts = Receipt::with(['customer', 'companyBankAccount', 'realizationLogs'])
            ->where('realization_status', 'realized')
            ->orderByDesc('realized_at')
            ->orderByDesc('id')
            ->get();

        foreach ($allReceipts as $receipt) {
            $acc = $receipt->companyBankAccount;
            $realizedAt = $receipt->realized_at ? Carbon::parse($receipt->realized_at) : ($receipt->receipt_date ? Carbon::parse($receipt->receipt_date) : null);
            
            $log = $receipt->realizationLogs->first();
            $bankRef = $log?->bank_reference_no ?? $receipt->reference_no;

            $txn = [
                'id' => 'rcpt_' . $receipt->id,
                'date' => $realizedAt ? $realizedAt->format('d/m/Y') : '—',
                'raw_date' => $realizedAt ? $realizedAt->timestamp : 0,
                'datetime_formatted' => $realizedAt ? $realizedAt->format('d M Y, h:i A') : '—',
                'voucher_no' => $receipt->receipt_no ?? ('RV/2025-26/' . str_pad((string)$receipt->id, 6, '0', STR_PAD_LEFT)),
                'customer_name' => $receipt->customer?->name ?? 'Direct Customer',
                'customer_phone' => $receipt->customer?->phone ?? '',
                'narration' => 'Realized from ' . ($receipt->customer?->name ?? 'Customer') . ' (' . ($receipt->payment_mode ?? 'Instrument') . ' ' . ($receipt->reference_no ?? '') . ')',
                'payment_mode' => $receipt->payment_mode ?: 'Cheque',
                'cheque_no' => $receipt->reference_no ?: '—',
                'drawee_bank' => $receipt->drawee_bank ?: '—',
                'bank_ref_no' => $bankRef ?: '—',
                'bank_name' => $acc?->bank_name ?? 'Treasury',
                'bank_account_id' => $receipt->company_bank_account_id,
                'type' => 'Credit',
                'amount' => (float)$receipt->amount,
                'balance' => (float)($acc?->current_balance ?? 0),
                'remarks' => $receipt->remarks ?: ($log?->remarks ?? '')
            ];

            $allRecentTxns[] = $txn;
            if ($receipt->company_bank_account_id) {
                $recentTransactions[$receipt->company_bank_account_id][] = $txn;
            }
        }

        // Inward Credit: Partner Contributions
        $allPartnerContributions = \App\Models\PartnerContribution::with(['partner', 'project', 'companyBankAccount', 'paymentMode'])
            ->whereNotNull('company_bank_account_id')
            ->where(function ($q) {
                $q->where('is_historical', false)->orWhereNull('is_historical');
            })
            ->orderByDesc('contribution_date')
            ->orderByDesc('id')
            ->get();

        foreach ($allPartnerContributions as $pc) {
            $acc = $pc->companyBankAccount;
            $cDate = $pc->contribution_date ? Carbon::parse($pc->contribution_date) : Carbon::parse($pc->created_at);
            $partnerName = $pc->partner?->name ?? 'Partner';
            $projectName = $pc->project?->name ? (' - ' . $pc->project->name) : '';

            $pcTxn = [
                'id' => 'pc_' . $pc->id,
                'date' => $cDate ? $cDate->format('d/m/Y') : '—',
                'raw_date' => $cDate ? $cDate->timestamp : 0,
                'datetime_formatted' => $cDate ? $cDate->format('d M Y') : '—',
                'voucher_no' => 'PRTC/' . str_pad((string)$pc->id, 5, '0', STR_PAD_LEFT),
                'customer_name' => $partnerName . ' (Partner Contribution' . $projectName . ')',
                'customer_phone' => '',
                'narration' => 'Partner Contribution from ' . $partnerName . ($pc->project?->name ? (' (' . $pc->project->name . ')') : ''),
                'payment_mode' => $pc->paymentMode?->name ?? 'Bank Transfer',
                'cheque_no' => $pc->reference_no ?: '—',
                'drawee_bank' => '—',
                'bank_ref_no' => $pc->reference_no ?: '—',
                'bank_name' => $acc?->bank_name ?? 'Treasury',
                'bank_account_id' => $pc->company_bank_account_id,
                'type' => 'Credit',
                'amount' => (float)$pc->amount,
                'balance' => (float)($acc?->current_balance ?? 0),
                'remarks' => $pc->remarks ?: ('Partner Contribution from ' . $partnerName)
            ];

            $allRecentTxns[] = $pcTxn;
            if ($pc->company_bank_account_id) {
                $recentTransactions[$pc->company_bank_account_id][] = $pcTxn;
            }
        }

        // Outward Debits: RA Bill Payments
        $allRaPayments = \App\Models\RaBillPayment::with(['raBill.contractor', 'companyBankAccount'])
            ->where(function ($q) {
                $q->where('is_historical', false)->orWhereNull('is_historical');
            })
            ->whereNotNull('company_bank_account_id')
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get();

        foreach ($allRaPayments as $raPay) {
            $acc = $raPay->companyBankAccount;
            $payDate = $raPay->payment_date ? Carbon::parse($raPay->payment_date) : null;
            $contractorName = $raPay->raBill?->contractor?->name ?? 'Contractor';

            $debitTxn = [
                'id' => 'ra_' . $raPay->id,
                'date' => $payDate ? $payDate->format('d/m/Y') : '—',
                'raw_date' => $payDate ? $payDate->timestamp : 0,
                'datetime_formatted' => $payDate ? $payDate->format('d M Y') : '—',
                'voucher_no' => 'PAY/' . str_pad((string)$raPay->id, 5, '0', STR_PAD_LEFT),
                'customer_name' => $contractorName . ' (RA Bill #' . ($raPay->ra_bill_id ?? '') . ')',
                'customer_phone' => '',
                'narration' => 'Payment to ' . $contractorName . ' (RA Bill)',
                'payment_mode' => str_replace('_', ' ', $raPay->payment_mode ?: 'Bank Transfer'),
                'cheque_no' => $raPay->reference_no ?: '—',
                'drawee_bank' => '—',
                'bank_ref_no' => $raPay->reference_no ?: '—',
                'bank_name' => $acc?->bank_name ?? 'Treasury',
                'bank_account_id' => $raPay->company_bank_account_id,
                'type' => 'Debit',
                'amount' => (float)$raPay->paid_amount,
                'balance' => (float)($acc?->current_balance ?? 0),
                'remarks' => $raPay->remarks ?: 'RA Bill Payment'
            ];

            $allRecentTxns[] = $debitTxn;
            if ($raPay->company_bank_account_id) {
                $recentTransactions[$raPay->company_bank_account_id][] = $debitTxn;
            }
        }

        // Outward Debits: Site Expense Payments
        $allSiteExpenses = \App\Models\SiteExpensePayment::with(['companyBankAccount'])
            ->whereNotNull('company_bank_account_id')
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get();

        foreach ($allSiteExpenses as $sep) {
            $acc = $sep->companyBankAccount;
            $payDate = $sep->payment_date ? Carbon::parse($sep->payment_date) : null;

            $sepTxn = [
                'id' => 'sep_' . $sep->id,
                'date' => $payDate ? $payDate->format('d/m/Y') : '—',
                'raw_date' => $payDate ? $payDate->timestamp : 0,
                'datetime_formatted' => $payDate ? $payDate->format('d M Y') : '—',
                'voucher_no' => 'EXP/' . str_pad((string)$sep->id, 5, '0', STR_PAD_LEFT),
                'customer_name' => 'Site Expense (' . ($sep->payment_mode ?? 'Bank') . ')',
                'customer_phone' => '',
                'narration' => 'Site Expense Payment',
                'payment_mode' => str_replace('_', ' ', $sep->payment_mode ?: 'Bank Transfer'),
                'cheque_no' => $sep->reference_no ?: '—',
                'drawee_bank' => '—',
                'bank_ref_no' => $sep->reference_no ?: '—',
                'bank_name' => $acc?->bank_name ?? 'Treasury',
                'bank_account_id' => $sep->company_bank_account_id,
                'type' => 'Debit',
                'amount' => (float)$sep->paid_amount,
                'balance' => (float)($acc?->current_balance ?? 0),
                'remarks' => $sep->remarks ?: 'Site Expense Payment'
            ];

            $allRecentTxns[] = $sepTxn;
            if ($sep->company_bank_account_id) {
                $recentTransactions[$sep->company_bank_account_id][] = $sepTxn;
            }
        }

        // Contra Vouchers: Bank Cash Withdrawals & Bank-to-Bank Transfers
        $allContraVouchers = \App\Models\Voucher::where('type', 'Contra')
            ->with(['lines.account'])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        // Helper to match a chart of account or bank id to a CompanyBankAccount
        $resolveBank = function ($bankId, $chartAcc) use ($bankAccounts) {
            if ($bankId) {
                $found = $bankAccounts->firstWhere('id', $bankId);
                if ($found) return $found;
            }
            if ($chartAcc) {
                $accName = strtolower($chartAcc->name ?? '');
                foreach ($bankAccounts as $ba) {
                    $bName = strtolower($ba->bank_name ?? '');
                    if ($bName && (str_contains($accName, $bName) || str_contains($bName, $accName))) {
                        return $ba;
                    }
                    if ($ba->account_number && str_contains($accName, (string)$ba->account_number)) {
                        return $ba;
                    }
                }
            }
            return null;
        };

        foreach ($allContraVouchers as $cv) {
            $cvDate = $cv->date ? Carbon::parse($cv->date) : Carbon::parse($cv->created_at);
            $creditLine = $cv->lines->firstWhere('credit', '>', 0);
            $debitLine = $cv->lines->firstWhere('debit', '>', 0);

            $amount = (float)($creditLine?->credit ?? $debitLine?->debit ?? 0);
            if ($amount <= 0) continue;

            $sourceBank = $resolveBank($cv->company_bank_account_id, $creditLine?->account);
            $destBank = $resolveBank(null, $debitLine?->account);

            $narrationLower = strtolower($cv->narration ?? '');
            $isBankTransfer = $destBank || str_contains($narrationLower, 'bank transfer') || str_contains($narrationLower, 'inter-bank') || str_contains($narrationLower, 'internal transfer');

            // 1. Source Account Record (Debit / Outflow from Source Bank)
            if ($sourceBank) {
                $sourceTitle = $destBank 
                    ? 'Transfer to ' . $destBank->bank_name . ' (Contra Transfer)' 
                    : ($isBankTransfer ? 'Internal Contra Bank Transfer' : 'Site Petty Cash Box (Bank Cash Withdrawal)');

                $sourceNarration = $cv->narration 
                    ?: ($destBank ? 'Internal Bank Transfer to ' . $destBank->bank_name : 'Cash Withdrawal from Bank into Site Petty Cash Box');

                $contraSourceTxn = [
                    'id' => 'contra_out_' . $cv->id,
                    'date' => $cvDate ? $cvDate->format('d/m/Y') : '—',
                    'raw_date' => $cvDate ? $cvDate->timestamp : 0,
                    'datetime_formatted' => $cvDate ? $cvDate->format('d M Y') : '—',
                    'voucher_no' => $cv->voucher_number,
                    'customer_name' => $sourceTitle,
                    'customer_phone' => '',
                    'narration' => $sourceNarration,
                    'payment_mode' => 'CONTRA',
                    'cheque_no' => $cv->reference_no ?: '—',
                    'drawee_bank' => '—',
                    'bank_ref_no' => $cv->reference_no ?: $cv->voucher_number,
                    'bank_name' => $sourceBank->bank_name,
                    'bank_account_id' => $sourceBank->id,
                    'type' => 'Debit',
                    'amount' => $amount,
                    'balance' => (float)($sourceBank->current_balance ?? 0),
                    'remarks' => $cv->narration ?: $sourceTitle
                ];

                $allRecentTxns[] = $contraSourceTxn;
                $recentTransactions[$sourceBank->id][] = $contraSourceTxn;
            }

            // 2. Destination Account Record (Credit / Inflow to Destination Bank for inter-bank transfers)
            if ($destBank && (!$sourceBank || $destBank->id !== $sourceBank->id)) {
                $destTitle = $sourceBank 
                    ? 'Transfer from ' . $sourceBank->bank_name . ' (Contra Receipt)' 
                    : 'Internal Contra Bank Receipt';

                $destNarration = $cv->narration 
                    ?: ($sourceBank ? 'Internal Bank Transfer received from ' . $sourceBank->bank_name : 'Internal Contra Transfer Inflow');

                $contraDestTxn = [
                    'id' => 'contra_in_' . $cv->id,
                    'date' => $cvDate ? $cvDate->format('d/m/Y') : '—',
                    'raw_date' => $cvDate ? $cvDate->timestamp : 0,
                    'datetime_formatted' => $cvDate ? $cvDate->format('d M Y') : '—',
                    'voucher_no' => $cv->voucher_number,
                    'customer_name' => $destTitle,
                    'customer_phone' => '',
                    'narration' => $destNarration,
                    'payment_mode' => 'CONTRA',
                    'cheque_no' => $cv->reference_no ?: '—',
                    'drawee_bank' => '—',
                    'bank_ref_no' => $cv->reference_no ?: $cv->voucher_number,
                    'bank_name' => $destBank->bank_name,
                    'bank_account_id' => $destBank->id,
                    'type' => 'Credit',
                    'amount' => $amount,
                    'balance' => (float)($destBank->current_balance ?? 0),
                    'remarks' => $cv->narration ?: $destTitle
                ];

                $allRecentTxns[] = $contraDestTxn;
                $recentTransactions[$destBank->id][] = $contraDestTxn;
            }
        }

        // Outward Debits: Payment Vouchers (Bank Loan EMIs, Prepayments, Broker Commission, Partner Distributions, Customer Refunds, etc.)
        $allPaymentVouchers = \App\Models\Voucher::where('type', 'Payment')
            ->where('narration', 'not like', '%[Historical]%')
            ->where(function ($q) {
                $q->whereNotNull('company_bank_account_id')
                  ->orWhere('voucher_number', 'LIKE', 'PY-REF-%')
                  ->orWhere('narration', 'LIKE', '%Customer Refund%');
            })
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        foreach ($allPaymentVouchers as $pv) {
            $bankAccId = $pv->company_bank_account_id;
            $refData = null;
            $chequeNo = '—';

            if ($pv->reference_no) {
                $trimmedRef = trim($pv->reference_no);
                if (str_starts_with($trimmedRef, '{') || str_starts_with($trimmedRef, '[')) {
                    $refData = json_decode($trimmedRef, true);
                    if (is_array($refData)) {
                        if (!empty($refData['reference_no'])) {
                            $chequeNo = $refData['reference_no'];
                        } elseif (!empty($refData['cheque_no'])) {
                            $chequeNo = $refData['cheque_no'];
                        }
                    }
                } else {
                    $chequeNo = $pv->reference_no;
                }
            }

            if (!$bankAccId && is_array($refData) && !empty($refData['company_bank_account_id'])) {
                $bankAccId = (int) $refData['company_bank_account_id'];
            }

            $acc = $bankAccounts->firstWhere('id', $bankAccId);
            $pvDate = $pv->date ? Carbon::parse($pv->date) : Carbon::parse($pv->created_at);

            $amount = (float)(\App\Models\VoucherLine::where('voucher_id', $pv->id)->where('credit', '>', 0)->value('credit')
                     ?? \App\Models\VoucherLine::where('voucher_id', $pv->id)->sum('debit'));

            if ($amount <= 0 && is_array($refData) && isset($refData['allocations'][0]['amount'])) {
                $amount = (float) $refData['allocations'][0]['amount'];
            }

            $narrationLower = strtolower($pv->narration ?? '');
            $vNoLower = strtolower($pv->voucher_number ?? '');

            // Dynamically classify the payment voucher
            if (str_starts_with($vNoLower, 'py-ref-') || str_contains($narrationLower, 'customer refund') || str_contains($narrationLower, 'booking cancellation refund')) {
                $customerName = 'Customer Refund Payout';
                $paymentMode = (is_array($refData) && !empty($refData['payment_mode'])) 
                    ? strtoupper(explode(' (', $refData['payment_mode'])[0]) 
                    : 'BANK TRANSFER';
            } elseif (str_starts_with($vNoLower, 'pay-loan-payoff-') || str_contains($narrationLower, 'prepayment') || str_contains($narrationLower, 'foreclosure')) {
                $customerName = str_contains($narrationLower, 'foreclosure') ? 'Bank Loan Foreclosure' : 'Bank Loan Prepayment';
                $paymentMode = 'BANK TRANSFER';
            } elseif (str_starts_with($vNoLower, 'pay-loan-') || str_contains($narrationLower, 'bank loan') || str_contains($narrationLower, 'loan emi')) {
                $customerName = 'Bank Loan EMI Repayment';
                $paymentMode = 'BANK TRANSFER';
            } elseif (str_starts_with($vNoLower, 'pv-broker-') || str_contains($narrationLower, 'broker')) {
                $customerName = 'Broker Commission Payout';
                $paymentMode = 'BANK TRANSFER';
            } elseif (str_starts_with($vNoLower, 'pv-partner-') || str_contains($narrationLower, 'partner') || str_contains($narrationLower, 'allocation')) {
                $customerName = 'Partner Profit Distribution';
                $paymentMode = 'BANK TRANSFER';
            } else {
                $customerName = $pv->narration ? \Illuminate\Support\Str::limit($pv->narration, 45) : 'Payment Voucher';
                $paymentMode = 'BANK TRANSFER';
            }

            $paymentTxn = [
                'id' => 'payment_v_' . $pv->id,
                'date' => $pvDate ? $pvDate->format('d/m/Y') : '—',
                'raw_date' => $pvDate ? $pvDate->timestamp : 0,
                'datetime_formatted' => $pvDate ? $pvDate->format('d M Y') : '—',
                'voucher_no' => $pv->voucher_number,
                'customer_name' => $customerName,
                'customer_phone' => '',
                'narration' => $pv->narration ?: $customerName,
                'payment_mode' => $paymentMode,
                'cheque_no' => $chequeNo,
                'drawee_bank' => '—',
                'bank_ref_no' => $pv->voucher_number,
                'bank_name' => $acc?->bank_name ?? 'Treasury',
                'bank_account_id' => $bankAccId,
                'type' => 'Debit',
                'amount' => $amount,
                'balance' => (float)($acc?->current_balance ?? 0),
                'remarks' => $pv->narration ?: $customerName
            ];

            $allRecentTxns[] = $paymentTxn;
            if ($bankAccId) {
                $recentTransactions[$bankAccId][] = $paymentTxn;
            }
        }

        // Ensure all bank accounts have an array entry and sort by date descending
        foreach ($bankAccounts as $account) {
            if (!isset($recentTransactions[$account->id])) {
                $recentTransactions[$account->id] = [];
            } else {
                usort($recentTransactions[$account->id], fn($a, $b) => ($b['raw_date'] <=> $a['raw_date']));
            }
        }
        usort($allRecentTxns, fn($a, $b) => ($b['raw_date'] <=> $a['raw_date']));

        return view('treasury.dashboard', compact(
            'bankAccounts',
            'totalBalance',
            'availableBalance',
            'totalPendingCount',
            'totalPendingAmount',
            'totalRealizedCount',
            'totalRealizedAmount',
            'todayRealizedCount',
            'todayRealizedAmount',
            'bouncedCount',
            'bouncedAmount',
            'recentTransactions',
            'allRecentTxns'
        ));
    }

    /**
     * Treasury Report: Comprehensive Cash Inflow and Outflow Statement across Company Bank Accounts
     */
    public function report(Request $request): View
    {
        $bankAccounts = CompanyBankAccount::orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $selectedBankId = $request->query('bank_account_id', 'all');
        $flowType = $request->query('flow_type', 'all');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        $search = trim((string)$request->query('search', ''));

        // All collected transactions
        $allTransactions = [];

        // 1. INFLOW: Realized Customer Receipts
        $receipts = Receipt::with(['customer', 'companyBankAccount', 'realizationLogs'])
            ->where('realization_status', 'realized')
            ->whereNotNull('company_bank_account_id')
            ->get();

        foreach ($receipts as $receipt) {
            $acc = $receipt->companyBankAccount;
            $realizedAt = $receipt->realized_at ? Carbon::parse($receipt->realized_at) : ($receipt->receipt_date ? Carbon::parse($receipt->receipt_date) : Carbon::parse($receipt->created_at));
            $log = $receipt->realizationLogs->first();
            $bankRef = $log?->bank_reference_no ?? $receipt->reference_no;

            $allTransactions[] = [
                'id'                 => 'rcpt_' . $receipt->id,
                'raw_timestamp'      => $realizedAt->timestamp,
                'date'               => $realizedAt->format('Y-m-d'),
                'date_formatted'     => $realizedAt->format('d M Y'),
                'datetime_formatted' => $realizedAt->format('d M Y, h:i A'),
                'bank_account_id'    => $receipt->company_bank_account_id,
                'bank_name'          => $acc?->bank_name ?? 'Treasury Bank',
                'account_number'     => $acc?->account_number ?? '',
                'flow_type'          => 'inflow',
                'category'           => 'Customer Collection',
                'voucher_no'         => $receipt->receipt_no ?? ('RV/2025-26/' . str_pad((string)$receipt->id, 6, '0', STR_PAD_LEFT)),
                'counterparty'       => $receipt->customer?->name ?? 'Direct Customer',
                'payment_mode'       => $receipt->payment_mode ?: 'Cheque / Bank',
                'reference_no'       => $bankRef ?: '—',
                'narration'          => 'Customer realization: ' . ($receipt->customer?->name ?? 'Customer') . ' (' . ($receipt->payment_mode ?? 'Instrument') . ')',
                'inflow_amount'      => (float) $receipt->amount,
                'outflow_amount'     => 0.00,
            ];
        }

        // 2. OUTFLOW: Contractor RA Bill Payments
        $raPayments = \App\Models\RaBillPayment::with(['raBill.contractor', 'companyBankAccount'])
            ->whereNotNull('company_bank_account_id')
            ->where(function ($q) {
                $q->where('is_historical', false)->orWhereNull('is_historical');
            })
            ->get();

        foreach ($raPayments as $raPay) {
            $acc = $raPay->companyBankAccount;
            $payDate = $raPay->payment_date ? Carbon::parse($raPay->payment_date) : Carbon::parse($raPay->created_at);
            $contractorName = $raPay->raBill?->contractor?->name ?? 'Contractor';

            $allTransactions[] = [
                'id'                 => 'ra_' . $raPay->id,
                'raw_timestamp'      => $payDate->timestamp,
                'date'               => $payDate->format('Y-m-d'),
                'date_formatted'     => $payDate->format('d M Y'),
                'datetime_formatted' => $payDate->format('d M Y'),
                'bank_account_id'    => $raPay->company_bank_account_id,
                'bank_name'          => $acc?->bank_name ?? 'Treasury Bank',
                'account_number'     => $acc?->account_number ?? '',
                'flow_type'          => 'outflow',
                'category'           => 'Contractor RA Claim',
                'voucher_no'         => 'PAY/' . str_pad((string)$raPay->id, 5, '0', STR_PAD_LEFT),
                'counterparty'       => $contractorName,
                'payment_mode'       => str_replace('_', ' ', $raPay->payment_mode ?: 'Bank Transfer'),
                'reference_no'       => $raPay->reference_no ?: '—',
                'narration'          => 'Payment to ' . $contractorName . ' (RA Bill #' . ($raPay->ra_bill_id ?? '') . ')',
                'inflow_amount'      => 0.00,
                'outflow_amount'     => (float) $raPay->paid_amount,
            ];
        }

        // 3. OUTFLOW: Site Expense Payments
        $sepPayments = \App\Models\SiteExpensePayment::with('companyBankAccount')
            ->whereNotNull('company_bank_account_id')
            ->get();

        foreach ($sepPayments as $sep) {
            $acc = $sep->companyBankAccount;
            $payDate = $sep->payment_date ? Carbon::parse($sep->payment_date) : Carbon::parse($sep->created_at);

            $allTransactions[] = [
                'id'                 => 'sep_' . $sep->id,
                'raw_timestamp'      => $payDate->timestamp,
                'date'               => $payDate->format('Y-m-d'),
                'date_formatted'     => $payDate->format('d M Y'),
                'datetime_formatted' => $payDate->format('d M Y'),
                'bank_account_id'    => $sep->company_bank_account_id,
                'bank_name'          => $acc?->bank_name ?? 'Treasury Bank',
                'account_number'     => $acc?->account_number ?? '',
                'flow_type'          => 'outflow',
                'category'           => 'Site Expense',
                'voucher_no'         => 'EXP/' . str_pad((string)$sep->id, 5, '0', STR_PAD_LEFT),
                'counterparty'       => 'Site Expense Payee',
                'payment_mode'       => str_replace('_', ' ', $sep->payment_mode ?: 'Bank Transfer'),
                'reference_no'       => $sep->reference_no ?: '—',
                'narration'          => $sep->remarks ?: 'Site Expense Payment',
                'inflow_amount'      => 0.00,
                'outflow_amount'     => (float) $sep->paid_amount,
            ];
        }

        // 4. OUTFLOW: Direct Site Expenses with payment_source_type = 'bank'
        $directSiteExpenses = \App\Models\SiteExpense::where('payment_source_type', 'bank')
            ->whereNotNull('company_bank_account_id')
            ->where('paid_amount', '>', 0)
            ->get();

        foreach ($directSiteExpenses as $dse) {
            $acc = $bankAccounts->firstWhere('id', $dse->company_bank_account_id);
            $payDate = $dse->voucher_date ? Carbon::parse($dse->voucher_date) : Carbon::parse($dse->created_at);
            $payeeName = $dse->casual_payee_name ?: ($dse->vendor?->name ?? 'Site Vendor');

            $allTransactions[] = [
                'id'                 => 'dse_' . $dse->id,
                'raw_timestamp'      => $payDate->timestamp,
                'date'               => $payDate->format('Y-m-d'),
                'date_formatted'     => $payDate->format('d M Y'),
                'datetime_formatted' => $payDate->format('d M Y'),
                'bank_account_id'    => $dse->company_bank_account_id,
                'bank_name'          => $acc?->bank_name ?? 'Treasury Bank',
                'account_number'     => $acc?->account_number ?? '',
                'flow_type'          => 'outflow',
                'category'           => 'Direct Site Expense',
                'voucher_no'         => $dse->voucher_number ?? ('EXP-' . $dse->id),
                'counterparty'       => $payeeName,
                'payment_mode'       => 'Bank Payment',
                'reference_no'       => $dse->transaction_reference_no ?: '—',
                'narration'          => ($dse->expense_category_name ?? 'Site Expense') . ($dse->narration ? ' — ' . $dse->narration : ''),
                'inflow_amount'      => 0.00,
                'outflow_amount'     => (float) $dse->paid_amount,
            ];
        }

        // Helper to match a chart of account or bank id to a CompanyBankAccount
        $resolveBank = function ($bankId, $chartAcc) use ($bankAccounts) {
            if ($bankId) {
                $found = $bankAccounts->firstWhere('id', $bankId);
                if ($found) return $found;
            }
            if ($chartAcc) {
                $accName = strtolower($chartAcc->name ?? '');
                foreach ($bankAccounts as $ba) {
                    $bName = strtolower($ba->bank_name ?? '');
                    if ($bName && (str_contains($accName, $bName) || str_contains($bName, $accName))) {
                        return $ba;
                    }
                    if ($ba->account_number && str_contains($accName, (string)$ba->account_number)) {
                        return $ba;
                    }
                }
            }
            return null;
        };

        // 5. OUTFLOW / CONTRA: Payment and Contra Vouchers
        $vouchers = \App\Models\Voucher::whereIn('type', ['Payment', 'Contra'])->with(['lines.account'])->get();
        foreach ($vouchers as $v) {
            $date = $v->date ? Carbon::parse($v->date) : Carbon::parse($v->created_at);
            $creditLine = $v->lines->firstWhere('credit', '>', 0);
            $debitLine = $v->lines->firstWhere('debit', '>', 0);
            $amount = (float)($creditLine?->credit ?? $debitLine?->debit ?? 0);
            if ($amount <= 0) continue;

            if ($v->type === 'Contra') {
                $sourceBank = $resolveBank($v->company_bank_account_id, $creditLine?->account);
                $destBank = $resolveBank(null, $debitLine?->account);
                $narrationLower = strtolower($v->narration ?? '');
                $isBankTransfer = $destBank || str_contains($narrationLower, 'bank transfer') || str_contains($narrationLower, 'inter-bank') || str_contains($narrationLower, 'internal transfer');

                // Source Bank Outflow
                if ($sourceBank) {
                    $category = $destBank ? 'Contra Bank Transfer' : 'Contra Cash Withdrawal';
                    $counterparty = $destBank ? $destBank->bank_name : 'Site Petty Cash Box';
                    $allTransactions[] = [
                        'id'                 => 'vch_out_' . $v->id,
                        'raw_timestamp'      => $date->timestamp,
                        'date'               => $date->format('Y-m-d'),
                        'date_formatted'     => $date->format('d M Y'),
                        'datetime_formatted' => $date->format('d M Y'),
                        'bank_account_id'    => $sourceBank->id,
                        'bank_name'          => $sourceBank->bank_name,
                        'account_number'     => $sourceBank->account_number ?? '',
                        'flow_type'          => 'outflow',
                        'category'           => $category,
                        'voucher_no'         => $v->voucher_number,
                        'counterparty'       => $counterparty,
                        'payment_mode'       => 'Contra Transfer',
                        'reference_no'       => $v->reference_no ?: $v->voucher_number,
                        'narration'          => $v->narration ?: ($destBank ? 'Transfer to ' . $destBank->bank_name : 'Cash Withdrawal'),
                        'inflow_amount'      => 0.00,
                        'outflow_amount'     => $amount,
                    ];
                }

                // Destination Bank Inflow (if inter-bank transfer)
                if ($destBank && (!$sourceBank || $destBank->id !== $sourceBank->id)) {
                    $allTransactions[] = [
                        'id'                 => 'vch_in_' . $v->id,
                        'raw_timestamp'      => $date->timestamp,
                        'date'               => $date->format('Y-m-d'),
                        'date_formatted'     => $date->format('d M Y'),
                        'datetime_formatted' => $date->format('d M Y'),
                        'bank_account_id'    => $destBank->id,
                        'bank_name'          => $destBank->bank_name,
                        'account_number'     => $destBank->account_number ?? '',
                        'flow_type'          => 'inflow',
                        'category'           => 'Contra Bank Receipt',
                        'voucher_no'         => $v->voucher_number,
                        'counterparty'       => $sourceBank ? $sourceBank->bank_name : 'Internal Contra Transfer',
                        'payment_mode'       => 'Contra Transfer',
                        'reference_no'       => $v->reference_no ?: $v->voucher_number,
                        'narration'          => $v->narration ?: ($sourceBank ? 'Transfer received from ' . $sourceBank->bank_name : 'Contra Inflow'),
                        'inflow_amount'      => $amount,
                        'outflow_amount'     => 0.00,
                    ];
                }
                continue;
            }

            // Payment Vouchers (Outflow)
            $bankId = $v->company_bank_account_id;
            if (!$bankId) {
                $narr = $v->narration ?? '';
                foreach ($bankAccounts as $ba) {
                    if (str_contains($narr, $ba->bank_name) || ($ba->account_number && str_contains($narr, $ba->account_number))) {
                        $bankId = $ba->id;
                        break;
                    }
                }
                if (!$bankId) {
                    $bankId = $bankAccounts->first()?->id ?? 1;
                }
            }
            $acc = $bankAccounts->firstWhere('id', $bankId);

            $category = 'Bank Payment Voucher';
            $vNo = strtolower($v->voucher_number ?? '');
            $narr = strtolower($v->narration ?? '');
            $counterparty = 'Payment Recipient';

            if (str_starts_with($vNo, 'py-ref-') || str_contains($narr, 'refund') || str_contains($narr, 'cancellation')) {
                $category = 'Customer Refund';
                $counterparty = 'Customer';
            } elseif (str_starts_with($vNo, 'py-ptr-') || str_contains($narr, 'partner')) {
                $category = 'Partner Profit Drawing';
                $counterparty = 'Equity Partner';
            } elseif (str_starts_with($vNo, 'pv-broker-') || str_contains($narr, 'broker')) {
                $category = 'Broker Commission';
                $counterparty = 'Sales Broker';
            } elseif (str_starts_with($vNo, 'pay-loan-') || str_contains($narr, 'loan') || str_contains($narr, 'emi')) {
                $category = 'Bank Loan Repayment';
                $counterparty = 'Lending Bank';
            }

            if ($v->narration && strlen($v->narration) > 5) {
                $counterparty = \Illuminate\Support\Str::limit($v->narration, 35);
            }

            $allTransactions[] = [
                'id'                 => 'vch_' . $v->id,
                'raw_timestamp'      => $date->timestamp,
                'date'               => $date->format('Y-m-d'),
                'date_formatted'     => $date->format('d M Y'),
                'datetime_formatted' => $date->format('d M Y'),
                'bank_account_id'    => $bankId,
                'bank_name'          => $acc?->bank_name ?? 'Treasury Bank',
                'account_number'     => $acc?->account_number ?? '',
                'flow_type'          => 'outflow',
                'category'           => $category,
                'voucher_no'         => $v->voucher_number,
                'counterparty'       => $counterparty,
                'payment_mode'       => 'Bank Voucher',
                'reference_no'       => $v->reference_no ?: $v->voucher_number,
                'narration'          => $v->narration ?: $category,
                'inflow_amount'      => 0.00,
                'outflow_amount'     => $amount,
            ];
        }

        // Sort all chronologically for running balance computation
        usort($allTransactions, fn($a, $b) => ($a['raw_timestamp'] <=> $b['raw_timestamp']));

        // Compute running balance per bank account
        $runningBalances = [];
        foreach ($bankAccounts as $ba) {
            $runningBalances[$ba->id] = (float) $ba->opening_balance;
        }

        foreach ($allTransactions as &$txn) {
            $bId = $txn['bank_account_id'];
            if (!isset($runningBalances[$bId])) {
                $runningBalances[$bId] = 0.0;
            }
            if ($txn['flow_type'] === 'inflow') {
                $runningBalances[$bId] += $txn['inflow_amount'];
            } else {
                $runningBalances[$bId] -= $txn['outflow_amount'];
            }
            $txn['running_balance'] = $runningBalances[$bId];
        }
        unset($txn);

        // Filter transactions based on request criteria
        $filteredTransactions = array_filter($allTransactions, function ($txn) use ($selectedBankId, $flowType, $dateFrom, $dateTo, $search) {
            if ($selectedBankId && $selectedBankId !== 'all' && (string)$txn['bank_account_id'] !== (string)$selectedBankId) {
                return false;
            }
            if ($flowType && $flowType !== 'all' && $txn['flow_type'] !== $flowType) {
                return false;
            }
            if ($dateFrom && $txn['date'] < $dateFrom) {
                return false;
            }
            if ($dateTo && $txn['date'] > $dateTo) {
                return false;
            }
            if ($search !== '') {
                $haystack = strtolower($txn['voucher_no'] . ' ' . $txn['counterparty'] . ' ' . $txn['narration'] . ' ' . $txn['reference_no'] . ' ' . $txn['category'] . ' ' . $txn['bank_name']);
                if (!str_contains($haystack, strtolower($search))) {
                    return false;
                }
            }
            return true;
        });

        // Sort descending (latest transaction first) for report display
        usort($filteredTransactions, fn($a, $b) => ($b['raw_timestamp'] <=> $a['raw_timestamp']));

        // Aggregates & KPIs
        $totalInflow = array_sum(array_column($filteredTransactions, 'inflow_amount'));
        $totalOutflow = array_sum(array_column($filteredTransactions, 'outflow_amount'));
        $netCashFlow = $totalInflow - $totalOutflow;

        $inflowCount = count(array_filter($filteredTransactions, fn($t) => $t['flow_type'] === 'inflow'));
        $outflowCount = count(array_filter($filteredTransactions, fn($t) => $t['flow_type'] === 'outflow'));

        // Target bank opening & closing balance
        if ($selectedBankId && $selectedBankId !== 'all') {
            $activeBank = $bankAccounts->firstWhere('id', $selectedBankId);
            $totalOpeningBalance = (float)($activeBank?->opening_balance ?? 0);
            $totalCurrentBalance = (float)($activeBank?->current_balance ?? 0);
        } else {
            $totalOpeningBalance = (float)$bankAccounts->sum('opening_balance');
            $totalCurrentBalance = (float)$bankAccounts->sum('current_balance');
        }

        // Category breakdown
        $inflowCategories = [];
        $outflowCategories = [];
        foreach ($filteredTransactions as $t) {
            $cat = $t['category'];
            if ($t['flow_type'] === 'inflow') {
                $inflowCategories[$cat] = ($inflowCategories[$cat] ?? 0) + $t['inflow_amount'];
            } else {
                $outflowCategories[$cat] = ($outflowCategories[$cat] ?? 0) + $t['outflow_amount'];
            }
        }
        arsort($inflowCategories);
        arsort($outflowCategories);

        // Paginate filtered transactions (default 15 per page to eliminate endless scrolling)
        $perPage = (int)$request->query('per_page', 15);
        if ($perPage <= 0) $perPage = 15;
        $currentPage = Paginator::resolveCurrentPage() ?: 1;
        $currentItems = array_slice($filteredTransactions, ($currentPage - 1) * $perPage, $perPage);
        $paginatedTransactions = new LengthAwarePaginator(
            $currentItems,
            count($filteredTransactions),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        usort($allTransactions, fn($a, $b) => ($b['raw_timestamp'] <=> $a['raw_timestamp']));

        return view('treasury.report', compact(
            'bankAccounts',
            'allTransactions',
            'selectedBankId',
            'flowType',
            'dateFrom',
            'dateTo',
            'search',
            'filteredTransactions',
            'paginatedTransactions',
            'perPage',
            'totalOpeningBalance',
            'totalInflow',
            'totalOutflow',
            'netCashFlow',
            'totalCurrentBalance',
            'inflowCount',
            'outflowCount',
            'inflowCategories',
            'outflowCategories'
        ));
    }
}

