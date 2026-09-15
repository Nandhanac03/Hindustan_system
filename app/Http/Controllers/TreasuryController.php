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

        // Fetch recent transactions
        $recentTransactions = [];
        $allRecentTxns = [];

        $allReceipts = Receipt::with(['customer', 'companyBankAccount', 'realizationLogs'])
            ->where('realization_status', 'realized')
            ->orderByDesc('realized_at')
            ->orderByDesc('id')
            ->limit(50)
            ->get();

        foreach ($allReceipts as $receipt) {
            $acc = $receipt->companyBankAccount;
            $realizedAt = $receipt->realized_at ? Carbon::parse($receipt->realized_at) : ($receipt->receipt_date ? Carbon::parse($receipt->receipt_date) : null);
            
            $log = $receipt->realizationLogs->first();
            $bankRef = $log?->bank_reference_no ?? $receipt->reference_no;

            $txn = [
                'id' => $receipt->id,
                'date' => $realizedAt ? $realizedAt->format('d/m/Y') : '—',
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

        // Ensure all bank accounts have an array entry
        foreach ($bankAccounts as $account) {
            if (!isset($recentTransactions[$account->id])) {
                $recentTransactions[$account->id] = [];
            }
        }

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

