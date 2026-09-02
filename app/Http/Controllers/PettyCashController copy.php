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
        $selectedDate = $request->input('date');
        
        $project = Project::find($selectedProject);
        $siteName = $project ? $project->name : 'Green City Site';
        
        $cashBoxIds = \App\Models\Account::where('name', 'like', '%Cash%')
            ->orWhere('code', 'like', 'CA%')
            ->pluck('id')
            ->toArray();

        if (empty($cashBoxIds)) {
            $cashBoxIds = [8]; // Fallback if no cash box is found yet
        }
        
        // 3. Database Data for Metrics & Transactions
        // Opening Balance calculation (Vouchers before selected date)
        if ($selectedDate) {
            $previousVouchers = Voucher::with('lines')
                ->where('system_id', $selectedProject)
                ->whereHas('lines', function($q) use ($cashBoxIds) {
                    $q->whereIn('account_id', $cashBoxIds);
                })
                ->whereDate('date', '<', $selectedDate)
                ->get();
        } else {
            $previousVouchers = collect([]);
        }
            
        $openingBalance = 0;
        foreach ($previousVouchers as $v) {
            $pettyCashLines = $v->lines->whereIn('account_id', $cashBoxIds);
            $openingBalance += $pettyCashLines->sum('debit');
            $openingBalance -= $pettyCashLines->sum('credit');
        }

        // Fetch Today's Vouchers
        $vouchersQuery = Voucher::with('lines')
            ->where('system_id', $selectedProject)
            ->whereHas('lines', function($q) use ($cashBoxIds) {
                $q->whereIn('account_id', $cashBoxIds);
            });

        if ($selectedDate) {
            $vouchersQuery->whereDate('date', $selectedDate);
        }

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
            $pettyCashLines = $v->lines->whereIn('account_id', $cashBoxIds);
            
            $vCashIn = $pettyCashLines->sum('debit');
            $vCashOut = $pettyCashLines->sum('credit');
            
            $cashIn += $vCashIn;
            $cashOut += $vCashOut;
            $runningBalance += ($vCashIn - $vCashOut);

            $transactions->push((object)[
                'id' => $v->id,
                'date' => $v->date ? $v->date->format('Y-m-d') : ($selectedDate ?? date('Y-m-d')),
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
        $bankWithdrawal = $vouchers->where('type', 'Contra')->sum(function($v) use ($cashBoxIds) {
            return $v->lines->whereIn('account_id', $cashBoxIds)->sum('debit');
        });
        $siteExpenses = $vouchers->whereIn('type', ['Payment', 'Journal'])->sum(function($v) use ($cashBoxIds) {
            return $v->lines->whereIn('account_id', $cashBoxIds)->sum('credit');
        });
        $netCashFlow = $cashIn - $cashOut;

        // Summaries (Dynamic)
        $latestVoucher = $vouchers->sortByDesc('updated_at')->first();
        $cashBoxIncharge = auth()->check() ? auth()->user()->name : 'System Admin';
        $cashBoxCode = 'PC-' . strtoupper($project ? $project->code : 'GEN') . '-001';
        $lastUpdated = $latestVoucher ? $latestVoucher->updated_at->format('d-M-Y h:i A') : ($selectedDate ? \Carbon\Carbon::parse($selectedDate)->format('d-M-Y') . ' 12:00 AM' : 'N/A');
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
        
        // Fetch actual bank accounts from CompanyBankAccount
        $bankAccounts = \App\Models\CompanyBankAccount::where('status', 'Active')->get()->map(function($bank) {
            return (object)[
                'id' => $bank->id,
                'name' => $bank->bank_name . ' - A/c No. ' . substr($bank->account_number, -4),
                'balance' => $bank->current_balance ?? 0
            ];
        });
        if ($bankAccounts->isEmpty()) {
            // Fallback for UI if DB is empty
            $bankAccounts = collect([(object)['id' => 1, 'name' => 'Default Company Bank Account', 'balance' => 0]]);
        }
        
        // Fetch actual cash boxes/accounts from DB
        $cashBoxes = \App\Models\Account::where('name', 'like', '%Cash%')->orWhere('code', 'like', 'CA%')->get();
        if ($cashBoxes->isEmpty()) {
            $cashBoxes = collect([(object)['id' => 8, 'name' => 'Site Petty Cash Box']]);
        }

        $pettyCashBalance = 0; // In a real system, calculate the current balance of the first cashBox
        
        // Fetch real recent Contra history
        $recentContras = Voucher::where('type', 'Contra')
            ->orderBy('date', 'desc')
            ->take(3)
            ->get()
            ->map(function($v) {
                return (object)[
                    'voucher_number' => $v->voucher_number,
                    'date' => $v->date,
                    'amount' => $v->lines->where('debit', '>', 0)->sum('debit') // Assuming debit line is the contra amount
                ];
            });

        // Generate next voucher number
        $lastVoucher = Voucher::where('type', 'Contra')->whereRaw("voucher_number REGEXP '^PCON-[0-9]+$'")->orderBy('id', 'desc')->first();
        if ($lastVoucher) {
            $lastNum = (int) str_replace('PCON-', '', $lastVoucher->voucher_number);
            $nextVoucherNo = 'PCON-' . str_pad($lastNum + 1, 5, '0', STR_PAD_LEFT);
        } else {
            // Fallback to count if no valid format found
            $nextVoucherNo = 'PCON-' . str_pad(Voucher::where('type', 'Contra')->count() + 1, 5, '0', STR_PAD_LEFT);
        }

        return view('petty-cash.contra-withdrawal', compact('projects', 'bankAccounts', 'cashBoxes', 'pettyCashBalance', 'recentContras', 'nextVoucherNo'));
    }

    public function storeContraWithdrawal(Request $request)
    {
        $request->validate([
            'voucher_number' => 'required',
            'project_id' => 'required',
            'bank_account_id' => 'required',
            'cash_box_id' => 'required',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        $amount = $request->input('amount');
        
        // Safely map Bank Account (CompanyBankAccount -> Chart of Account)
        $companyBank = \App\Models\CompanyBankAccount::find($request->input('bank_account_id'));
        if ($companyBank) {
            // Deduct the withdrawn amount from the actual Company Bank Account balance
            $companyBank->current_balance -= $amount;
            $companyBank->save();
        }
        
        $bankAccountName = $companyBank ? $companyBank->bank_name . ' Bank' : 'Company Bank Account';
        $bankLedgerAccount = \App\Models\Account::firstOrCreate(
            ['name' => $bankAccountName],
            ['type' => 'Asset', 'code' => 'BK-' . rand(1000, 9999), 'system_id' => $request->input('project_id')]
        );

        // Safely resolve Cash Box Account
        $cashLedgerAccount = \App\Models\Account::find($request->input('cash_box_id'));
        if (!$cashLedgerAccount) {
            $cashLedgerAccount = \App\Models\Account::firstOrCreate(
                ['name' => 'Site Petty Cash Box'],
                ['type' => 'Asset', 'code' => 'CA-' . rand(1000, 9999), 'system_id' => $request->input('project_id')]
            );
        }

        // Create the Voucher
        $voucher = Voucher::create([
            'system_id' => $request->input('project_id'),
            'voucher_number' => $request->input('voucher_number'),
            'type' => 'Contra',
            'date' => $request->input('date'),
            'narration' => $request->input('narration'),
            'reference_no' => $request->input('reference_no'),
            'created_by' => auth()->id() ?? 1,
            'status' => 'Posted',
        ]);

        // Bank Account Line (Credit - Money leaving bank)
        \App\Models\VoucherLine::create([
            'voucher_id' => $voucher->id,
            'account_id' => $bankLedgerAccount->id,
            'debit' => 0,
            'credit' => $amount,
            'line_narration' => 'Cash withdrawal from bank',
        ]);

        // Petty Cash Box Line (Debit - Money entering petty cash)
        \App\Models\VoucherLine::create([
            'voucher_id' => $voucher->id,
            'account_id' => $cashLedgerAccount->id,
            'debit' => $amount,
            'credit' => 0,
            'line_narration' => 'Petty cash receipt via contra',
        ]);

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
