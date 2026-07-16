<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Receipt;
use App\Models\CustomerInstallment;
use App\Models\Project;
use App\Models\Account;
use App\Models\Loan;
use App\Models\EmiSchedule;
use App\Models\Payee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class EmiCollectionController extends Controller
{
    // ──────────────────────────────────────────────────────────────────
    // A. DASHBOARD
    // ──────────────────────────────────────────────────────────────────

    // ──────────────────────────────────────────────────────────────────
    // A. DASHBOARD
    // ──────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $payments = Receipt::with(['customer', 'sale.unit', 'sale.project'])
            ->latest('receipt_date')
            ->paginate(15);

        $totalReceived  = Receipt::sum('amount');
        $totalSales     = Sale::where('status', 'active')->count();
        $totalOutstanding = Sale::where('status', 'active')->sum('remaining_balance');
        $pendingPaymentsCount = Sale::where('status', 'active')->where('remaining_balance', '>', 0)->count();

        $recentBookings = Sale::with(['customer', 'project', 'unit'])
            ->where('status', 'active')
            ->where('remaining_balance', '>', 0)
            ->latest('sale_date')
            ->take(5)
            ->get();

        return view('emi-collections.index', compact(
            'payments',
            'totalReceived',
            'totalSales',
            'totalOutstanding',
            'pendingPaymentsCount',
            'recentBookings'
        ));
    }

    // ──────────────────────────────────────────────────────────────────
    // B. PAYMENT SCHEDULES (template display + interactive calculator)
    // ──────────────────────────────────────────────────────────────────

    public function schedules(Request $request): View
    {
        // The schedules page is template/calculator driven (managed by the user's Alpine UI)
        // We pass active sales for reference if needed later
        $projects = Project::where('is_active', true)->orderBy('name')->get();

        return view('emi-collections.schedules', compact('projects'));
    }

    /**
     * Auto-generate customer installment schedule for a Sale.
     */
    public function generateSchedule(Request $request): JsonResponse
    {
        $request->validate([
            'sale_id'          => ['required', 'exists:sales,id'],
            'schedule_type'    => ['required', 'in:fixed_emi,milestone'],
            'num_installments' => ['required_if:schedule_type,fixed_emi', 'integer', 'min:1', 'max:360'],
            'start_date'       => ['required', 'date'],
            'down_payment_pct' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'milestones'       => ['required_if:schedule_type,milestone', 'array', 'min:1'],
            'milestones.*.label'    => ['required_if:schedule_type,milestone', 'string'],
            'milestones.*.amount'   => ['required_if:schedule_type,milestone', 'numeric', 'min:0'],
            'milestones.*.due_date' => ['required_if:schedule_type,milestone', 'date'],
        ]);

        $sale = Sale::findOrFail($request->sale_id);

        // Wipe existing schedule
        CustomerInstallment::where('sale_id', $sale->id)->delete();

        $rows = [];

        if ($request->schedule_type === 'fixed_emi') {
            $totalAmount   = (float)$sale->total_amount;
            $downPct       = (float)($request->down_payment_pct ?? 10);
            $downPayment   = round($totalAmount * $downPct / 100, 2);
            $balance       = round($totalAmount - $downPayment, 2);
            $numEmi        = (int)$request->num_installments;
            $emiAmount     = $numEmi > 0 ? round($balance / $numEmi, 2) : 0;
            $startDate     = Carbon::parse($request->start_date);

            if ($downPayment > 0) {
                $rows[] = CustomerInstallment::create([
                    'sale_id'         => $sale->id,
                    'installment_no'  => 0,
                    'label'           => 'Down Payment',
                    'due_date'        => $startDate->toDateString(),
                    'amount'          => $downPayment,
                    'status'          => 'pending',
                    'schedule_type'   => 'fixed_emi',
                ]);
            }

            for ($i = 1; $i <= $numEmi; $i++) {
                $due = $startDate->copy()->addMonths($i);
                $amt = ($i === $numEmi)
                    ? round($balance - ($emiAmount * ($numEmi - 1)), 2)
                    : $emiAmount;

                $rows[] = CustomerInstallment::create([
                    'sale_id'         => $sale->id,
                    'installment_no'  => $i,
                    'label'           => "EMI {$i}",
                    'due_date'        => $due->toDateString(),
                    'amount'          => $amt,
                    'status'          => 'pending',
                    'schedule_type'   => 'fixed_emi',
                ]);
            }
        } else {
            foreach ($request->milestones as $idx => $m) {
                $rows[] = CustomerInstallment::create([
                    'sale_id'         => $sale->id,
                    'installment_no'  => $idx + 1,
                    'label'           => $m['label'],
                    'due_date'        => $m['due_date'],
                    'amount'          => $m['amount'],
                    'status'          => 'pending',
                    'schedule_type'   => 'milestone',
                ]);
            }
        }

        return response()->json(['success' => true, 'count' => count($rows), 'installments' => $rows]);
    }

    /**
     * Update a single installment row.
     */
    public function updateInstallment(Request $request, CustomerInstallment $installment): JsonResponse
    {
        $request->validate([
            'label'    => ['sometimes', 'string', 'max:100'],
            'due_date' => ['sometimes', 'date'],
            'amount'   => ['sometimes', 'numeric', 'min:0'],
            'status'   => ['sometimes', 'in:pending,paid,overdue,partial'],
        ]);

        $installment->update($request->only(['label', 'due_date', 'amount', 'status']));

        return response()->json(['success' => true, 'installment' => $installment]);
    }

    /**
     * Delete all installments for a sale.
     */
    public function deleteSchedule(Sale $sale): JsonResponse
    {
        $count = CustomerInstallment::where('sale_id', $sale->id)->count();
        CustomerInstallment::where('sale_id', $sale->id)->delete();

        return response()->json(['success' => true, 'deleted' => $count]);
    }

    // ──────────────────────────────────────────────────────────────────
    // C. RECEIPT ENTRY — linked to Sales, not Bookings
    // ──────────────────────────────────────────────────────────────────

    public function receipts(Request $request): View
    {
        $sales = Sale::with(['customer', 'project', 'unit', 'receipts'])
            ->where('status', 'active')
            ->where('remaining_balance', '>', 0)
            ->when($request->filled('project_id'), fn($q) => $q->where('project_id', $request->project_id))
            ->latest('sale_date')
            ->get();

        $projects     = Project::where('is_active', true)->orderBy('name')->get();
        $bankAccounts = Account::where('type', 'Asset')->orderBy('name')->get();
        $partners     = Payee::where('type', 'Partner')->orderBy('name')->get();

        $recentReceipts = Receipt::with(['customer', 'sale.project', 'sale.unit', 'partner'])
            ->latest('receipt_date')
            ->take(20)
            ->get();

        return view('emi-collections.receipts', compact('sales', 'projects', 'bankAccounts', 'partners', 'recentReceipts'));
    }

    /**
     * Record a receipt against a Sale (the real sales workflow).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sale_id'       => ['nullable', 'exists:sales,id'],
            'booking_id'    => ['nullable', 'exists:sales,id'], // fallback
            'amount'        => ['required', 'numeric', 'min:0.01'],
            'payment_mode'  => ['required', 'in:Cash,Cheque,Bank Transfer,Online,Credit Card,UPI'],
            'receipt_date'  => ['nullable', 'date'],
            'reference_no'  => ['nullable', 'string', 'max:100'],
            'bank_name'     => ['nullable', 'string', 'max:100'],
            'remarks'       => ['nullable', 'string', 'max:500'],
            'partner_id'    => ['nullable', 'exists:payees,id'],
        ]);

        $saleId = $validated['sale_id'] ?? $validated['booking_id'];
        if (!$saleId) {
            return response()->json(['error' => 'The sale id / booking id field is required.'], 422);
        }

        $sale = Sale::findOrFail($saleId);

        // Intake Validation Guard: sale must have project + unit + customer
        if (!$sale->project_id || !$sale->unit_id || !$sale->customer_id) {
            return response()->json([
                'error' => 'Receipt must be linked to a Sale with valid Project, Unit, and Customer.'
            ], 422);
        }

        if (round((float)$validated['amount'], 2) > round((float)$sale->remaining_balance, 2)) {
            return response()->json([
                'error' => 'Payment (₹' . number_format((float)$validated['amount'], 2) .
                           ') exceeds remaining balance (₹' . number_format((float)$sale->remaining_balance, 2) . ').'
            ], 422);
        }

        $receipt = DB::transaction(function () use ($validated, $sale) {
            $receipt = Receipt::create([
                'sale_id'      => $sale->id,
                'customer_id'  => $sale->customer_id,
                'project_id'   => $sale->project_id,
                'unit_id'      => $sale->unit_id,
                'receipt_date' => $validated['receipt_date'] ?? now()->toDateString(),
                'amount'       => $validated['amount'],
                'payment_mode' => $validated['payment_mode'],
                'reference_no' => $validated['reference_no'] ?? null,
                'bank_name'    => $validated['bank_name'] ?? null,
                'remarks'      => $validated['remarks'] ?? null,
                'created_by'   => auth()->id(),
                'partner_id'   => $validated['partner_id'] ?? null,
            ]);

            Receipt::allocateToPartners($receipt);

            // Recompute remaining balance from all receipts
            $totalPaid = $sale->receipts()->sum('amount');
            $sale->update(['remaining_balance' => max(0, round((float)$sale->total_amount - $totalPaid, 2))]);

            // Auto-allocate receipt amount to mark installments as paid/partial
            $remainingPayment = (float)$validated['amount'];
            $installments = CustomerInstallment::where('sale_id', $sale->id)
                ->where('status', '!=', 'paid')
                ->orderBy('installment_no')
                ->get();

            foreach ($installments as $inst) {
                if ($remainingPayment <= 0) {
                    break;
                }

                $instAmount = (float)$inst->amount;
                if ($remainingPayment >= $instAmount) {
                    $inst->update(['status' => 'paid']);
                    $remainingPayment -= $instAmount;
                } else {
                    $inst->update(['status' => 'partial']);
                    $remainingPayment = 0;
                }
            }

            return $receipt;
        });

        return response()->json([
            'success' => true,
            'message' => 'Receipt recorded successfully.',
            'receipt' => $receipt,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────
    // D. OUTSTANDING — from Sales.remaining_balance
    // ──────────────────────────────────────────────────────────────────

    public function outstanding(Request $request): View
    {
        $sales = Sale::with(['customer', 'project', 'unit', 'receipts'])
            ->where('status', 'active')
            ->where('remaining_balance', '>', 0)
            ->when($request->filled('project_id'), fn($q) => $q->where('project_id', $request->project_id))
            ->get();

        // Compute aging brackets based on sale_date
        $brackets = ['current' => [], '1-30' => [], '31-60' => [], '61+' => []];
        $today = now();

        foreach ($sales as $sale) {
            $outstanding = (float)$sale->remaining_balance;
            $daysAged    = (int)Carbon::parse($sale->sale_date)->diffInDays($today);

            $bracket = match(true) {
                $daysAged <= 30  => 'current',
                $daysAged <= 60  => '1-30',
                $daysAged <= 90  => '31-60',
                default          => '61+',
            };

            $brackets[$bracket][] = [
                'sale'        => $sale,
                'outstanding' => $outstanding,
                'days_aged'   => $daysAged,
                'total_paid'  => $sale->receipts->sum('amount'),
            ];
        }

        $totals = [
            'current' => collect($brackets['current'])->sum('outstanding'),
            '1-30'    => collect($brackets['1-30'])->sum('outstanding'),
            '31-60'   => collect($brackets['31-60'])->sum('outstanding'),
            '61+'     => collect($brackets['61+'])->sum('outstanding'),
        ];

        $projects = Project::where('is_active', true)->orderBy('name')->get();

        return view('emi-collections.outstanding', compact('brackets', 'totals', 'projects'));
    }

    // ──────────────────────────────────────────────────────────────────
    // E. CASH BOOK — from Receipts
    // ──────────────────────────────────────────────────────────────────

    public function cashBook(Request $request): View
    {
        $receipts = Receipt::with(['customer', 'sale.project', 'sale.unit', 'partner'])
            ->when($request->filled('mode'), fn($q) => $q->where('payment_mode', $request->mode))
            ->when($request->filled('from'), fn($q) => $q->whereDate('receipt_date', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->whereDate('receipt_date', '<=', $request->to))
            ->latest('receipt_date')
            ->get(); // Fetch all matching to make ledger calculations and show in Alpine

        $modeSummary = Receipt::select('payment_mode', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_mode')
            ->get();

        $totalCash = Receipt::sum('amount');

        return view('emi-collections.cash-book', compact('receipts', 'modeSummary', 'totalCash'));
    }

    // ──────────────────────────────────────────────────────────────────
    // F. CUSTOMER LEDGER — per Sale
    // ──────────────────────────────────────────────────────────────────

    public function customerLedger(Sale $sale): View
    {
        $sale->load(['customer', 'project', 'unit', 'receipts']);

        $installments = CustomerInstallment::where('sale_id', $sale->id)
            ->orderBy('installment_no')
            ->get();

        // Build chronological ledger
        $ledger = collect();
        $opening = 0; // Sales have no "opening balance" concept — full amount is the debit

        // Installment rows (debits)
        foreach ($installments as $inst) {
            $ledger->push([
                'date'            => $inst->due_date?->format('d M Y') ?? '—',
                'description'     => $inst->label . ' (Due)',
                'debit'           => (float)$inst->amount,
                'credit'          => 0,
                'running_balance' => 0,
                'type'            => 'installment',
                'status'          => $inst->status,
                'sort_date'       => $inst->due_date,
            ]);
        }

        // Receipt rows (credits)
        foreach ($sale->receipts as $receipt) {
            $ledger->push([
                'date'            => Carbon::parse($receipt->receipt_date)->format('d M Y'),
                'description'     => 'Receipt — ' . $receipt->payment_mode
                    . ($receipt->reference_no ? ' (' . $receipt->reference_no . ')' : ''),
                'debit'           => 0,
                'credit'          => (float)$receipt->amount,
                'running_balance' => 0,
                'type'            => 'receipt',
                'sort_date'       => Carbon::parse($receipt->receipt_date),
            ]);
        }

        // If no installments, show a single debit row for the total sale amount
        if ($installments->isEmpty()) {
            $ledger->prepend([
                'date'            => Carbon::parse($sale->sale_date)->format('d M Y'),
                'description'     => 'Sale Agreement — Total Amount',
                'debit'           => (float)$sale->total_amount,
                'credit'          => 0,
                'running_balance' => 0,
                'type'            => 'installment',
                'status'          => 'pending',
                'sort_date'       => Carbon::parse($sale->sale_date),
            ]);
        }

        $ledger = $ledger->sortBy('sort_date')->values();

        // Running balance
        $runningBalance = 0;
        foreach ($ledger as &$row) {
            $runningBalance += $row['debit'] - $row['credit'];
            $row['running_balance'] = $runningBalance;
        }
        unset($row);

        $totalDebits    = $ledger->sum('debit');
        $totalCredits   = $ledger->sum('credit');
        $closingBalance = (float)$sale->remaining_balance;
        $allSales       = Sale::with(['customer', 'unit'])->get();

        return view('emi-collections.ledger', compact(
            'sale',
            'ledger',
            'opening',
            'totalDebits',
            'totalCredits',
            'closingBalance',
            'allSales',
            'installments'
        ));
    }

    // ──────────────────────────────────────────────────────────────────
    // G. BUILDER LOANS
    // ──────────────────────────────────────────────────────────────────

    public function loans(Request $request): View
    {
        $loans = Loan::with(['project'])
            ->when($request->filled('project_id'), fn($q) => $q->where('project_id', $request->project_id))
            ->latest()
            ->get();

        foreach ($loans as $loan) {
            $schedules = EmiSchedule::where('loan_id', $loan->id)->get();
            $loan->principal_paid    = $schedules->where('status', 'Paid')->sum('principal_component');
            $loan->interest_paid     = $schedules->where('status', 'Paid')->sum('interest_component');
            $loan->balance_remaining = $schedules->whereNotIn('status', ['Paid'])->sum('principal_component');
            $loan->next_due          = $schedules->where('status', 'Due')->sortBy('due_date')->first();
        }

        $projects     = Project::where('is_active', true)->orderBy('name')->get();
        $bankAccounts = Account::where('type', 'Asset')->orderBy('name')->get();

        return view('emi-collections.loans', compact('loans', 'projects', 'bankAccounts'));
    }

    public function storeLoan(Request $request): JsonResponse
    {
        $request->validate([
            'project_id'         => ['required', 'exists:projects,id'],
            'lender_name'        => ['required', 'string', 'max:255'],
            'principal_amount'   => ['required', 'numeric', 'min:1'],
            'interest_rate'      => ['required', 'numeric', 'min:0', 'max:100'],
            'tenure_months'      => ['required', 'integer', 'min:1', 'max:600'],
            'start_date'         => ['required', 'date'],
            'schedule_type'      => ['required', 'in:reducing_balance,flat'],
            'ledger_account_id'  => ['required', 'exists:accounts,id'],
            'interest_account_id'=> ['required', 'exists:accounts,id'],
        ]);

        $loan = DB::transaction(function () use ($request) {
            $principal   = (float)$request->principal_amount;
            $annualRate  = (float)$request->interest_rate;
            $monthlyRate = $annualRate / 12 / 100;
            $tenure      = (int)$request->tenure_months;
            $startDate   = Carbon::parse($request->start_date);

            $baseEmi = ($monthlyRate > 0)
                ? round($principal * $monthlyRate * pow(1 + $monthlyRate, $tenure) / (pow(1 + $monthlyRate, $tenure) - 1), 2)
                : round($principal / $tenure, 2);

            $loan = Loan::create([
                'system_id'           => auth()->user()->system_id ?? 1,
                'project_id'          => $request->project_id,
                'lender_name'         => $request->lender_name,
                'principal_amount'    => $principal,
                'interest_rate'       => $annualRate,
                'tenure_months'       => $tenure,
                'start_date'          => $startDate->toDateString(),
                'schedule_type'       => $request->schedule_type,
                'outstanding_balance' => $principal,
                'ledger_account_id'   => $request->ledger_account_id,
                'interest_account_id' => $request->interest_account_id,
            ]);

            $balance = $principal;
            for ($i = 1; $i <= $tenure; $i++) {
                $dueDate      = $startDate->copy()->addMonths($i);
                $interestComp = round($balance * $monthlyRate, 2);
                $principalComp = ($i === $tenure)
                    ? round($balance, 2)
                    : round($baseEmi - $interestComp, 2);

                EmiSchedule::create([
                    'system_id'           => $loan->system_id,
                    'loan_id'             => $loan->id,
                    'installment_no'      => $i,
                    'due_date'            => $dueDate->toDateString(),
                    'emi_amount'          => round($principalComp + $interestComp, 2),
                    'principal_component' => $principalComp,
                    'interest_component'  => $interestComp,
                    'status'              => 'Due',
                ]);

                $balance = max(0, $balance - $principalComp);
            }

            return $loan;
        });

        return response()->json(['success' => true, 'loan' => $loan]);
    }

    public function loanAmortization(Loan $loan): JsonResponse
    {
        $schedules = EmiSchedule::where('loan_id', $loan->id)->orderBy('installment_no')->get();

        return response()->json([
            'loan'      => $loan->load('project'),
            'schedules' => $schedules,
            'summary'   => [
                'principal_paid'    => $schedules->where('status', 'Paid')->sum('principal_component'),
                'interest_paid'     => $schedules->where('status', 'Paid')->sum('interest_component'),
                'balance_remaining' => $schedules->whereNotIn('status', ['Paid'])->sum('principal_component'),
                'total_interest'    => $schedules->sum('interest_component'),
            ],
        ]);
    }

    public function bulkUpdateSchedule(Request $request, Sale $sale): JsonResponse
    {
        $request->validate([
            'installments' => ['required', 'array'],
            'installments.*.installment_no' => ['required', 'integer'],
            'installments.*.label' => ['required', 'string', 'max:100'],
            'installments.*.due_date' => ['required', 'date'],
            'installments.*.amount' => ['required', 'numeric', 'min:0'],
            'installments.*.status' => ['required', 'in:pending,paid,overdue,partial'],
        ]);

        $installmentsData = $request->input('installments');

        $sum = collect($installmentsData)->sum('amount');
        if (abs($sum - (float)$sale->total_amount) > 0.01) {
            return response()->json([
                'error' => 'The sum of all installments (₹' . number_format($sum, 2) . 
                           ') must equal the total sale amount (₹' . number_format($sale->total_amount, 2) . ').'
            ], 422);
        }

        DB::transaction(function () use ($sale, $installmentsData) {
            $currentInsts = CustomerInstallment::where('sale_id', $sale->id)->get()->keyBy('id');
            
            $keepIds = [];
            foreach ($installmentsData as $inst) {
                if (isset($inst['id']) && $currentInsts->has($inst['id'])) {
                    $row = $currentInsts->get($inst['id']);
                    if ($row->status !== 'paid') {
                        $row->update([
                            'installment_no' => $inst['installment_no'],
                            'label'          => $inst['label'],
                            'due_date'       => $inst['due_date'],
                            'amount'         => $inst['amount'],
                            'status'         => $inst['status'],
                        ]);
                    }
                    $keepIds[] = $row->id;
                } else {
                    $newRow = CustomerInstallment::create([
                        'sale_id'        => $sale->id,
                        'installment_no' => $inst['installment_no'],
                        'label'          => $inst['label'],
                        'due_date'       => $inst['due_date'],
                        'amount'         => $inst['amount'],
                        'status'         => $inst['status'],
                        'schedule_type'  => 'fixed_emi',
                    ]);
                    $keepIds[] = $newRow->id;
                }
            }
            
            CustomerInstallment::where('sale_id', $sale->id)
                ->whereNotIn('id', $keepIds)
                ->where('status', '!=', 'paid')
                ->delete();
        });

        return response()->json(['success' => true]);
    }
}
