<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Voucher;
use App\Models\PettyCashBox;
use App\Models\PettyCashTransaction;
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
        
        // Find or create the Petty Cash Box for this site
        $pettyCashBox = PettyCashBox::firstOrCreate(
            ['project_id' => $selectedProject],
            [
                'box_code' => 'PC-' . strtoupper($project ? $project->code : 'GEN') . '-001',
                'incharge_id' => auth()->id(),
                'current_balance' => 0
            ]
        );

        $cashBoxIncharge = $pettyCashBox->incharge ? $pettyCashBox->incharge->name : (auth()->check() ? auth()->user()->name : 'System Admin');
        $cashBoxCode = $pettyCashBox->box_code;
        
        // 3. Database Data for Metrics & Transactions
        $transactionsQuery = PettyCashTransaction::where('petty_cash_box_id', $pettyCashBox->id);
        
        // Opening Balance calculation
        $openingBalance = 0;
        if ($selectedDate) {
            $previousTransactions = (clone $transactionsQuery)->whereDate('transaction_date', '<', $selectedDate)->get();
            foreach ($previousTransactions as $t) {
                $openingBalance += $t->cash_in;
                $openingBalance -= $t->cash_out;
            }
            $transactionsQuery->whereDate('transaction_date', $selectedDate);
        } else {
            // If no date is selected, opening balance is from the very beginning (i.e. 0)
            $openingBalance = 0;
        }
            
        if ($request->filled('search')) {
            $transactionsQuery->where(function($q) use ($request) {
                $q->where('voucher_number', 'like', '%' . $request->search . '%')
                  ->orWhere('narration', 'like', '%' . $request->search . '%')
                  ->orWhere('reference_no', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $statusMap = ['active' => 'Posted', 'pending' => 'Draft'];
            if (isset($statusMap[$request->status])) {
                $transactionsQuery->where('status', $statusMap[$request->status]);
            }
        }

        $dbTransactions = $transactionsQuery->orderBy('transaction_date')->orderBy('id')->get();

        $transactions = collect([]);
        $runningBalance = $openingBalance;
        $cashIn = 0;
        $cashOut = 0;

        foreach ($dbTransactions as $t) {
            $vCashIn = $t->cash_in;
            $vCashOut = $t->cash_out;
            
            $cashIn += $vCashIn;
            $cashOut += $vCashOut;
            $runningBalance += ($vCashIn - $vCashOut);

            $transactions->push((object)[
                'id' => $t->id,
                'date' => $t->transaction_date ? \Carbon\Carbon::parse($t->transaction_date)->format('Y-m-d') : ($selectedDate ?? date('Y-m-d')),
                'voucher_number' => $t->voucher_number,
                'type_label' => $t->transaction_type . ($t->narration ? ' - ' . str()->limit($t->narration, 30) : ''),
                'cash_in' => $vCashIn,
                'cash_out' => $vCashOut,
                'balance' => $runningBalance,
                'reference' => $t->reference_no,
                'status' => $t->status
            ]);
        }

        $closingBalance = $runningBalance;
        $bankWithdrawal = $dbTransactions->where('transaction_type', 'Contra')->sum('cash_in'); // Cash in from contra
        $siteExpenses = $dbTransactions->whereIn('transaction_type', ['Payment', 'Journal'])->sum('cash_out');
        $netCashFlow = $cashIn - $cashOut;

        // Summaries (Dynamic)
        $latestTransaction = $dbTransactions->sortByDesc('updated_at')->first();
        $lastUpdated = $latestTransaction ? $latestTransaction->updated_at->format('d-M-Y h:i A') : ($selectedDate ? \Carbon\Carbon::parse($selectedDate)->format('d-M-Y') . ' 12:00 AM' : 'N/A');
        $updatedBy = $latestTransaction && $latestTransaction->creator ? $latestTransaction->creator->name : 'System';

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
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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

        // Also record in the new petty cash tables
        $pettyCashBox = PettyCashBox::firstOrCreate(
            ['project_id' => $request->input('project_id')],
            [
                'box_code' => 'PC-' . strtoupper(\App\Models\Project::find($request->input('project_id'))->code ?? 'GEN') . '-001',
                'incharge_id' => auth()->id(),
                'current_balance' => 0
            ]
        );

        $newBalance = $pettyCashBox->current_balance + $amount;
        $pettyCashBox->update(['current_balance' => $newBalance]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('petty-cash-attachments', 'public');
        }

        PettyCashTransaction::create([
            'petty_cash_box_id' => $pettyCashBox->id,
            'voucher_id' => $voucher->id,
            'transaction_date' => $request->input('date'),
            'voucher_number' => $request->input('voucher_number'),
            'transaction_type' => 'Contra',
            'reference_no' => $request->input('reference_no'),
            'narration' => $request->input('narration'),
            'attachment_path' => $attachmentPath,
            'cash_in' => $amount,
            'cash_out' => 0,
            'balance' => $newBalance,
            'created_by' => auth()->id() ?? 1,
            'status' => 'Posted',
        ]);

        return redirect()->route('petty-cash.balance-register')->with('success', 'Contra withdrawal entry posted successfully.');
    }
    public function dailySiteExpenses(Request $request)
    {
        $projects = Project::where('is_active', true)->get();
        
        $selectedProject = $request->input('project_id', $projects->first()->id ?? null);
        $fromDate = $request->input('from_date', date('Y-m-01'));
        $toDate = $request->input('to_date', date('Y-m-d'));
        $category = $request->input('category', 'All');
        $paymentMode = $request->input('payment_mode', 'All');

        $project = Project::find($selectedProject);
        $siteName = $project ? $project->name : 'Green City Site';
        
        $pettyCashBox = PettyCashBox::firstOrCreate(
            ['project_id' => $selectedProject],
            [
                'box_code' => 'PC-' . strtoupper($project ? $project->code : 'GEN') . '-001',
                'incharge_id' => auth()->id(),
                'current_balance' => 0
            ]
        );

        $query = PettyCashTransaction::where('petty_cash_box_id', $pettyCashBox->id)
            ->whereIn('transaction_type', ['Site Expense', 'Expense', 'Payment']);
            
        if ($fromDate) {
            $query->whereDate('transaction_date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('transaction_date', '<=', $toDate);
        }
        
        // Assuming narration holds the category for now since category isn't a dedicated column, 
        // or we filter by category if added. Let's just pass the filter for UI logic.
        // In a real scenario, you'd filter by an expense_category_id.
        if ($category !== 'All') {
            $query->where('narration', 'like', $category . '%');
        }
        
        if ($paymentMode !== 'All') {
            $query->where('payment_mode', $paymentMode);
        }

        $expenses = $query->orderBy('transaction_date', 'desc')->paginate(10);
        
        // Example summary
        $totalAmount = $expenses->sum('cash_out');
        
        $paymentModes = \App\Models\PaymentMode::active()->get();

        // Calculate sidebar stats
        $availableBalance = $pettyCashBox->current_balance ?? 0;
        
        $startOfMonth = now()->startOfMonth();
        $categorySummary = PettyCashTransaction::where('petty_cash_box_id', $pettyCashBox->id)
            ->whereIn('transaction_type', ['Site Expense', 'Expense', 'Payment'])
            ->whereDate('transaction_date', '>=', $startOfMonth)
            ->get()
            ->groupBy(function($item) {
                // If category is the prefix of narration
                $parts = explode('-', $item->narration);
                return trim($parts[0]) ?: 'Others';
            })
            ->map(function($group) {
                return $group->sum('cash_out');
            });

        return view('petty-cash.daily-site-expenses', compact(
            'expenses', 
            'projects', 
            'selectedProject', 
            'fromDate', 
            'toDate',
            'category',
            'paymentMode',
            'siteName',
            'totalAmount',
            'paymentModes',
            'availableBalance',
            'categorySummary'
        ));
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

    public function storeExpense(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'transaction_date' => 'required|date',
            'voucher_number' => 'required|string',
            'category' => 'required|string',
            'payment_mode' => 'required|string',
            'bill_no' => 'nullable|string',
            'bill_date' => 'nullable|date',
            'amount' => 'required|numeric|min:0.01',
            'particulars' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $project = Project::findOrFail($request->project_id);
        $pettyCashBox = PettyCashBox::firstOrCreate(
            ['project_id' => $project->id],
            [
                'box_code' => 'PC-' . strtoupper($project->code) . '-001',
                'incharge_id' => auth()->id(),
                'current_balance' => 0
            ]
        );

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('petty-cash-attachments'), $filename);
            $attachmentPath = 'petty-cash-attachments/' . $filename;
        }

        $balanceBefore = $pettyCashBox->current_balance;
        $balanceAfter = $balanceBefore - $request->amount;

        $transaction = PettyCashTransaction::create([
            'petty_cash_box_id' => $pettyCashBox->id,
            'transaction_date' => $request->transaction_date,
            'voucher_number' => $request->voucher_number,
            'transaction_type' => 'Site Expense',
            'reference_no' => $request->bill_no,
            'bill_date' => $request->bill_date,
            'narration' => $request->category . ' - ' . $request->particulars,
            'payment_mode' => $request->payment_mode,
            'cash_in' => 0,
            'cash_out' => $request->amount,
            'balance' => $balanceAfter,
            'created_by' => auth()->id(),
            'status' => 'approved',
        ]);

        if ($attachmentPath) {
            $transaction->attachment_path = $attachmentPath;
            $transaction->save();
        }

        $pettyCashBox->update(['current_balance' => $balanceAfter]);

        if ($request->input('submit_action') === 'save_new') {
            return redirect()->back()->with('success', 'Site expense recorded successfully.')->with('show_expense_modal', true);
        }

        return redirect()->back()->with('success', 'Site expense recorded successfully.');
    }
}
