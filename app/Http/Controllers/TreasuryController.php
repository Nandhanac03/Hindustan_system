<?php

namespace App\Http\Controllers;

use App\Models\CompanyBankAccount;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

        $totalBalance = $bankAccounts->sum('current_balance');
        // Assuming available balance is same as current for now, or calculate differently
        $availableBalance = $bankAccounts->sum('current_balance');

        $recentTransactions = [];
        foreach ($bankAccounts as $account) {
            $receipts = \App\Models\Receipt::with('customer')
                ->where('company_bank_account_id', $account->id)
                ->where('realization_status', 'realized')
                ->orderBy('realized_at', 'desc')
                ->limit(20)
                ->get();
            
            $txns = [];
            foreach ($receipts as $receipt) {
                $txns[] = [
                    'date' => $receipt->realized_at ? \Carbon\Carbon::parse($receipt->realized_at)->format('d/m/Y') : \Carbon\Carbon::parse($receipt->receipt_date)->format('d/m/Y'),
                    'voucher_no' => $receipt->receipt_no ?? 'RV/2025-26/'.str_pad($receipt->id, 6, '0', STR_PAD_LEFT),
                    'narration' => 'Realized from ' . ($receipt->customer->name ?? 'Customer') . ' (' . ($receipt->payment_mode ?? 'Instrument') . ' ' . ($receipt->reference_no ?? '') . ')',
                    'type' => 'Credit',
                    'amount' => $receipt->amount,
                    'balance' => $account->current_balance // Showing current balance
                ];
            }
            $recentTransactions[$account->id] = $txns;
        }

        return view('treasury.dashboard', compact('bankAccounts', 'totalBalance', 'availableBalance', 'recentTransactions'));
    }
}
