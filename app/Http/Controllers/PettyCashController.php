<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Voucher;
use App\Models\PettyCashBox;
use App\Models\PettyCashTransaction;
use App\Models\JournalVoucher;
use App\Models\JournalEntry;
use App\Models\ChartOfAccount;
use App\Models\VoucherType;
use App\Models\CompanyBankAccount;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;

class PettyCashController extends Controller
{
    private function ensureHistoricalColumn(): void
    {
        try {
            if (Schema::hasTable('petty_cash_transactions') && !Schema::hasColumn('petty_cash_transactions', 'is_historical')) {
                Schema::table('petty_cash_transactions', function (Blueprint $table) {
                    $table->boolean('is_historical')->default(false)->after('status');
                });
            }
            if (Schema::hasTable('vouchers') && !Schema::hasColumn('vouchers', 'is_historical')) {
                Schema::table('vouchers', function (Blueprint $table) {
                    $table->boolean('is_historical')->default(false)->after('status');
                });
            }
        } catch (\Throwable $e) {
            // Ignore if already exists or lacks DDL privileges
        }
    }

    private function syncExpenseVoucher(PettyCashTransaction $transaction, ?string $categoryName = null, ?int $projectId = null): Voucher
    {
        $systemId = $projectId
            ?? $transaction->pettyCashBox?->project_id
            ?? auth()->user()?->system_id
            ?? 1;

        if (!$categoryName) {
            $categoryName = $transaction->category?->category
                ?? trim(explode('-', (string) $transaction->narration)[0] ?: 'General');
        }

        $expenseLedgerAccount = \App\Models\Account::withoutGlobalScopes()->firstOrCreate(
            ['name' => $categoryName, 'system_id' => $systemId],
            ['type' => 'Expense', 'code' => 'EXP-' . rand(1000, 9999), 'is_active' => true]
        );

        $cashLedgerAccount = \App\Models\Account::withoutGlobalScopes()
            ->where('name', 'Petty Cash Box')
            ->where('system_id', $systemId)
            ->first();

        if (!$cashLedgerAccount) {
            $cashLedgerAccount = \App\Models\Account::withoutGlobalScopes()->firstOrCreate(
                ['name' => 'Petty Cash Box'],
                ['type' => 'Asset', 'code' => 'CA-' . rand(1000, 9999), 'system_id' => $systemId, 'is_active' => true]
            );
        }

        $voucher = null;
        if ($transaction->voucher_id) {
            $voucher = Voucher::withoutGlobalScopes()->find($transaction->voucher_id);
        }
        if (!$voucher && $transaction->voucher_number) {
            $voucher = Voucher::withoutGlobalScopes()
                ->where('voucher_number', $transaction->voucher_number)
                ->first();
        }

        $expenseAmount = (float) $transaction->cash_out;
        $isHistorical = (bool) ($transaction->is_historical ?? false);

        if ($voucher) {
            $voucher->update([
                'system_id' => $systemId,
                'voucher_number' => $transaction->voucher_number,
                'type' => 'Payment',
                'date' => $transaction->transaction_date,
                'narration' => $transaction->narration,
                'reference_no' => $transaction->reference_no,
                'edited_by' => auth()->id() ?? $transaction->created_by ?? 1,
                'status' => 'Posted',
                'is_historical' => $isHistorical,
            ]);
        } else {
            $voucher = Voucher::create([
                'system_id' => $systemId,
                'company_bank_account_id' => null,
                'voucher_number' => $transaction->voucher_number,
                'type' => 'Payment',
                'date' => $transaction->transaction_date,
                'narration' => $transaction->narration,
                'reference_no' => $transaction->reference_no,
                'created_by' => $transaction->created_by ?? auth()->id() ?? 1,
                'status' => 'Posted',
                'is_historical' => $isHistorical,
            ]);
        }

        if ((int) $transaction->voucher_id !== (int) $voucher->id) {
            $transaction->voucher_id = $voucher->id;
            $transaction->save();
        }

        \App\Models\VoucherLine::where('voucher_id', $voucher->id)->delete();

        // Expense Account Line (Debit - Expense increases)
        \App\Models\VoucherLine::create([
            'voucher_id' => $voucher->id,
            'account_id' => $expenseLedgerAccount->id,
            'debit' => $expenseAmount,
            'credit' => 0,
            'line_narration' => $transaction->narration,
        ]);

        // Petty Cash Box Line (Credit - Petty Cash Asset decreases)
        \App\Models\VoucherLine::create([
            'voucher_id' => $voucher->id,
            'account_id' => $cashLedgerAccount->id,
            'debit' => 0,
            'credit' => $expenseAmount,
            'line_narration' => 'Paid from Petty Cash Box (' . ($transaction->payment_mode ?: 'Cash') . ')',
        ]);

        return $voucher;
    }

    private function backfillMissingExpenseVouchers(): void
    {
        $unlinkedExpenses = PettyCashTransaction::with(['category', 'pettyCashBox'])
            ->whereIn('transaction_type', ['Site Expense', 'Expense', 'Payment'])
            ->where(function ($q) {
                $q->whereNull('voucher_id')
                  ->orWhereDoesntHave('voucher.lines');
            })
            ->get();

        foreach ($unlinkedExpenses as $expense) {
            $this->syncExpenseVoucher($expense);
        }
    }

    public function balanceRegister(Request $request)
    {
        $this->ensureHistoricalColumn();
        // 1. Fetch available projects for the dropdown
        $projects = Project::where('is_active', true)->get();
        
        // 2. Handle filters (Project and Date)
        if ($request->has('project_id')) {
            $selectedProject = $request->filled('project_id') ? $request->input('project_id') : null;
        } else {
            $selectedProject = $projects->first()->id ?? null;
        }
        
        $selectedDate = $request->input('date');
        
        if ($selectedProject) {
            $project = Project::find($selectedProject);
            $siteName = $project ? $project->name : 'Site ' . $selectedProject;
            
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
            
            $transactionsQuery = PettyCashTransaction::where('petty_cash_box_id', $pettyCashBox->id);
        } else {
            $siteName = 'All Sites';
            $cashBoxIncharge = auth()->check() ? auth()->user()->name : 'System Admin';
            $cashBoxCode = 'ALL-BOXES';
            $transactionsQuery = PettyCashTransaction::query();
        }
        
        // Opening Balance calculation
        $openingBalance = 0;
        if ($selectedDate) {
            $previousTransactions = (clone $transactionsQuery)->whereDate('transaction_date', '<', $selectedDate)->get();
            foreach ($previousTransactions as $t) {
                if (!$t->is_historical) {
                    $openingBalance += $t->cash_in;
                    $openingBalance -= $t->cash_out;
                }
            }
            $transactionsQuery->whereDate('transaction_date', $selectedDate);
        } else {
            $openingBalance = 0;
        }
            
        if ($request->filled('search')) {
            $searchTerm = '%' . trim($request->search) . '%';
            $transactionsQuery->where(function($q) use ($searchTerm) {
                $q->where('voucher_number', 'like', $searchTerm)
                  ->orWhere('narration', 'like', $searchTerm)
                  ->orWhere('reference_no', 'like', $searchTerm)
                  ->orWhere('transaction_type', 'like', $searchTerm)
                  ->orWhere('payment_mode', 'like', $searchTerm);
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $transactionsQuery->whereIn('status', ['Posted', 'approved', 'Active', 'active']);
            } elseif ($request->status === 'pending') {
                $transactionsQuery->whereIn('status', ['pending', 'Draft', 'Pending', 'draft']);
            } else {
                $transactionsQuery->where('status', $request->status);
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
            if (!$t->is_historical) {
                $runningBalance += ($vCashIn - $vCashOut);
            }

            $transactions->push((object)[
                'id' => $t->id,
                'date' => $t->transaction_date ? \Carbon\Carbon::parse($t->transaction_date)->format('Y-m-d') : ($selectedDate ?? date('Y-m-d')),
                'date_formatted' => $t->transaction_date ? \Carbon\Carbon::parse($t->transaction_date)->format('d-M-Y') : date('d-M-Y'),
                'voucher_number' => $t->voucher_number,
                'type_label' => $t->narration ?: ($t->transaction_type ?: 'Petty Cash Transaction'),
                'cash_in' => $vCashIn,
                'cash_out' => $vCashOut,
                'balance' => $runningBalance,
                'reference' => $t->reference_no,
                'status' => $t->status,
                'is_historical' => (bool)($t->is_historical ?? false),
            ]);
        }

        $closingBalance = $runningBalance;
        $bankWithdrawal = $dbTransactions->where('transaction_type', 'Contra')->sum('cash_in'); // Cash in from contra
        $siteExpenses = $dbTransactions->whereIn('transaction_type', ['Payment', 'Journal', 'Site Expense'])->sum('cash_out');
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
                'bank_name' => $bank->bank_name,
                'account_name' => $bank->account_name ?? 'Account',
                'account_number' => $bank->account_number ?? '',
                'branch_name' => $bank->branch_name ?? '',
                'name' => $bank->bank_name . ' - A/c No. ' . substr($bank->account_number, -4),
                'balance' => (float)($bank->current_balance ?? 0)
            ];
        });
        if ($bankAccounts->isEmpty()) {
            // Fallback for UI if DB is empty
            $bankAccounts = collect([(object)[
                'id' => 1, 
                'bank_name' => 'Default Bank',
                'account_name' => 'Company Account',
                'account_number' => '0000',
                'branch_name' => 'Main',
                'name' => 'Default Company Bank Account', 
                'balance' => 0
            ]]);
        }
        
        // Fetch actual cash boxes/accounts from DB
        $cashBoxes = \App\Models\PettyCashBox::where('status', 'Active')->get()->map(function($box) {
            return (object)[
                'id' => $box->id,
                'name' => $box->box_name,
                'balance' => $box->current_balance ?? 0
            ];
        });
        if ($cashBoxes->isEmpty()) {
            $cashBoxes = \App\Models\Account::where('name', 'like', '%Cash%')->orWhere('code', 'like', 'CA%')->get()->map(function($acc) {
                return (object)[
                    'id' => $acc->id,
                    'name' => $acc->name,
                    'balance' => 0
                ];
            });
        }
        if ($cashBoxes->isEmpty()) {
            $cashBoxes = collect([(object)['id' => 8, 'name' => 'Petty Cash Box', 'balance' => 0]]);
        }

        $pettyCashBalance = $cashBoxes->first()?->balance ?? 0;
        
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
            'reference_no' => 'required',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $amount = (float) $request->input('amount');
        
        // Safely map Bank Account (CompanyBankAccount -> Chart of Account)
        $companyBank = \App\Models\CompanyBankAccount::find($request->input('bank_account_id'));
        if ($companyBank) {
            $availBal = (float)($companyBank->current_balance ?? $companyBank->opening_balance ?? 0);
            if ($amount > $availBal) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Insufficient Bank Funds! Withdrawal amount (Rs. ' . number_format($amount, 2) . ') exceeds available balance in ' . $companyBank->bank_name . ' (Rs. ' . number_format($availBal, 2) . ').');
            }
            // Deduct the withdrawn amount from the actual Company Bank Account balance
            $companyBank->current_balance = $availBal - $amount;
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
                ['name' => 'Petty Cash Box'],
                ['type' => 'Asset', 'code' => 'CA-' . rand(1000, 9999), 'system_id' => $request->input('project_id')]
            );
        }

        // Create the Voucher
        $voucher = Voucher::create([
            'system_id' => $request->input('project_id'),
            'company_bank_account_id' => $request->input('bank_account_id'),
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

        // Create Double Entry Accounting Postings in journal_vouchers & journal_entries
        try {
            $allBankNames = CompanyBankAccount::pluck('bank_name')->filter()->unique()->implode(' / ');
            $bankAccountName = 'Bank Balances (' . ($allBankNames ?: 'Karnataka Bank / HDFC Escrow') . ')';
            $requiredAccounts = [
                '1002' => ['name' => 'Petty Cash Box', 'type' => 'ASSET'],
                '1001' => ['name' => 'Bank Balances', 'type' => 'ASSET']
            ];
            foreach ($requiredAccounts as $accCode => $accInfo) {
                ChartOfAccount::firstOrCreate(
                    ['account_code' => $accCode],
                    [
                        'account_name' => $accInfo['name'],
                        'account_type' => $accInfo['type'],
                        'is_active'    => true,
                    ]
                );
            }

            $voucherType = VoucherType::firstOrCreate(
                ['code' => 'PETTY_CASH_CONTRA'],
                [
                    'id'          => 9,
                    'name'        => 'Petty Cash Contra Transfer',
                    'prefix'      => 'JV-PC',
                    'description' => 'Generated on bank cash withdrawal to petty cash box',
                    'is_active'   => true,
                ]
            );

            $vDate = $request->input('date') ?? date('Y-m-d');
            $vNarration = 'Cash withdrawal for petty cash box';

            $journalVoucher = JournalVoucher::create([
                'voucher_no'      => 'JV-PC-' . date('Y') . '-' . str_pad((string)rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'voucher_type_id' => $voucherType->id,
                'voucher_date'    => $vDate,
                'reference_id'    => null,
                'narration'       => $vNarration,
                'is_active'       => true,
            ]);

            // 1. Petty Cash Box (Debit 1002: Cash Asset Increases)
            JournalEntry::create([
                'voucher_id'     => $journalVoucher->id,
                'account_id'     => '1002',
                'debit_amount'   => $amount,
                'credit_amount'  => 0.00,
                'line_narration' => 'Petty Cash Box (Cash Asset Increases)',
                'entity_type'    => null,
                'entity_id'      => null,
            ]);

            // 2. Bank Account (Credit 1001: Bank Asset Decreases)
            JournalEntry::create([
                'voucher_id'     => $journalVoucher->id,
                'account_id'     => '1001',
                'debit_amount'   => 0.00,
                'credit_amount'  => $amount,
                'line_narration' => ($companyBank ? $companyBank->bank_name : 'Karnataka Bank') . ' (Bank Asset Decreases)',
                'entity_type'    => 'BANK',
                'entity_id'      => $companyBank ? $companyBank->id : null,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Journal Voucher Creation Error on Contra Withdrawal: ' . $e->getMessage());
        }

        return redirect()->route('petty-cash.balance-register')->with('success', 'Contra withdrawal entry posted successfully.');
    }
    public function dailySiteExpenses(Request $request)
    {
        $this->ensureHistoricalColumn();
        $this->backfillMissingExpenseVouchers();
        $projects = Project::where('is_active', true)->get();
        
        $selectedProject = $request->input('project_id', $projects->first()->id ?? null);
        $financialYear = $request->input('financial_year', 'All');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if ($financialYear && $financialYear !== 'All' && $financialYear !== 'Custom' && preg_match('/^(\d{4})-(\d{2,4})$/', (string) $financialYear, $matches)) {
            $fyStartYear = (int) $matches[1];
            $fyEndYear = $fyStartYear + 1;
            $fromDate = $fromDate ?: "{$fyStartYear}-04-01";
            $toDate = $toDate ?: "{$fyEndYear}-03-31";
        }

        $category = $request->input('category', 'All');
        $paymentMode = $request->input('payment_mode', 'All');
        $search = $request->input('search');

        $project = ($selectedProject && $selectedProject !== 'All') ? Project::find($selectedProject) : $projects->first();
        $siteName = $project ? $project->name : 'Green City Site';
        
        $pettyCashBox = PettyCashBox::firstOrCreate(
            ['project_id' => $project ? $project->id : $selectedProject],
            [
                'box_code' => 'PC-' . strtoupper($project ? $project->code : 'GEN') . '-001',
                'incharge_id' => auth()->id(),
                'current_balance' => 0
            ]
        );

        $query = PettyCashTransaction::with(['category', 'pettyCashBox.project', 'creator'])
            ->whereIn('transaction_type', ['Site Expense', 'Expense', 'Payment']);

        if ($selectedProject && $selectedProject !== 'All') {
            $query->where('petty_cash_box_id', $pettyCashBox->id);
        }
            
        if ($fromDate) {
            $query->whereDate('transaction_date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('transaction_date', '<=', $toDate);
        }
        
        if ($category && $category !== 'All') {
            if (is_numeric($category)) {
                $query->where('category_id', $category);
            } else {
                $query->where(function($q) use ($category) {
                    $q->where('category_id', $category)
                      ->orWhereHas('category', function($cq) use ($category) {
                          $cq->where('category', $category);
                      })
                      ->orWhere('narration', 'like', $category . '%');
                });
            }
        }
        
        if ($paymentMode && $paymentMode !== 'All') {
            $query->where('payment_mode', $paymentMode);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('voucher_number', 'like', "%{$search}%")
                  ->orWhere('narration', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%");
            });
        }

        $totalAmount = (float) (clone $query)->sum('cash_out');
        $filteredCount = (clone $query)->count();

        // Dynamic KPI Cards (Petty Cash Balance & Monthly Outflow) responsive to Financial Year / Search / Filters
        $now = now();
        $isDateFiltered = !empty($fromDate) || !empty($toDate);
        $isCurrentPeriodSelected = !$isDateFiltered || (
            (!$fromDate || $now->toDateString() >= $fromDate) &&
            (!$toDate || $now->toDateString() <= $toDate)
        );

        if (!$isDateFiltered && (!$search && ($category === 'All' || !$category) && ($paymentMode === 'All' || !$paymentMode))) {
            $availableBalance = (float) ($pettyCashBox->current_balance ?? 0);
        } elseif ($filteredCount === 0) {
            $availableBalance = 0.0;
        } elseif ($isCurrentPeriodSelected) {
            $availableBalance = (float) ($pettyCashBox->current_balance ?? 0);
        } else {
            // Historical / past FY balance calculation from Cash Book / Petty Cash Box
            $cashBoxTxQuery = PettyCashTransaction::where('petty_cash_box_id', $pettyCashBox->id);
            if ($fromDate) {
                $cashBoxTxQuery->whereDate('transaction_date', '>=', $fromDate);
            }
            if ($toDate) {
                $cashBoxTxQuery->whereDate('transaction_date', '<=', $toDate);
            }
            $periodCashIn = (float) (clone $cashBoxTxQuery)->sum('cash_in');
            $periodCashOut = (float) (clone $cashBoxTxQuery)->sum('cash_out');

            if ($periodCashIn > 0) {
                $availableBalance = max(0.0, $periodCashIn - $periodCashOut);
            } else {
                // Check Cash-in-Hand / Petty Cash Box ledger entries in this period if seeded via Voucher
                $cashAccountIds = \App\Models\Account::withoutGlobalScopes()
                    ->whereIn('name', ['Cash-in-Hand', 'Petty Cash Box', 'Cash'])
                    ->orWhere('code', 'CASH-HAND')
                    ->pluck('id');
                if ($cashAccountIds->isNotEmpty()) {
                    $vLineQuery = \App\Models\VoucherLine::whereIn('account_id', $cashAccountIds)
                        ->whereHas('voucher', function ($vq) use ($fromDate, $toDate) {
                            if ($fromDate) {
                                $vq->whereDate('date', '>=', $fromDate);
                            }
                            if ($toDate) {
                                $vq->whereDate('date', '<=', $toDate);
                            }
                        });
                    $vDebit = (float) (clone $vLineQuery)->sum('debit');
                    $vCredit = (float) (clone $vLineQuery)->sum('credit');
                    $availableBalance = max(0.0, $vDebit - $vCredit);
                } else {
                    $availableBalance = 0.0;
                }
            }
        }

        // Dynamic Monthly Outflow based on active FY / filters
        if ($filteredCount === 0) {
            $thisMonthTotal = 0.0;
        } elseif ($isCurrentPeriodSelected) {
            $startOfMonth = $now->copy()->startOfMonth()->toDateString();
            $endOfMonth = $now->copy()->endOfMonth()->toDateString();
            $thisMonthTotal = (float) (clone $query)
                ->whereDate('transaction_date', '>=', $startOfMonth)
                ->whereDate('transaction_date', '<=', $endOfMonth)
                ->sum('cash_out');
        } else {
            // For a past/selected FY or custom range, use the latest active month within the filtered results
            $latestTxDate = (clone $query)->max('transaction_date');
            if ($latestTxDate) {
                $latestMonthStart = Carbon::parse($latestTxDate)->startOfMonth()->toDateString();
                $latestMonthEnd = Carbon::parse($latestTxDate)->endOfMonth()->toDateString();
                $thisMonthTotal = (float) (clone $query)
                    ->whereDate('transaction_date', '>=', $latestMonthStart)
                    ->whereDate('transaction_date', '<=', $latestMonthEnd)
                    ->sum('cash_out');
            } else {
                $thisMonthTotal = 0.0;
            }
        }

        $categorySummary = (clone $query)
            ->get()
            ->groupBy(function($item) {
                if ($item->category) {
                    return $item->category->category;
                }
                $parts = explode('-', $item->narration);
                return trim($parts[0]) ?: 'Others';
            })
            ->map(function($group) {
                return $group->sum('cash_out');
            });

        $paymentModes = \App\Models\PaymentMode::active()->get();
        $masterCategories = Category::where('status', 'active')
            ->where(function($q) use ($selectedProject) {
                $q->whereNull('project_id')
                  ->orWhere('project_id', $selectedProject);
            })
            ->orderBy('category', 'asc')
            ->get();

        if ($masterCategories->isEmpty()) {
            $masterCategories = Category::where('status', 'active')->orderBy('category', 'asc')->get();
        }

        $formatExpense = function($expense) use ($category, $selectedProject, $siteName) {
            $catName = $expense->category?->category ?? (($category && $category !== 'All' && !is_numeric($category)) ? $category : (explode('-', $expense->narration)[0] ?? 'General'));
            $parts = explode('-', $expense->narration);
            $particularsText = count($parts) > 1 ? trim(implode('-', array_slice($parts, 1))) : $expense->narration;

            return [
                'id' => $expense->id,
                'voucher_number' => $expense->voucher_number,
                'transaction_date' => \Carbon\Carbon::parse($expense->transaction_date)->format('Y-m-d'),
                'formatted_date' => \Carbon\Carbon::parse($expense->transaction_date)->format('d-M-Y'),
                'project_id' => $expense->pettyCashBox?->project_id ?? $selectedProject,
                'project_name' => $expense->pettyCashBox?->project?->name ?? $siteName,
                'category_id' => $expense->category_id,
                'category' => trim($catName),
                'particulars' => trim($particularsText),
                'narration' => $expense->narration,
                'payment_mode' => $expense->payment_mode ?? 'Cash',
                'bill_no' => $expense->reference_no ?? '',
                'bill_date' => $expense->bill_date ? \Carbon\Carbon::parse($expense->bill_date)->format('Y-m-d') : '',
                'formatted_bill_date' => $expense->bill_date ? \Carbon\Carbon::parse($expense->bill_date)->format('d-M-Y') : '',
                'amount' => (float)$expense->cash_out,
                'formatted_amount' => '₹ ' . number_format($expense->cash_out, 2),
                'attachment_url' => $expense->attachment_path ? asset($expense->attachment_path) : '',
                'attachment_name' => $expense->attachment_path ? basename($expense->attachment_path) : '',
                'created_by' => $expense->creator?->name ?? 'System Admin',
                'is_historical' => (bool)($expense->is_historical ?? false),
            ];
        };

        if ($request->wantsJson() || $request->ajax()) {
            if ($request->boolean('all') || $request->input('per_page') === 'all' || $request->has('export')) {
                $allExpenses = $query->orderBy('transaction_date', 'desc')->get();
                $formattedExpenses = $allExpenses->map($formatExpense);

                return response()->json([
                    'expenses' => $formattedExpenses,
                    'totalAmount' => number_format($totalAmount, 2),
                    'totalAmountRaw' => $totalAmount,
                    'availableBalance' => $availableBalance,
                    'thisMonthTotal' => $thisMonthTotal,
                    'totalCount' => $allExpenses->count(),
                ]);
            }

            $expenses = $query->orderBy('transaction_date', 'desc')->paginate(10);
            $formattedExpenses = collect($expenses->items())->map($formatExpense);

            return response()->json([
                'expenses' => $formattedExpenses,
                'totalAmount' => number_format($totalAmount, 2),
                'totalAmountRaw' => $totalAmount,
                'availableBalance' => $availableBalance,
                'thisMonthTotal' => $thisMonthTotal,
                'pagination' => [
                    'current_page' => $expenses->currentPage(),
                    'last_page' => $expenses->lastPage(),
                    'from' => $expenses->firstItem() ?? 0,
                    'to' => $expenses->lastItem() ?? 0,
                    'total' => $expenses->total(),
                ]
            ]);
        }

        $expenses = $query->orderBy('transaction_date', 'desc')->paginate(10);

        return view('petty-cash.daily-site-expenses', compact(
            'expenses', 
            'projects', 
            'selectedProject', 
            'financialYear',
            'fromDate', 
            'toDate', 
            'category', 
            'paymentMode', 
            'siteName', 
            'totalAmount', 
            'paymentModes', 
            'masterCategories',
            'availableBalance', 
            'thisMonthTotal', 
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
        $this->ensureHistoricalColumn();
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'transaction_date' => 'required|date',
            'voucher_number' => 'required|string',
            'category_id' => 'nullable',
            'category' => 'nullable',
            'payment_mode' => 'required|string',
            'bill_no' => 'required|string',
            'bill_date' => 'nullable|date',
            'amount' => 'required|numeric|min:0.01',
            'particulars' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'is_historical' => 'nullable|boolean',
        ]);

        $catInput = $request->input('category_id') ?: $request->input('category');
        $categoryId = null;
        $categoryName = 'General';
        if ($catInput) {
            if (is_numeric($catInput)) {
                $catObj = Category::find($catInput);
                if ($catObj) {
                    $categoryId = $catObj->id;
                    $categoryName = $catObj->category;
                }
            } else {
                $catObj = Category::where('category', $catInput)->first();
                if ($catObj) {
                    $categoryId = $catObj->id;
                    $categoryName = $catObj->category;
                } else {
                    $categoryName = $catInput;
                }
            }
        }

        $project = Project::findOrFail($request->project_id);
        $pettyCashBox = PettyCashBox::firstOrCreate(
            ['project_id' => $project->id],
            [
                'box_code' => 'PC-' . strtoupper($project->code) . '-001',
                'incharge_id' => auth()->id(),
                'current_balance' => 0
            ]
        );

        $availBal = (float)($pettyCashBox->current_balance ?? 0);
        $expenseAmount = (float)$request->amount;
        $isHistorical = $request->boolean('is_historical');

        if (!$isHistorical) {
            if ($availBal <= 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'No Petty Cash Available! The current petty cash balance is ₹ 0.00. Please perform a bank cash withdrawal (Contra) first, or check "Is Historical".')
                    ->with('show_expense_modal', true);
            }

            if ($expenseAmount > $availBal) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Insufficient Petty Cash Balance! Entered amount (₹ ' . number_format($expenseAmount, 2) . ') exceeds available balance (₹ ' . number_format($availBal, 2) . ').')
                    ->with('show_expense_modal', true);
            }
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('petty-cash-attachments'), $filename);
            $attachmentPath = 'petty-cash-attachments/' . $filename;
        }

        $balanceBefore = (float)$pettyCashBox->current_balance;
        $balanceAfter = $isHistorical ? $balanceBefore : ($balanceBefore - $expenseAmount);

        $transaction = PettyCashTransaction::create([
            'petty_cash_box_id' => $pettyCashBox->id,
            'category_id' => $categoryId,
            'transaction_date' => $request->transaction_date,
            'voucher_number' => $request->voucher_number,
            'transaction_type' => 'Site Expense',
            'reference_no' => $request->bill_no,
            'bill_date' => $request->bill_date,
            'narration' => $categoryName . ' - ' . $request->particulars,
            'payment_mode' => $request->payment_mode,
            'cash_in' => 0,
            'cash_out' => $request->amount,
            'balance' => $balanceAfter,
            'created_by' => auth()->id(),
            'status' => 'approved',
            'is_historical' => $isHistorical,
        ]);

        if ($attachmentPath) {
            $transaction->attachment_path = $attachmentPath;
            $transaction->save();
        }

        $this->syncExpenseVoucher($transaction, $categoryName, (int) $project->id);

        if (!$isHistorical) {
            $pettyCashBox->update(['current_balance' => $balanceAfter]);
        }

        if ($request->input('submit_action') === 'save_new') {
            return redirect()->back()->with('success', 'Site expense recorded successfully.')->with('show_expense_modal', true);
        }

        return redirect()->back()->with('success', 'Site expense recorded successfully.');
    }

    public function updateExpense(Request $request, $id)
    {
        $this->ensureHistoricalColumn();
        $request->validate([
            'category_id' => 'nullable',
            'category' => 'nullable',
            'payment_mode' => 'required|string',
            'bill_no' => 'required|string',
            'bill_date' => 'nullable|date',
            'amount' => 'required|numeric|min:0.01',
            'particulars' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'is_historical' => 'nullable|boolean',
        ]);

        $transaction = PettyCashTransaction::findOrFail($id);
        $pettyCashBox = PettyCashBox::findOrFail($transaction->petty_cash_box_id);

        $catInput = $request->input('category_id') ?: $request->input('category');
        $categoryId = $transaction->category_id;
        $categoryName = $transaction->category?->category ?? 'General';
        if ($catInput) {
            if (is_numeric($catInput)) {
                $catObj = Category::find($catInput);
                if ($catObj) {
                    $categoryId = $catObj->id;
                    $categoryName = $catObj->category;
                }
            } else {
                $catObj = Category::where('category', $catInput)->first();
                if ($catObj) {
                    $categoryId = $catObj->id;
                    $categoryName = $catObj->category;
                } else {
                    $categoryName = $catInput;
                }
            }
        }

        $oldAmount = (float)$transaction->cash_out;
        $newAmount = (float)$request->amount;
        $currentBalance = (float)$pettyCashBox->current_balance;
        $wasHistorical = (bool)($transaction->is_historical ?? false);
        $isHistorical = $request->boolean('is_historical');

        // Effective available balance before this expense deduction
        $effectiveBalance = $wasHistorical ? $currentBalance : ($currentBalance + $oldAmount);

        if (!$isHistorical) {
            if ($effectiveBalance <= 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'No Petty Cash Available! Available capacity is ₹ 0.00. Please perform a bank cash withdrawal (Contra) first, or check "Is Historical".');
            }
            if ($newAmount > $effectiveBalance) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Insufficient Petty Cash Balance! New amount (₹ ' . number_format($newAmount, 2) . ') exceeds available capacity (₹ ' . number_format($effectiveBalance, 2) . ').');
            }
            $balanceAfter = $effectiveBalance - $newAmount;
        } else {
            $balanceAfter = $effectiveBalance;
        }

        $attachmentPath = $transaction->attachment_path;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('petty-cash-attachments'), $filename);
            $attachmentPath = 'petty-cash-attachments/' . $filename;
        }

        $transaction->update([
            'category_id' => $categoryId,
            'reference_no' => $request->bill_no,
            'bill_date' => $request->bill_date,
            'narration' => $categoryName . ' - ' . $request->particulars,
            'payment_mode' => $request->payment_mode,
            'cash_out' => $newAmount,
            'balance' => $balanceAfter,
            'attachment_path' => $attachmentPath,
            'is_historical' => $isHistorical,
        ]);

        $this->syncExpenseVoucher($transaction, $categoryName, (int) $pettyCashBox->project_id);

        $pettyCashBox->update(['current_balance' => $balanceAfter]);

        return redirect()->back()->with('success', 'Site expense updated successfully.');
    }
}
