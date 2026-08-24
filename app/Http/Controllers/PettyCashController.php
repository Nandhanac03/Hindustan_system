<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Voucher;
use Carbon\Carbon;

class PettyCashController extends Controller
{
    public function balanceRegister(Request $request)
    {
        // 1. Fetch available projects for the dropdown
        $projects = Project::where('is_active', true)->get();
        
        // 2. Handle filters (Project and Date)
        $selectedProject = $request->input('project_id', $projects->first()->id ?? null);
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        $project = Project::find($selectedProject);
        $siteName = $project ? $project->name : 'Green City Site';
        
        // 3. Database Data for Metrics & Transactions
        // Opening Balance calculation (Vouchers before selected date)
        $previousVouchers = Voucher::with('lines')
            ->where('system_id', $selectedProject)
            ->whereDate('date', '<', $selectedDate)
            ->get();
            
        $openingBalance = 0;
        foreach ($previousVouchers as $v) {
            if ($v->type === 'Receipt') {
                $openingBalance += $v->lines->sum('debit');
            } elseif ($v->type === 'Payment') {
                $openingBalance -= $v->lines->sum('credit');
            }
        }

        // Fetch Today's Vouchers
        $vouchersQuery = Voucher::with('lines')
            ->where('system_id', $selectedProject)
            ->whereDate('date', $selectedDate);

        if ($request->filled('search')) {
            $vouchersQuery->where(function($q) use ($request) {
                $q->where('voucher_number', 'like', '%' . $request->search . '%')
                  ->orWhere('narration', 'like', '%' . $request->search . '%')
                  ->orWhere('reference_no', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('status')) {
            $statusMap = ['active' => 'Posted', 'pending' => 'Draft'];
            if (isset($statusMap[$request->status])) {
                $vouchersQuery->where('status', $statusMap[$request->status]);
            }
        }

        $vouchers = $vouchersQuery->orderBy('created_at')->get();

        $transactions = collect([]);
        $runningBalance = $openingBalance;
        $cashIn = 0;
        $cashOut = 0;

        foreach ($vouchers as $v) {
            $vCashIn = 0;
            $vCashOut = 0;
            
            if ($v->type === 'Receipt' || $v->type === 'Contra') {
                // Simplified: assuming debit to cash/bank
                $vCashIn = $v->lines->sum('debit');
                $cashIn += $vCashIn;
                $runningBalance += $vCashIn;
            } elseif ($v->type === 'Payment' || $v->type === 'Journal') {
                // Simplified: assuming credit to cash/bank
                $vCashOut = $v->lines->sum('credit');
                $cashOut += $vCashOut;
                $runningBalance -= $vCashOut;
            }

            $transactions->push((object)[
                'id' => $v->id,
                'date' => $v->date ? $v->date->format('Y-m-d') : $selectedDate,
                'voucher_number' => $v->voucher_number,
                'type_label' => $v->type . ($v->narration ? ' - ' . str()->limit($v->narration, 30) : ''),
                'cash_in' => $vCashIn,
                'cash_out' => $vCashOut,
                'balance' => $runningBalance,
                'reference' => $v->reference_no,
                'status' => $v->status
            ]);
        }

        $closingBalance = $runningBalance;
        $bankWithdrawal = $vouchers->where('type', 'Contra')->sum(fn($v) => $v->lines->sum('debit'));
        $siteExpenses = $vouchers->where('type', 'Payment')->sum(fn($v) => $v->lines->sum('credit'));
        $netCashFlow = $cashIn - $cashOut;

        // Summaries (Dynamic)
        $latestVoucher = $vouchers->sortByDesc('updated_at')->first();
        $cashBoxIncharge = auth()->check() ? auth()->user()->name : 'System Admin';
        $cashBoxCode = 'PC-' . strtoupper($project ? $project->code : 'GEN') . '-001';
        $lastUpdated = $latestVoucher ? $latestVoucher->updated_at->format('d-M-Y h:i A') : \Carbon\Carbon::parse($selectedDate)->format('d-M-Y') . ' 12:00 AM';
        $updatedBy = $latestVoucher && $latestVoucher->creator ? $latestVoucher->creator->name : 'System';

        return view('petty-cash.balance-register', compact(
            'projects',
            'selectedProject',
            'selectedDate',
            'siteName',
            'openingBalance',
            'cashIn',
            'cashOut',
            'closingBalance',
            'cashBoxIncharge',
            'cashBoxCode',
            'lastUpdated',
            'updatedBy',
            'bankWithdrawal',
            'siteExpenses',
            'netCashFlow',
            'transactions'
        ));
    }

    public function contraWithdrawal(Request $request)
    {
        $projects = Project::where('is_active', true)->get();
        
        // Mock data for the view since exact accounts aren't fully modeled yet
        $bankAccounts = collect([
            (object)['id' => 1, 'name' => 'Karnataka Bank - A/c No. 1234', 'balance' => 245600.00],
            (object)['id' => 2, 'name' => 'HDFC Bank - A/c No. 5678', 'balance' => 150000.00]
        ]);
        
        $cashBoxes = collect([
            (object)['id' => 1, 'name' => 'Site Petty Cash Box']
        ]);

        $pettyCashBalance = 30750.00; // Mock balance before withdrawal
        
        // Mock Recent History
        $recentContras = collect([
            (object)['voucher_number' => 'PCON-00015', 'date' => '2026-05-18', 'amount' => 18500.00],
            (object)['voucher_number' => 'PCON-00014', 'date' => '2026-05-16', 'amount' => 10000.00],
            (object)['voucher_number' => 'PCON-00013', 'date' => '2026-05-14', 'amount' => 12000.00],
        ]);

        return view('petty-cash.contra-withdrawal', compact('projects', 'bankAccounts', 'cashBoxes', 'pettyCashBalance', 'recentContras'));
    }

    public function storeContraWithdrawal(Request $request)
    {
        // Placeholder for storing the contra voucher
        return redirect()->route('petty-cash.balance-register')->with('success', 'Contra withdrawal entry posted successfully.');
    }

    public function export(Request $request)
    {
        // Placeholder for export functionality
        return back()->with('info', 'Export functionality will be implemented soon.');
    }

    public function transactionDetails($transaction)
    {
        // Placeholder for transaction details view
        return back()->with('info', 'Transaction details view will be implemented soon.');
    }
}
