<?php

namespace App\Http\Controllers;

use App\Models\CompanyBankAccount;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

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

        // Populate per-bank metrics
        foreach ($bankAccounts as $account) {
            $account->realized_count = Receipt::where('company_bank_account_id', $account->id)
                ->where('realization_status', 'realized')
                ->count();
            $account->realized_sum = (float) Receipt::where('company_bank_account_id', $account->id)
                ->where('realization_status', 'realized')
                ->sum('amount');
            $account->pending_count = Receipt::where('company_bank_account_id', $account->id)
                ->whereIn('realization_status', $pendingStatuses)
                ->count();
            $account->pending_sum = (float) Receipt::where('company_bank_account_id', $account->id)
                ->whereIn('realization_status', $pendingStatuses)
                ->sum('amount');
        }

        // Fetch recent transactions (Credits: Realized Receipts, Debits: RA Bill & Site Expense Payments)
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

        // Outward Debits: RA Bill Payments
        $allRaPayments = \App\Models\RaBillPayment::with(['raBill.contractor', 'companyBankAccount'])
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
                'cheque_no' => $sep->reference_number ?: '—',
                'drawee_bank' => '—',
                'bank_ref_no' => $sep->reference_number ?: '—',
                'bank_name' => $acc?->bank_name ?? 'Treasury',
                'bank_account_id' => $sep->company_bank_account_id,
                'type' => 'Debit',
                'amount' => (float)$sep->amount,
                'balance' => (float)($acc?->current_balance ?? 0),
                'remarks' => $sep->remarks ?: 'Site Expense Payment'
            ];

            $allRecentTxns[] = $sepTxn;
            if ($sep->company_bank_account_id) {
                $recentTransactions[$sep->company_bank_account_id][] = $sepTxn;
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
}

