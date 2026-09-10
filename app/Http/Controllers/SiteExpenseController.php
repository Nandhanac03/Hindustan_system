<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SiteExpense;
use App\Models\Project;
use App\Models\Floor;
use App\Models\Payee;
use App\Models\ChartOfAccount;
use App\Models\CompanyBankAccount;
use App\Models\Loan;
use App\Models\Document;
use App\Models\Voucher;
use App\Models\VoucherLine;
use App\Models\Account;
use App\Models\Sale;
use App\Models\Unit;
use App\Models\SiteExpensePayment;
use App\Models\PaymentMode;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SiteExpenseController extends Controller
{
    /**
     * Default 4000-series Expense Categories as per specification
     */
    protected array $defaultCategories = [
        '4001' => 'Land Acquisition & Legal Cost',
        '4010' => 'Site Office & Administrative',
        '4020' => 'Machinery & Heavy Equipment Rental',
        '4030' => 'Generator Diesel & Power Expenses',
        '4040' => 'Municipal, Plan Sanction & RERA Fees',
    ];

    /**
     * Get Expense Categories from ChartOfAccount table master
     */
    protected function getExpenseCategories(): array
    {
        $categories = [];

        try {
            if (Schema::hasTable('chart_of_accounts')) {
                $dbAccounts = ChartOfAccount::where('is_active', true)
                    ->orderBy('account_code')
                    ->get();

                foreach ($dbAccounts as $acc) {
                    $categories[$acc->account_code] = $acc->account_name;
                }
            }
        } catch (\Exception $e) {
            // Fallback to defaults if table query fails
        }

        if (empty($categories)) {
            $categories = $this->defaultCategories;
        }

        return $categories;
    }

    /**
     * Generate Next Sequential Voucher Number (EXP-2026-XXXX)
     */
    protected function generateVoucherNumber(): string
    {
        $year = Carbon::now()->format('Y');
        $prefix = "EXP-{$year}-";

        $latest = SiteExpense::where('voucher_number', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->first();

        if ($latest) {
            $lastNum = (int) substr($latest->voucher_number, strlen($prefix));
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return $prefix . str_pad((string)$nextNum, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Display listing and dashboard of site expenses
     */
    public function index(Request $request): View
    {
        $query = SiteExpense::with(['project', 'floor', 'payee', 'companyBankAccount', 'loan', 'creator'])
            ->orderByDesc('voucher_date')
            ->orderByDesc('id');

        // Status Tab Filter
        $statusTab = $request->query('status', 'all');
        if ($statusTab === 'draft') {
            $query->where('status', 'Draft');
        } elseif ($statusTab === 'pending') {
            $query->where('status', 'Pending');
        } elseif ($statusTab === 'approved') {
            $query->where('status', 'Approved');
        } elseif ($statusTab === 'posted') {
            $query->where('status', 'Approved');
        } elseif ($statusTab === 'rejected') {
            $query->where('status', 'Rejected');
        }

        // Filters
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('category_code')) {
            $query->where('expense_category_code', $request->category_code);
        }

        if ($request->filled('payee_type')) {
            $query->where('payee_type', $request->payee_type);
        }

        if ($request->filled('payment_source_type')) {
            $query->where('payment_source_type', $request->payment_source_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('voucher_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('voucher_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('voucher_number', 'like', "%{$search}%")
                  ->orWhere('transaction_reference_no', 'like', "%{$search}%")
                  ->orWhere('casual_payee_name', 'like', "%{$search}%")
                  ->orWhereHas('payee', fn($pq) => $pq->where('name', 'like', "%{$search}%"));
            });
        }

        $siteExpenses = $query->paginate(15)->withQueryString();

        // Calculate Summary Dashboard Metrics
        $allQuery = SiteExpense::query();
        if ($request->filled('project_id')) {
            $allQuery->where('project_id', $request->project_id);
        }

        $tabCounts = [
            'all'      => (clone $allQuery)->count(),
            'draft'    => (clone $allQuery)->where('status', 'Draft')->count(),
            'pending'  => (clone $allQuery)->where('status', 'Pending')->count(),
            'approved' => (clone $allQuery)->where('status', 'Approved')->count(),
            'rejected' => (clone $allQuery)->where('status', 'Rejected')->count(),
            'posted'   => (clone $allQuery)->where('status', 'Approved')->count(),
        ];

        $totalCount            = (clone $allQuery)->count();
        $totalAmount           = (float) (clone $allQuery)->sum('net_amount');

        $approvedAmount        = (float) (clone $allQuery)->where('status', 'Approved')->sum('net_amount');

        $pendingAmount         = (float) (clone $allQuery)->where('status', 'Pending')->sum('net_amount');

        $thisMonthExpenses     = (float) (clone $allQuery)->whereMonth('voucher_date', now()->month)->whereYear('voucher_date', now()->year)->sum('net_amount');

        
        $budgetTotal = 7800000;
        $budgetUtilizationPct = $totalAmount > 0 ? round(($totalAmount / $budgetTotal) * 100, 1) : 0;

        $unpostedAmount        = (float) (clone $allQuery)->where('status', 'Rejected')->sum('net_amount');
        $unpostedPct           = $totalAmount > 0 ? round(($unpostedAmount / $totalAmount) * 100, 1) : 0;

        $registeredPayeeAmount = (float) (clone $allQuery)->where('payee_type', 'registered')->sum('net_amount');
        $oneTimePayeeAmount    = (float) (clone $allQuery)->where('payee_type', 'one_time')->sum('net_amount');
        $bankSourceAmount      = (float) (clone $allQuery)->where('payment_source_type', 'bank')->sum('net_amount');
        $loanSourceAmount      = (float) (clone $allQuery)->where('payment_source_type', 'loan')->sum('net_amount');

        // Form dependencies
        $projects          = Project::where('is_active', true)->orderBy('name')->get();
        $floors            = Floor::with('project')->orderBy('floor_number')->get();
        $payees            = Payee::orderBy('name')->get();
        $bankAccounts      = CompanyBankAccount::orderBy('bank_name')->get();
        $loans             = Loan::orderBy('lender_name')->get();
        $expenseCategories = $this->getExpenseCategories();
        $autoVoucherNumber = $this->generateVoucherNumber();

        return view('expenses.site-expenses.index', compact(
            'siteExpenses',
            'projects',
            'floors',
            'payees',
            'bankAccounts',
            'loans',
            'expenseCategories',
            'autoVoucherNumber',
            'totalCount',
            'totalAmount',
            'approvedAmount',
            'pendingAmount',
            'thisMonthExpenses',
            'budgetTotal',
            'budgetUtilizationPct',
            'unpostedAmount',
            'unpostedPct',
            'registeredPayeeAmount',
            'oneTimePayeeAmount',
            'bankSourceAmount',
            'loanSourceAmount',
            'statusTab',
            'tabCounts'
        ));
    }

    /**
     * Show Interactive Workflow View (Redirects to main Site Expenses Register)
     */
    public function workflow(Request $request): RedirectResponse
    {
        return redirect()->route('site-expenses.index');
    }

    /**
     * Show form for creating a new site expense (Redirects to Modal Popup on Index)
     */
    public function create(Request $request): RedirectResponse
    {
        return redirect()->route('site-expenses.index', ['create' => 1]);
    }

    /**
     * Store newly created site expense in database
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id'               => 'required|exists:projects,id',
            'tower_block_tag'          => 'nullable|string|max:100',
            'floor_id'                 => 'nullable|exists:floors,id',
            'voucher_date'             => 'required|date',
            'payee_type'               => 'required|in:registered,one_time',
            'payee_id'                 => 'required_if:payee_type,registered|nullable|exists:payees,id',
            'casual_payee_name'        => 'required_if:payee_type,one_time|nullable|string|max:255',
            'expense_category_code'    => 'required|string',
            'gross_amount'             => 'required|numeric|min:0.01',
            'gst_rate'                 => 'nullable|numeric|min:0|max:100',
            'cgst_amount'              => 'nullable|numeric|min:0',
            'sgst_amount'              => 'nullable|numeric|min:0',
            'igst_amount'              => 'nullable|numeric|min:0',
            'net_amount'               => 'required|numeric|min:0.01',
            'payment_source_type'      => 'required|in:bank,loan',
            'company_bank_account_id'  => 'required_if:payment_source_type,bank|nullable|exists:company_bank_accounts,id',
            'loan_id'                  => 'required_if:payment_source_type,loan|nullable|exists:loans,id',
            'transaction_reference_no' => 'required|string|max:100',
            'narration'                => 'nullable|string|max:1000',
            'attachment'               => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'submit_action'            => 'nullable|string|in:draft,submit',
        ]);

        $categories = $this->getExpenseCategories();
        $categoryCode = $validated['expense_category_code'];
        $categoryName = $categories[$categoryCode] ?? 'General Site Expense';

        $gross = (float) $validated['gross_amount'];
        $net   = (float) $validated['net_amount'];
        $gstRate = isset($validated['gst_rate']) ? (float)$validated['gst_rate'] : (float)$request->input('gst_rate', 0);
        if ($gstRate <= 0 && $gross > 0 && $net > $gross) {
            $gstRate = round((($net - $gross) / $gross) * 100, 2);
        }

        // Calculate tax totals
        $cgst = (float) ($validated['cgst_amount'] ?? 0);
        $sgst = (float) ($validated['sgst_amount'] ?? 0);
        $igst = (float) ($validated['igst_amount'] ?? 0);
        $totalGst = $cgst + $sgst + $igst;
        if ($totalGst <= 0 && $net > $gross) {
            $totalGst = round($net - $gross, 2);
            $cgst = round($totalGst / 2, 2);
            $sgst = round($totalGst / 2, 2);
        }

        // STEP 1: Site staff entry creates a Pending voucher (or Draft if saved as draft)
        $status = ($request->submit_action === 'draft') ? 'Draft' : 'Pending';

        // Handle File Attachment
        $attachmentPath = null;
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $attachmentPath = $file->storeAs('site_expenses', $fileName, 'public');
        }

        DB::beginTransaction();
        try {
            $voucherNumber = $this->generateVoucherNumber();

            // Find linked chart of account if available
            $chartOfAccount = ChartOfAccount::where('account_code', $categoryCode)->first();

            $siteExpense = SiteExpense::create([
                'system_id'                => Auth::user()->system_id ?? 1,
                'voucher_number'           => $voucherNumber,
                'project_id'               => $validated['project_id'],
                'floor_id'                 => $validated['floor_id'] ?? null,
                'tower_block_tag'          => $validated['tower_block_tag'] ?? null,
                'voucher_date'             => $validated['voucher_date'],
                'payee_type'               => $validated['payee_type'],
                'payee_id'                 => $validated['payee_type'] === 'registered' ? $validated['payee_id'] : null,
                'casual_payee_name'        => $validated['payee_type'] === 'one_time' ? $validated['casual_payee_name'] : null,
                'chart_of_account_id'      => $chartOfAccount?->id,
                'expense_category_code'    => $categoryCode,
                'expense_category_name'    => $categoryName,
                'gross_amount'             => $gross,
                'gst_rate'                 => $gstRate,
                'cgst_amount'              => $cgst,
                'sgst_amount'              => $sgst,
                'igst_amount'              => $igst,
                'total_gst_amount'         => $totalGst,
                'net_amount'               => $net,
                'payment_source_type'      => $validated['payment_source_type'],
                'company_bank_account_id'  => $validated['payment_source_type'] === 'bank' ? $validated['company_bank_account_id'] : null,
                'loan_id'                  => $validated['payment_source_type'] === 'loan' ? $validated['loan_id'] : null,
                'transaction_reference_no' => $validated['transaction_reference_no'],
                'narration'                => $validated['narration'] ?? null,
                'attachment_path'          => $attachmentPath,
                'created_by'               => Auth::id(),
                'status'                   => $status,
                'paid_amount'              => 0.00,
                'balance_amount'           => $net,
                'payment_status'           => 'unpaid',
            ]);

            // 3. DMS Document Store Integration
            if ($attachmentPath) {
                try {
                    Document::create([
                        'system_id'            => Auth::user()->system_id ?? 1,
                        'documentable_type'    => SiteExpense::class,
                        'documentable_id'      => $siteExpense->id,
                        'category'             => 'Vendor & Operations',
                        'document_type'        => 'Site Expense Invoice / Receipt',
                        'title'                => "Receipt {$voucherNumber} - {$categoryName}",
                        'description'          => "Site Expense Recorded for {$siteExpense->payee_display_name}",
                        'file_path'            => $attachmentPath,
                        'file_name'            => basename($attachmentPath),
                        'file_size'            => Storage::disk('public')->exists($attachmentPath) ? Storage::disk('public')->size($attachmentPath) : 0,
                        'mime_type'            => Storage::disk('public')->exists($attachmentPath) ? Storage::disk('public')->mimeType($attachmentPath) : 'application/pdf',
                        'uploaded_by'          => Auth::id(),
                        'reference_project_id' => $validated['project_id'],
                    ]);
                } catch (\Exception $e) {}
            }

            DB::commit();

            $msg = ($status === 'Draft') 
                ? "Draft Expense {$voucherNumber} saved successfully." 
                : "Site Expense Voucher {$voucherNumber} of ₹" . number_format($net, 2) . " submitted for approval!";

            return redirect()->route('site-expenses.index')->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to save Site Expense: ' . $e->getMessage());
        }
    }

    /**
     * Update existing site expense in database
     */
    public function update(Request $request, SiteExpense $siteExpense): RedirectResponse
    {
        $validated = $request->validate([
            'project_id'               => 'required|exists:projects,id',
            'tower_block_tag'          => 'nullable|string|max:100',
            'floor_id'                 => 'nullable|exists:floors,id',
            'voucher_date'             => 'required|date',
            'payee_type'               => 'required|in:registered,one_time',
            'payee_id'                 => 'required_if:payee_type,registered|nullable|exists:payees,id',
            'casual_payee_name'        => 'required_if:payee_type,one_time|nullable|string|max:255',
            'expense_category_code'    => 'required|string',
            'gross_amount'             => 'required|numeric|min:0.01',
            'gst_rate'                 => 'nullable|numeric|min:0|max:100',
            'cgst_amount'              => 'nullable|numeric|min:0',
            'sgst_amount'              => 'nullable|numeric|min:0',
            'igst_amount'              => 'nullable|numeric|min:0',
            'net_amount'               => 'required|numeric|min:0.01',
            'payment_source_type'      => 'required|in:bank,loan',
            'company_bank_account_id'  => 'required_if:payment_source_type,bank|nullable|exists:company_bank_accounts,id',
            'loan_id'                  => 'required_if:payment_source_type,loan|nullable|exists:loans,id',
            'transaction_reference_no' => 'required|string|max:100',
            'narration'                => 'nullable|string|max:1000',
            'attachment'               => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'submit_action'            => 'nullable|string|in:draft,submit',
        ]);

        $categories = $this->getExpenseCategories();
        $categoryCode = $validated['expense_category_code'];
        $categoryName = $categories[$categoryCode] ?? 'General Site Expense';

        $gross = (float) $validated['gross_amount'];
        $net   = (float) $validated['net_amount'];
        $gstRate = isset($validated['gst_rate']) ? (float)$validated['gst_rate'] : (float)$request->input('gst_rate', 0);
        if ($gstRate <= 0 && $gross > 0 && $net > $gross) {
            $gstRate = round((($net - $gross) / $gross) * 100, 2);
        }

        // Calculate tax totals
        $cgst = (float) ($validated['cgst_amount'] ?? 0);
        $sgst = (float) ($validated['sgst_amount'] ?? 0);
        $igst = (float) ($validated['igst_amount'] ?? 0);
        $totalGst = $cgst + $sgst + $igst;
        if ($totalGst <= 0 && $net > $gross) {
            $totalGst = round($net - $gross, 2);
            $cgst = round($totalGst / 2, 2);
            $sgst = round($totalGst / 2, 2);
        }

        $status = ($request->submit_action === 'draft') 
            ? 'Draft' 
            : ($siteExpense->status === 'Approved' ? 'Approved' : 'Pending');

        // Handle File Attachment if newly uploaded
        $attachmentPath = $siteExpense->attachment_path;
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $attachmentPath = $file->storeAs('site_expenses', $fileName, 'public');
        }

        DB::beginTransaction();
        try {
            $chartOfAccount = ChartOfAccount::where('account_code', $categoryCode)->first();

            $siteExpense->update([
                'project_id'               => $validated['project_id'],
                'floor_id'                 => $validated['floor_id'] ?? null,
                'tower_block_tag'          => $validated['tower_block_tag'] ?? null,
                'voucher_date'             => $validated['voucher_date'],
                'payee_type'               => $validated['payee_type'],
                'payee_id'                 => $validated['payee_type'] === 'registered' ? $validated['payee_id'] : null,
                'casual_payee_name'        => $validated['payee_type'] === 'one_time' ? $validated['casual_payee_name'] : null,
                'chart_of_account_id'      => $chartOfAccount?->id,
                'expense_category_code'    => $categoryCode,
                'expense_category_name'    => $categoryName,
                'gross_amount'             => $gross,
                'gst_rate'                 => $gstRate,
                'cgst_amount'              => $cgst,
                'sgst_amount'              => $sgst,
                'igst_amount'              => $igst,
                'total_gst_amount'         => $totalGst,
                'net_amount'               => $net,
                'balance_amount'           => max(0, $net - (float)$siteExpense->paid_amount),
                'payment_source_type'      => $validated['payment_source_type'],
                'company_bank_account_id'  => $validated['payment_source_type'] === 'bank' ? $validated['company_bank_account_id'] : null,
                'loan_id'                  => $validated['payment_source_type'] === 'loan' ? $validated['loan_id'] : null,
                'transaction_reference_no' => $validated['transaction_reference_no'],
                'narration'                => $validated['narration'] ?? null,
                'attachment_path'          => $attachmentPath,
                'status'                   => $status,
            ]);

            DB::commit();

            return redirect()->route('site-expenses.index')
                ->with('success', "Site Expense Voucher {$siteExpense->voucher_number} updated successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update Site Expense: ' . $e->getMessage());
        }
    }

    /**
     * Approve Site Expense Voucher (Step 2 of 3)
     * Confirms the liability and makes it ready for Treasury disbursement in Payment Release
     */
    public function approve(SiteExpense $siteExpense): RedirectResponse
    {
        if ($siteExpense->status === 'Approved') {
            return redirect()->back()->with('info', "Voucher {$siteExpense->voucher_number} is already approved.");
        }

        DB::beginTransaction();
        try {
            // STEP 2: Approval confirms liability. Bank deduction happens in Step 3 (Disbursement desk).
            $siteExpense->update([
                'status' => 'Approved',
            ]);

            DB::commit();

            return redirect()->route('site-expenses.index')
                ->with('success', "Voucher {$siteExpense->voucher_number} approved! Liability confirmed and forwarded to Payment Release desk.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to approve voucher: ' . $e->getMessage());
        }
    }

    /**
     * Reject / Send Back Site Expense Voucher
     */
    public function reject(SiteExpense $siteExpense): RedirectResponse
    {
        $siteExpense->update(['status' => 'Rejected']);
        return redirect()->route('site-expenses.index')
            ->with('info', "Voucher {$siteExpense->voucher_number} sent back / rejected.");
    }

    /**
     * Display details of a specific site expense
     */
    public function show(SiteExpense $siteExpense): View
    {
        $siteExpense->load(['project', 'floor', 'payee', 'companyBankAccount', 'loan', 'creator', 'documents']);
        return view('expenses.site-expenses.show', compact('siteExpense'));
    }

    /**
     * Delete site expense record and reverse balances
     */
    public function destroy(SiteExpense $siteExpense): RedirectResponse
    {
        DB::beginTransaction();
        try {
            if ($siteExpense->status === 'Approved') {
                if ($siteExpense->payment_source_type === 'bank' && $siteExpense->company_bank_account_id) {
                    $bankAccount = CompanyBankAccount::find($siteExpense->company_bank_account_id);
                    if ($bankAccount) {
                        $bankAccount->increment('current_balance', (float)$siteExpense->net_amount);
                    }
                } elseif ($siteExpense->payment_source_type === 'loan' && $siteExpense->loan_id) {
                    $loan = Loan::find($siteExpense->loan_id);
                    if ($loan) {
                        $loan->decrement('outstanding_balance', (float)$siteExpense->net_amount);
                    }
                }
            }

            if ($siteExpense->attachment_path && Storage::disk('public')->exists($siteExpense->attachment_path)) {
                Storage::disk('public')->delete($siteExpense->attachment_path);
            }

            $voucherNo = $siteExpense->voucher_number;
            $siteExpense->delete();

            DB::commit();

            return redirect()->route('site-expenses.index')
                ->with('success', "Site Expense Voucher {$voucherNo} has been deleted.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('site-expenses.index')
                ->with('error', 'Failed to delete Site Expense: ' . $e->getMessage());
        }
    }

    /**
     * Treasury Desk: Site Expense Payment Release & Disbursement Register
     */
    public function paymentRelease(Request $request): View
    {
        $systemId = Auth::user()->system_id ?? 1;

        $query = SiteExpense::with([
            'project',
            'floor',
            'payee',
            'companyBankAccount',
            'loan',
            'creator',
            'payments' => function ($q) {
                $q->orderBy('payment_date', 'asc')->with(['companyBankAccount', 'loan']);
            }
        ])
        ->where('status', 'Approved')
        ->orderByDesc('voucher_date')
        ->orderByDesc('id');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('payment_status')) {
            if ($request->payment_status === 'unpaid') {
                $query->where('balance_amount', '>', 0)->where('paid_amount', 0);
            } elseif ($request->payment_status === 'partially_paid') {
                $query->where('balance_amount', '>', 0)->where('paid_amount', '>', 0);
            } elseif ($request->payment_status === 'paid') {
                $query->where('balance_amount', '<=', 0);
            } elseif ($request->payment_status === 'pending_disbursement') {
                $query->where('balance_amount', '>', 0);
            }
        }

        if ($request->filled('search')) {
            $s = '%' . trim($request->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('voucher_number', 'like', $s)
                  ->orWhere('casual_payee_name', 'like', $s)
                  ->orWhere('expense_category_name', 'like', $s)
                  ->orWhere('transaction_reference_no', 'like', $s)
                  ->orWhereHas('payee', fn($pq) => $pq->where('name', 'like', $s));
            });
        }

        $siteExpenses = $query->paginate(20)->withQueryString();

        // Summary KPI Calculations
        $allApproved = SiteExpense::where('status', 'Approved');
        if ($request->filled('project_id')) {
            $allApproved->where('project_id', $request->project_id);
        }

        $totalApproved = (float) (clone $allApproved)->sum('net_amount');
        $totalPaid     = (float) (clone $allApproved)->sum('paid_amount');
        $totalBalance  = (float) (clone $allApproved)->sum('balance_amount');
        $readyCount    = (clone $allApproved)->where('balance_amount', '>', 0)->count();

        $projects = Project::where('is_active', true)->orderBy('name')->get();
        $companyBankAccounts = CompanyBankAccount::where('status', 'active')
            ->orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();
        $loans = Loan::orderBy('lender_name')->get();
        $paymentModes = PaymentMode::where('status', 'active')->orderBy('name')->get();
        if ($paymentModes->isEmpty()) {
            $paymentModes = collect([
                (object) ['code' => 'NEFT', 'name' => 'NEFT / RTGS Transfer'],
                (object) ['code' => 'Cheque', 'name' => 'Cheque'],
                (object) ['code' => 'UPI', 'name' => 'UPI / Net Banking'],
                (object) ['code' => 'Cash', 'name' => 'Cash'],
            ]);
        }

        return view('expenses.site-expenses.payment-release', compact(
            'siteExpenses',
            'projects',
            'companyBankAccounts',
            'loans',
            'paymentModes',
            'totalApproved',
            'totalPaid',
            'totalBalance',
            'readyCount'
        ));
    }

    /**
     * Process Payment Release / Disbursement for a Site Expense
     */
    public function disburse(Request $request, $id): RedirectResponse
    {
        $siteExpense = SiteExpense::findOrFail($id);

        $balance = (float) $siteExpense->balance_amount;
        if ($balance <= 0) {
            return redirect()->back()->with('error', 'This site expense is already fully paid and disbursed.');
        }

        $validated = $request->validate([
            'payment_date'            => 'required|date',
            'paid_amount'             => 'required|numeric|min:0.01|max:' . $balance,
            'payment_source_type'     => 'required|in:bank,loan',
            'company_bank_account_id' => 'required_if:payment_source_type,bank|nullable|exists:company_bank_accounts,id',
            'loan_id'                 => 'required_if:payment_source_type,loan|nullable|exists:loans,id',
            'payment_mode'            => 'required|string|max:50',
            'reference_no'            => 'nullable|string|max:100',
            'remarks'                 => 'nullable|string|max:1000',
        ], [
            'paid_amount.max' => 'The disbursement amount cannot exceed the outstanding balance of ₹' . number_format($balance, 2),
        ]);

        $paidAmount = (float) $validated['paid_amount'];

        DB::beginTransaction();
        try {
            $systemId = Auth::user()->system_id ?? 1;

            // 1. Create Payment Voucher in Double-Entry Engine if table exists
            $voucher = null;
            if (Schema::hasTable('vouchers')) {
                $voucher = Voucher::create([
                    'system_id'      => $systemId,
                    'voucher_number' => 'PV-SITE-' . time(),
                    'type'           => 'Payment',
                    'date'           => $validated['payment_date'],
                    'narration'      => $validated['remarks'] ?? "Site Expense Payment Release for #{$siteExpense->voucher_number} - {$siteExpense->expense_category_name}",
                    'reference_no'   => $validated['reference_no'] ?? null,
                    'created_by'     => Auth::id() ?? 1,
                ]);
            }

            // 2. Record disbursement payment
            SiteExpensePayment::create([
                'system_id'               => $systemId,
                'site_expense_id'         => $siteExpense->id,
                'payment_date'            => $validated['payment_date'],
                'paid_amount'             => $paidAmount,
                'payment_mode'            => $validated['payment_mode'],
                'payment_source_type'     => $validated['payment_source_type'],
                'company_bank_account_id' => $validated['payment_source_type'] === 'bank' ? $validated['company_bank_account_id'] : null,
                'loan_id'                 => $validated['payment_source_type'] === 'loan' ? $validated['loan_id'] : null,
                'reference_no'            => $validated['reference_no'] ?? null,
                'voucher_id'              => $voucher?->id,
                'status'                  => 'paid',
                'remarks'                 => $validated['remarks'] ?? null,
                'created_by'              => Auth::id(),
            ]);

            // 3. Balance deduction from bank / loan
            if ($validated['payment_source_type'] === 'bank' && !empty($validated['company_bank_account_id'])) {
                $bankAccount = CompanyBankAccount::find($validated['company_bank_account_id']);
                if ($bankAccount) {
                    $bankAccount->decrement('current_balance', $paidAmount);
                }
            } elseif ($validated['payment_source_type'] === 'loan' && !empty($validated['loan_id'])) {
                $loan = Loan::find($validated['loan_id']);
                if ($loan) {
                    $loan->increment('outstanding_balance', $paidAmount);
                }
            }

            // 4. Update site expense balance and status
            $siteExpense->recalculateBalances();

            DB::commit();

            $formattedPaid = number_format($paidAmount, 2);
            return redirect()->route('site-expenses.payment-release')
                ->with('success', "Disbursement of ₹{$formattedPaid} released successfully for Site Expense #{$siteExpense->voucher_number}!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Disbursement release failed: ' . $e->getMessage())
                ->withInput();
        }
    }
}
