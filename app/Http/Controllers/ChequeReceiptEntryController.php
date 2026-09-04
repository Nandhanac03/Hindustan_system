<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\ReceiptStore;
use App\Models\CompanyBankAccount;
use App\Models\Customer;
use App\Models\Project;
use App\Models\PaymentMode;
use App\Models\ChequeStatus;
use App\Models\Sale;
use App\Models\Bank;
use App\Services\ChequeRealizationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ChequeReceiptEntryController extends Controller
{
    public function __construct(
        private readonly ChequeRealizationService $realizationService
    ) {}

    /**
     * Display the Cheque & Receipt Entry Desk with Tabbed Views & Statuses.
     */
    public function index(Request $request): View
    {
        $activeTab = $request->input('tab', 'all');

        // Query for Main Receipts Table
        $query = Receipt::with(['companyBankAccount', 'customer', 'project', 'unit', 'bank'])
            ->latest('receipt_date')
            ->latest('id');

        // Filter based on active tab
        if ($activeTab === 'cheques') {
            $query->whereIn('realization_status', ['pending', 'cheque_in_hand', 'deposited']);
        } elseif ($activeTab !== 'all' && $activeTab !== 'stores') {
            $query->where('realization_status', $activeTab);
        }

        // Additional request filters
        if ($request->filled('company_bank_account_id')) {
            $query->where('company_bank_account_id', $request->input('company_bank_account_id'));
        }

        if ($request->filled('customer_id')) {
            $custId = $request->input('customer_id');
            $query->where(function ($q) use ($custId) {
                $q->where('customer_id', $custId)
                  ->orWhereHas('sale', function ($sq) use ($custId) {
                      $sq->where('customer_id', $custId);
                  });
            });
        }

        if ($request->filled('realization_status')) {
            $statusVal = strtolower(str_replace(' ', '_', trim($request->input('realization_status'))));
            $query->where(function ($q) use ($statusVal, $request) {
                $q->where('realization_status', $statusVal)
                  ->orWhere('realization_status', $request->input('realization_status'));
            });
        }

        if ($request->filled('payment_mode')) {
            $query->where('payment_mode', $request->input('payment_mode'));
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                  ->orWhere('remarks', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhere('drawee_bank', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereHas('unit', function ($uq) use ($search) {
                      $uq->where('door_no', 'like', "%{$search}%");
                  })
                  ->orWhereHas('project', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('sale.unit', function ($uq) use ($search) {
                      $uq->where('door_no', 'like', "%{$search}%");
                  })
                  ->orWhereHas('companyBankAccount', function ($bq) use ($search) {
                      $bq->where('bank_name', 'like', "%{$search}%")
                         ->orWhere('account_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('receipt_date', $request->input('date'));
        }
        if ($request->filled('receipt_date')) {
            $query->whereDate('receipt_date', $request->input('receipt_date'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('receipt_date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('receipt_date', '<=', $request->input('date_to'));
        }

        // Fetch dynamic Cheque Statuses from Master table (cheque_statuses)
        $chequeStatusesMaster = ChequeStatus::where('is_active', true)->orderBy('name')->get();
        $chequeStatusesMap = [];
        foreach ($chequeStatusesMaster as $cs) {
            $key = strtolower(str_replace(' ', '_', trim($cs->name)));
            $color = strtolower($cs->color_code ?? 'slate-500');
            $badgeClasses = match(true) {
                str_contains($color, 'emerald') || str_contains($color, 'green') => 'bg-emerald-50 text-emerald-800 border-emerald-300',
                str_contains($color, 'amber') || str_contains($color, 'orange') || str_contains($color, 'yellow') => 'bg-amber-50 text-amber-800 border-amber-300',
                str_contains($color, 'blue') => 'bg-blue-50 text-blue-800 border-blue-300',
                str_contains($color, 'rose') || str_contains($color, 'red') => 'bg-rose-50 text-rose-800 border-rose-300',
                str_contains($color, 'purple') || str_contains($color, 'violet') => 'bg-purple-50 text-purple-800 border-purple-300',
                default => 'bg-slate-100 text-slate-700 border-slate-300',
            };

            $tabActiveClasses = match(true) {
                str_contains($color, 'emerald') || str_contains($color, 'green') => 'bg-emerald-600 text-white shadow-md',
                str_contains($color, 'amber') || str_contains($color, 'orange') || str_contains($color, 'yellow') => 'bg-amber-600 text-white shadow-md',
                str_contains($color, 'blue') => 'bg-blue-600 text-white shadow-md',
                str_contains($color, 'rose') || str_contains($color, 'red') => 'bg-rose-600 text-white shadow-md',
                str_contains($color, 'purple') || str_contains($color, 'violet') => 'bg-purple-600 text-white shadow-md',
                default => 'bg-slate-800 text-white shadow-md',
            };

            $count = Receipt::where('realization_status', $key)->count();

            $chequeStatusesMap[$key] = [
                'key'                => $key,
                'name'               => strtoupper($cs->name),
                'badge_classes'      => $badgeClasses,
                'tab_active_classes' => $tabActiveClasses,
                'color_code'         => $cs->color_code,
                'count'              => $count,
            ];
        }

        // Fetch receipts for the desk register
        $receipts = $query->get();

        $allReceiptsFormatted = $receipts->map(function ($r) use ($chequeStatusesMap) {
            $bankName = $r->companyBankAccount?->bank_name ?: ($r->bank?->bank_name ?: 'General Account');
            $accNo    = $r->companyBankAccount?->account_number;
            $upiId    = $r->companyBankAccount?->upi_id;
            $ifsc     = $r->companyBankAccount?->ifsc_code ?: $r->bank?->ifsc_code;

            $rst = strtolower($r->realization_status ?? 'pending');
            $rstMaster = $chequeStatusesMap[$rst] ?? null;
            $badgeClasses = $rstMaster ? $rstMaster['badge_classes'] : match($rst) {
                'realized' => 'bg-emerald-50 text-emerald-800 border-emerald-300',
                'cheque_in_hand' => 'bg-amber-50 text-amber-800 border-amber-300',
                'deposited' => 'bg-blue-50 text-blue-800 border-blue-300',
                'bounced' => 'bg-rose-50 text-rose-800 border-rose-300',
                default => 'bg-slate-100 text-slate-700 border-slate-300'
            };
            $statusName = $rstMaster ? $rstMaster['name'] : strtoupper(str_replace('_', ' ', $rst));

            return [
                'id'                          => $r->id,
                'ref'                         => $r->reference_no ?: 'REC-' . str_pad((string)$r->id, 5, '0', STR_PAD_LEFT),
                'amount'                      => (float)$r->amount,
                'date'                        => $r->receipt_date?->format('Y-m-d'),
                'customer_name'               => $r->customer?->name ?? ($r->payer_name ?? ($r->sale?->customer?->name ?? 'General Payer')),
                'payer_name'                  => $r->payer_name,
                'customer_id'                 => $r->customer_id,
                'payment_mode'                => $r->payment_mode ?: 'Cash',
                'company_bank_account_id'     => $r->company_bank_account_id,
                'company_bank_account_name'   => $bankName,
                'company_bank_account_number' => $accNo,
                'company_bank_account_upi_id' => $upiId,
                'company_bank_account_ifsc'   => $ifsc,
                'project_id'                  => $r->project_id,
                'project_name'                => $r->project?->name ?? ($r->sale?->project?->name ?? '—'),
                'unit_id'                     => $r->unit_id,
                'unit_name'                   => $r->unit?->door_no ?? ($r->sale?->unit?->door_no ?? '—'),
                'reference_no'                => $r->reference_no,
                'remarks'                     => $r->remarks,
                'is_allocated'                => (bool)$r->is_allocated,
                'realization_status'          => $r->realization_status,
                'status_badge_classes'        => $badgeClasses,
                'status_display_name'         => $statusName,
                'is_cheque_instrument'        => $r->isChequeInstrument(),
                'can_realize'                 => !$r->isTerminal(),
                'can_reinitialize'            => $r->realization_status === 'bounced',
                'cheque_date'                 => $r->cheque_date?->format('Y-m-d'),
                'source_bank'                 => $r->drawee_bank ?: ($r->bank?->bank_name ?: 'Customer Bank / Payer Instrument'),
                'destination_bank'            => $bankName . ($accNo ? " (A/C: {$accNo})" : ''),
                'drawee_bank'                 => $r->drawee_bank,
                'realized_at'                 => $r->realized_at?->format('d M Y, h:i A'),
            ];
        });

        // Tab Metrics & Summaries
        $totalCollectionAmount   = (float) Receipt::where('realization_status', 'realized')->sum('amount');
        $totalReceiptsCount      = Receipt::count();
        $pendingRealizationCount = Receipt::whereIn('realization_status', ['pending', 'cheque_in_hand', 'deposited'])->count();
        $pendingRealizationAmount= (float) Receipt::whereIn('realization_status', ['pending', 'cheque_in_hand', 'deposited'])->sum('amount');
        $realizedCount           = Receipt::where('realization_status', 'realized')->count();
        $bouncedCount            = Receipt::where('realization_status', 'bounced')->count();
        $bouncedAmount           = (float) Receipt::where('realization_status', 'bounced')->sum('amount');

        $companyBankAccounts = CompanyBankAccount::orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $totalLiquidity     = $companyBankAccounts->sum('current_balance');
        $defaultBankAccount = $companyBankAccounts->firstWhere('is_default', true);

        $customers    = Customer::orderBy('name')->get(['id', 'name', 'phone']);
        $projects     = Project::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        $paymentModes = class_exists(PaymentMode::class)
            ? PaymentMode::where('status', 'active')->orderBy('id')->get(['id', 'name', 'code', 'requires_reference', 'requires_bank'])
            : collect([]);

        $realizationStatuses = Receipt::STATUSES;

        $activeSales = Sale::with(['customer', 'project', 'unit.floor', 'unit.unitType', 'saleUnits.unit.floor', 'saleUnits.unit.unitType', 'customerInstallments'])
            ->where('status', 'active')
            ->latest()
            ->get();

        foreach ($activeSales as $sale) {
            $totalPaid = (float) Receipt::where('sale_id', $sale->id)->sum('amount');
            $installments = $sale->customerInstallments;

            $nextDueAmount = 0;
            $allocated = $totalPaid;
            if ($installments->isNotEmpty()) {
                foreach ($installments as $inst) {
                    $instAmt = (float)$inst->amount;
                    if ($allocated >= $instAmt) {
                        $allocated -= $instAmt;
                    } else {
                        $nextDueAmount = $instAmt - $allocated;
                        break;
                    }
                }
            }
            $sale->next_due_amount = $nextDueAmount > 0 ? round($nextDueAmount, 0) : round((float)$sale->remaining_balance, 0);
        }

        $banks = Bank::where('status', 'active')->orderBy('bank_name')->get();

        return view('cheque-receipt-entry.index', compact(
            'activeTab',
            'receipts',
            'allReceiptsFormatted',
            'totalCollectionAmount',
            'totalReceiptsCount',
            'pendingRealizationCount',
            'pendingRealizationAmount',
            'realizedCount',
            'bouncedCount',
            'bouncedAmount',
            'companyBankAccounts',
            'totalLiquidity',
            'defaultBankAccount',
            'customers',
            'projects',
            'paymentModes',
            'realizationStatuses',
            'chequeStatusesMaster',
            'chequeStatusesMap',
            'activeSales',
            'banks'
        ));
    }

    /**
     * Store new Cheque & Receipt Entry.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_bank_account_id' => ['required', 'exists:company_bank_accounts,id'],
            'receipt_date'            => ['required', 'date'],
            'amount'                  => ['required', 'numeric', 'min:0.01'],
            'payment_mode'            => ['required', 'string'],
            'reference_no'            => ['nullable', 'string', 'max:100'],
            'customer_id'             => ['nullable', 'exists:customers,id'],
            'payer_name'              => ['nullable', 'string', 'max:255'],
            'project_id'              => ['nullable', 'exists:projects,id'],
            'remarks'                 => ['nullable', 'string', 'max:500'],
            'cheque_date'             => ['nullable', 'date'],
            'drawee_bank'             => ['nullable', 'string', 'max:100'],
        ]);

        $isChequeMode = in_array($validated['payment_mode'], Receipt::DEFERRED_MODES, true);

        DB::transaction(function () use ($validated, $isChequeMode, &$receipt) {
            $bankAccount = CompanyBankAccount::lockForUpdate()->findOrFail($validated['company_bank_account_id']);

            $remarks = $validated['remarks'] ?? null;
            if (empty($remarks) && !empty($validated['payer_name'])) {
                $remarks = 'Inbound Payment received from ' . $validated['payer_name'];
            }

            $initStatus = $isChequeMode ? 'cheque_in_hand' : 'realized';

            $receipt = Receipt::create([
                'company_bank_account_id' => $bankAccount->id,
                'receipt_date'            => $validated['receipt_date'],
                'amount'                  => $validated['amount'],
                'payment_mode'            => $validated['payment_mode'],
                'reference_no'            => $validated['reference_no'] ?? null,
                'customer_id'             => $validated['customer_id'] ?? null,
                'project_id'              => $validated['project_id'] ?? null,
                'remarks'                 => $remarks,
                'is_allocated'            => false,
                'realization_status'      => $initStatus,
                'cheque_date'             => $validated['cheque_date'] ?? null,
                'drawee_bank'             => $validated['drawee_bank'] ?? null,
                'realized_at'             => $isChequeMode ? null : now(),
                'realized_by'             => $isChequeMode ? null : auth()->id(),
            ]);

            if (!$isChequeMode) {
                $bankAccount->increment('current_balance', $validated['amount']);
            }

            ReceiptStore::create([
                'receipt_id'              => $receipt->id,
                'company_bank_account_id' => $bankAccount->id,
                'customer_id'             => $validated['customer_id'] ?? null,
                'project_id'              => $validated['project_id'] ?? null,
                'receipt_date'            => $validated['receipt_date'],
                'amount'                  => $validated['amount'],
                'payment_mode'            => $validated['payment_mode'],
                'reference_no'            => $validated['reference_no'] ?? null,
                'remarks'                 => $remarks,
                'status'                  => $initStatus,
                'created_by'              => auth()->id(),
            ]);
        });

        $message = $isChequeMode
            ? "Cheque/Receipt Entry #{$receipt->id} recorded as 'Cheque in Hand'."
            : "Cheque/Receipt Entry #{$receipt->id} created & ₹" . number_format((float)$receipt->amount, 2) . " credited.";

        return redirect()->route('cheque-receipt-entry.index')->with('success', $message);
    }

    /**
     * Mark Cheque as Realized.
     */
    public function realize(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'company_bank_account_id' => ['nullable', 'exists:company_bank_accounts,id'],
            'realized_at'             => ['nullable', 'date'],
            'remarks'                 => ['nullable', 'string', 'max:500'],
        ]);

        $receipt = Receipt::findOrFail($id);

        try {
            $this->realizationService->realize($receipt, [
                'company_bank_account_id' => $validated['company_bank_account_id'] ?? null,
                'realized_at'             => $validated['realized_at'] ?? null,
                'realized_by'             => auth()->id(),
                'remarks'                 => $validated['remarks'] ?? null,
            ]);

            $bankName = $receipt->fresh()->companyBankAccount?->bank_name ?? 'Company Bank Account';
            $amount   = number_format((float) $receipt->amount, 2);

            return redirect()->back()->with(
                'success',
                "✅ Receipt #{$id} Realized! ₹{$amount} credited to {$bankName}."
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark Cheque as Bounced.
     */
    public function markBounced(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $receipt = Receipt::findOrFail($id);

        try {
            $this->realizationService->markBounced($receipt, [
                'changed_by' => auth()->id(),
                'remarks'    => $validated['remarks'] ?? 'Cheque dishonoured by bank.',
            ]);

            return redirect()->back()->with(
                'success',
                "❌ Receipt #{$id} marked as Bounced."
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Re-initialize Bounced Cheque back to 'cheque_in_hand'.
     */
    public function reinitialize(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $receipt = Receipt::findOrFail($id);

        try {
            $this->realizationService->reinitialize($receipt, [
                'changed_by' => auth()->id(),
                'remarks'    => $validated['remarks'] ?? 'Re-initialized bounced cheque for clearance.',
            ]);

            return redirect()->back()->with(
                'success',
                "🔄 Receipt #{$id} Re-Initialized to 'Cheque in Hand'."
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Advance Cheque Status.
     */
    public function advanceStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'new_status' => ['required', 'in:cheque_in_hand,deposited,cancelled'],
            'remarks'    => ['nullable', 'string', 'max:500'],
        ]);

        $receipt = Receipt::findOrFail($id);

        try {
            $this->realizationService->advanceStatus($receipt, $validated['new_status'], [
                'changed_by' => auth()->id(),
                'remarks'    => $validated['remarks'] ?? null,
            ]);

            $label = Receipt::STATUSES[$validated['new_status']] ?? $validated['new_status'];

            return redirect()->back()->with('success', "Receipt #{$id} status updated to: {$label}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete / Cancel Receipt.
     */
    public function destroy(int $id): RedirectResponse
    {
        $receipt = Receipt::findOrFail($id);

        DB::transaction(function () use ($receipt) {
            if ($receipt->isRealized() && $receipt->company_bank_account_id) {
                $bankAccount = CompanyBankAccount::lockForUpdate()->find($receipt->company_bank_account_id);
                if ($bankAccount) {
                    $bankAccount->decrement('current_balance', $receipt->amount);
                }
            }

            ReceiptStore::where('receipt_id', $receipt->id)->delete();
            $receipt->delete();
        });

        return redirect()->route('cheque-receipt-entry.index')
            ->with('success', "Receipt #{$id} deleted successfully.");
    }
}
