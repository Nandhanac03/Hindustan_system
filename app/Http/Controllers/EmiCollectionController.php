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
use App\Models\EmiRescheduleLog;
use App\Models\Payee;
use App\Models\Bank;
use App\Models\PaymentMode;
use App\Services\RealizedCollectionService;
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
        $sales = Sale::with(['customer', 'project', 'unit.floor', 'unit.unitType', 'saleUnits.unit.floor', 'saleUnits.unit.unitType', 'receipts' => function($q) {
                $q->with('bank')->latest();
            }])
            ->where('status', 'active')
            ->latest()
            ->paginate(50)->withQueryString();

        $activeSales = Sale::with(['customer', 'project', 'unit.floor', 'unit.unitType', 'saleUnits.unit.floor', 'saleUnits.unit.unitType', 'receipts' => function($q) {
                $q->with('bank')->latest();
            }])
            ->where('status', 'active')
            ->latest()
            ->get();

        $totalContractValue = (float) Sale::where('status', 'active')->sum('total_amount');
        $totalSales         = Sale::where('status', 'active')->count();

        // ── Match Dashboard & Customer Ledger ────────────────────────────────
        // Collections are counted from RECEIPTS, not from active sales only.
        // Cash received against a sale that was later cancelled / returned /
        // exchanged is still realized money, and the refunds paid back on it
        // are deducted. Sharing one service keeps this KPI identical to the
        // Dashboard one forever. See RealizedCollectionService.
        $collections               = RealizedCollectionService::summarize();
        $grossRealizedCollections  = $collections['gross'];
        $totalRefundsPaid          = $collections['refunds'];
        $totalPaidSales            = $collections['net'];
        // ─────────────────────────────────────────────────────────────────────

        foreach ($activeSales as $sale) {
            $totalPaid = (float) $sale->receipts
                ->filter(fn($r) => strtolower($r->realization_status ?? 'realized') === 'realized')
                ->sum('amount');
            $realizedRemaining = max(0.0, round((float) $sale->total_amount - $totalPaid, 2));
            $sale->remaining_balance = $realizedRemaining;

            $installments = CustomerInstallment::where('sale_id', $sale->id)
                ->orderBy('installment_no')
                ->get();

            $nextDueAmount = 0;
            $allocated = $totalPaid;
            foreach ($installments as $inst) {
                $instAmt = (float)$inst->amount;
                if ($allocated >= $instAmt) {
                    $allocated -= $instAmt;
                } else {
                    $nextDueAmount = $instAmt - $allocated;
                    break;
                }
            }
            $due = $nextDueAmount > 0 ? $nextDueAmount : $realizedRemaining;
            $sale->setAttribute('next_due_amount', $due);
            $sale->next_due_amount = $due;

            // Actual installment count from schedule (excluding Down Payment if present, or total EMI rows)
            $emiOnlyCount = $installments->filter(function($i) {
                return !str_contains(strtolower($i->label), 'down payment') && $i->installment_no > 0;
            })->count();
            $effectiveCount = $emiOnlyCount > 0 ? $emiOnlyCount : ($installments->count() > 0 ? $installments->count() : ($sale->emi_installment_count ?? 12));
            $sale->setAttribute('actual_installment_count', $effectiveCount);
        }

        $totalOutstanding     = (float) $activeSales->sum('remaining_balance');
        $pendingPaymentsCount = $activeSales->filter(fn($s) => (float) $s->remaining_balance > 0)->count();
        $fullyPaidCount       = $activeSales->filter(fn($s) => (float) $s->remaining_balance <= 0)->count();

        $recentBookings = $activeSales->filter(fn($s) => (float) $s->remaining_balance > 0)->take(5)->values();

        $allSalesFormatted = $activeSales->map(function ($sale) {
            $totalPaid = (float) $sale->receipts
                ->filter(fn($r) => strtolower($r->realization_status ?? 'realized') === 'realized')
                ->sum('amount');
            $realizedRemaining = max(0.0, round((float) $sale->total_amount - $totalPaid, 2));
            $formattedUnitText = '';
            if($sale->saleUnits && $sale->saleUnits->isNotEmpty()) {
                $unitStrings = [];
                foreach($sale->saleUnits as $su) {
                    if($su->unit) {
                        $door = trim(explode(',', $su->unit->door_no)[0]);
                        $type = strtolower($su->unit->unitType?->name ?? '');
                        if ($type === 'flat') $type = 'Apartment';
                        elseif (strpos($type, 'parking') !== false) $type = 'Parking';
                        else $type = ucfirst($type);

                        $floor = trim($su->unit->floor?->name ?? '');
                        if (preg_match('/^(floor|fl)\b/i', $floor)) {
                            $floor = preg_replace('/^(floor|fl)\b/i', 'Floor', $floor);
                        } elseif ($floor && is_numeric($floor)) {
                            $floor = 'Floor ' . $floor;
                        } elseif ($floor) {
                            $floor = ucfirst($floor);
                        }
                        $unitStrings[] = $door . ($type ? "($type)" : "") . ($floor ? " - $floor" : "");
                    }
                }
                $formattedUnitText = implode(', ', $unitStrings);
            } elseif($sale->unit) {
                $door = trim(explode(',', $sale->unit->door_no)[0]);
                $type = strtolower($sale->unit->unitType?->name ?? '');
                if ($type === 'flat') $type = 'Apartment';
                elseif (strpos($type, 'parking') !== false) $type = 'Parking';
                else $type = ucfirst($type);

                $floor = trim($sale->unit->floor?->name ?? '');
                if (preg_match('/^(floor|fl)\b/i', $floor)) {
                    $floor = preg_replace('/^(floor|fl)\b/i', 'Floor', $floor);
                } elseif ($floor && is_numeric($floor)) {
                    $floor = 'Floor ' . $floor;
                } elseif ($floor) {
                    $floor = ucfirst($floor);
                }
                $formattedUnitText = $door . ($type ? "($type)" : "") . ($floor ? " - $floor" : "");
            } else {
                $formattedUnitText = '—';
            }

            return [
                'id'                     => $sale->id,
                'sale_number'            => $sale->sale_number,
                'customer_id'            => $sale->customer_id,
                'customer_name'          => $sale->customer?->name ?? 'N/A',
                'customer_phone'         => $sale->customer?->phone ?? '',
                'project_id'             => $sale->project_id,
                'project_name'           => $sale->project?->name ?? 'N/A',
                'unit_text'              => $formattedUnitText,
                'total_amount'           => (float) $sale->total_amount,
                'total_paid'             => $totalPaid,
                'remaining_balance'      => $realizedRemaining,
                'next_due_amount'        => (float) ($sale->next_due_amount ?? $realizedRemaining),
                'payment_plan'           => $sale->payment_plan ?: 'emi',
                'emi_installment_count'  => $sale->actual_installment_count ?? ($sale->emi_installment_count ?? 12),
                'emi_frequency'          => $sale->emi_frequency ?? 'monthly',
                'created_date'           => $sale->created_at?->format('Y-m-d'),
                'receipts'               => $sale->receipts->map(function($r) {
                    return [
                        'receipt_date' => $r->receipt_date ? \Carbon\Carbon::parse($r->receipt_date)->format('d M Y') : '—',
                        'payment_mode' => $r->payment_mode ?: '—',
                        'reference_no' => $r->reference_no ?: '—',
                        'bank_name'    => $r->bank?->bank_name ?: ($r->companyBankAccount?->bank_name ?: '—'),
                        'amount'       => (float)$r->amount,
                    ];
                })->values()->toArray(),
            ];
        });

        $customers    = \App\Models\Customer::orderBy('name')->get(['id', 'name', 'phone']);
        $projects     = Project::where('is_active', true)->orderBy('name')->get(['id', 'name']);
        $banks        = Bank::where('status', 'active')->orderBy('bank_name')->get();
        $paymentModes = PaymentMode::where('status', 'active')->orderBy('name')->get();

        return view('emi-collections.index', compact(
            'sales',
            'totalContractValue',
            'totalPaidSales',
            'grossRealizedCollections',
            'totalRefundsPaid',
            'totalOutstanding',
            'totalSales',
            'pendingPaymentsCount',
            'fullyPaidCount',
            'recentBookings',
            'activeSales',
            'allSalesFormatted',
            'customers',
            'projects',
            'banks',
            'paymentModes'
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
        $banks        = \App\Models\Bank::where('status', 'active')->orderBy('bank_name')->get();

        $recentReceipts = Receipt::with(['customer', 'sale.project', 'sale.unit', 'partner', 'bank'])
            ->latest('receipt_date')
            ->take(20)
            ->get();

        return view('emi-collections.receipts', compact('sales', 'projects', 'bankAccounts', 'partners', 'recentReceipts', 'banks'));
    }

    /**
     * Record a receipt against a Sale (the real sales workflow).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sale_id'           => ['nullable', 'exists:sales,id'],
            'booking_id'        => ['nullable', 'exists:sales,id'], // fallback
            'amount'            => ['required_unless:collection_type,reschedule', 'nullable', 'numeric', 'min:0.01'],
            'payment_mode'      => ['required_unless:collection_type,reschedule', 'nullable', 'string', 'max:100'],
            'receipt_date'      => ['nullable', 'date'],
            'reference_no'      => ['required_unless:collection_type,reschedule', 'string', 'max:100'],
            'bank_id'           => ['nullable', 'exists:banks,id'],
            'bank_name'         => ['nullable', 'string', 'max:100'],
            'remarks'           => ['nullable', 'string', 'max:500'],
            'partner_id'        => ['nullable', 'exists:payees,id'],
            'collection_type'   => ['nullable', 'in:regular,prepayment,reschedule'],
            'prepayment_option' => ['nullable', 'in:reduce_tenure,reduce_emi'],
            'reschedule_option' => ['nullable', 'in:extend_tenure,shift_dates'],
            'reschedule_reason' => ['nullable', 'string'],
            'new_count'         => ['nullable', 'integer', 'min:1'],
            'shift_months'      => ['nullable', 'integer', 'min:1'],
        ], [
            'reference_no.required_unless' => 'The Ref / Cheque / UTR No. field is required.',
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

        if (($validated['collection_type'] ?? 'regular') !== 'reschedule') {
            if (round((float)$validated['amount'], 2) > round((float)$sale->remaining_balance, 2)) {
                return response()->json([
                    'error' => 'Payment (₹' . number_format((float)$validated['amount'], 2) .
                               ') exceeds remaining balance (₹' . number_format((float)$sale->remaining_balance, 2) . ').'
                ], 422);
            }
        }

        if ($request->filled('label') && preg_match('/^(EMI\s*\d+|Down Payment)/i', $request->label)) {
            $currentLabel = trim(explode('(', $request->label)[0]);
            $installments = CustomerInstallment::where('sale_id', $sale->id)
                ->orderBy('installment_no')
                ->get();

            $firstUnpaid = $installments->first(function ($inst) {
                return in_array($inst->status, ['pending', 'partial', 'overdue']) && ((float)$inst->amount - (float)($inst->paid_amount ?? 0) > 0.01);
            });

            if ($firstUnpaid && strcasecmp(trim($firstUnpaid->label), $currentLabel) !== 0) {
                $targetInst = $installments->first(fn($i) => strcasecmp(trim($i->label), $currentLabel) === 0);
                if ($targetInst && $targetInst->installment_no > $firstUnpaid->installment_no) {
                    $unpaidDesc = $firstUnpaid->label . ' (Due)';
                    return response()->json([
                        'error' => "Installments must be paid in sequential order. Please settle earlier pending installment '{$unpaidDesc}' first before paying '{$request->label}'."
                    ], 422);
                }
            }
        }

        $result = DB::transaction(function () use ($validated, $sale) {
            $collectionType = $validated['collection_type'] ?? 'regular';
            $receipt = null;

            if ($collectionType === 'regular' || $collectionType === 'prepayment') {
                $bankId = $validated['bank_id'] ?? null;
                if (!$bankId && !empty($validated['bank_name'])) {
                    $bank = \App\Models\Bank::where('bank_name', $validated['bank_name'])->first();
                    if ($bank) {
                        $bankId = $bank->id;
                    }
                }

                $receipt = Receipt::create([
                    'sale_id'      => $sale->id,
                    'customer_id'  => $sale->customer_id,
                    'project_id'   => $sale->project_id,
                    'unit_id'      => $sale->unit_id,
                    'receipt_date' => $validated['receipt_date'] ?? now()->toDateString(),
                    'amount'       => $validated['amount'],
                    'payment_mode' => $validated['payment_mode'],
                    'payment_type' => $collectionType,
                    'reference_no' => $validated['reference_no'] ?? null,
                    'bank_id'      => $bankId,
                    'remarks'      => $validated['remarks'] ?? null,
                    'created_by'   => auth()->id() ?? 1,
                    'partner_id'   => $validated['partner_id'] ?? null,
                ]);

                $totalPaid = $sale->receipts()->sum('amount');
                $sale->update(['remaining_balance' => max(0, round((float)$sale->total_amount - $totalPaid, 2))]);
            }

            if ($collectionType === 'regular') {
                CustomerInstallment::allocatePaymentStatusForSale($sale->id);
            } 
            elseif ($collectionType === 'prepayment') {
                $prepaymentAmount = (float) $validated['amount'];
                $option = $validated['prepayment_option'];
                
                $pendingInstallments = CustomerInstallment::where('sale_id', $sale->id)
                    ->whereIn('status', ['pending', 'partial'])
                    ->orderBy('installment_no', 'asc')
                    ->get();
                $oldSnapshot = $pendingInstallments->toArray();

                if ($option === 'reduce_tenure') {
                    $remainingPrepayment = $prepaymentAmount;
                    $reversedInstallments = $pendingInstallments->reverse();
                    foreach ($reversedInstallments as $inst) {
                        if ($remainingPrepayment <= 0) break;
                        $instOutstanding = $inst->amount - ($inst->paid_amount ?? 0);
                        if ($remainingPrepayment >= $instOutstanding) {
                            $inst->update(['paid_amount' => $inst->amount, 'status' => 'paid']);
                            $remainingPrepayment -= $instOutstanding;
                        } else {
                            $inst->update(['paid_amount' => ($inst->paid_amount ?? 0) + $remainingPrepayment, 'status' => 'partial']);
                            $remainingPrepayment = 0;
                        }
                    }
                } elseif ($option === 'reduce_emi') {
                    $totalPendingCount = $pendingInstallments->count();
                    $reductionPerInstallment = round($prepaymentAmount / $totalPendingCount, 2);
                    $remainingPrepayment = $prepaymentAmount;
                    foreach ($pendingInstallments as $index => $inst) {
                        $applyAmount = ($index === $totalPendingCount - 1) ? $remainingPrepayment : $reductionPerInstallment;
                        $newAmount = max(0, $inst->amount - $applyAmount);
                        $inst->update([
                            'amount' => $newAmount,
                            'status' => $newAmount <= ($inst->paid_amount ?? 0) ? 'paid' : $inst->status,
                        ]);
                        $remainingPrepayment -= $applyAmount;
                    }
                }

                $newSnapshot = CustomerInstallment::where('sale_id', $sale->id)
                    ->whereIn('id', $pendingInstallments->pluck('id'))->get()->toArray();

                EmiRescheduleLog::create([
                    'sale_id' => $sale->id,
                    'action_type' => 'prepayment_' . $option,
                    'reason' => 'Prepayment - ' . $option,
                    'old_schedule_snapshot' => $oldSnapshot,
                    'new_schedule_snapshot' => $newSnapshot,
                    'performed_by' => auth()->id() ?? 1,
                ]);
            }
            elseif ($collectionType === 'reschedule') {
                $option = $validated['reschedule_option'];
                $reason = $validated['reschedule_reason'];
                
                $pendingInstallments = CustomerInstallment::where('sale_id', $sale->id)
                    ->whereIn('status', ['pending', 'partial'])
                    ->orderBy('installment_no', 'asc')
                    ->get();
                    
                $oldSnapshot = $pendingInstallments->toArray();
                foreach ($pendingInstallments as $inst) {
                    $inst->update(['status' => 'superseded']);
                }

                $newInstallments = [];
                $firstPending = $pendingInstallments->first();
                
                if ($option === 'extend_tenure') {
                    $totalOutstanding = $pendingInstallments->sum(function($i) { return $i->amount - ($i->paid_amount ?? 0); });
                    $newCount = $validated['new_count'];
                    $newEmiAmount = round($totalOutstanding / $newCount, 2);
                    $startDate = Carbon::parse($firstPending->due_date);
                    $remainingBalance = $totalOutstanding;
                    $startInstallmentNo = $firstPending->installment_no;

                    for ($i = 0; $i < $newCount; $i++) {
                        $amt = ($i === $newCount - 1) ? $remainingBalance : $newEmiAmount;
                        $newInst = CustomerInstallment::create([
                            'sale_id' => $sale->id,
                            'installment_no' => $startInstallmentNo + $i,
                            'label' => 'Rescheduled EMI ' . ($startInstallmentNo + $i),
                            'due_date' => $startDate->copy()->addMonths($i)->toDateString(),
                            'amount' => $amt,
                            'paid_amount' => 0,
                            'status' => 'pending',
                            'schedule_type' => 'fixed_emi',
                            'rescheduled_from_id' => $firstPending->id
                        ]);
                        $newInstallments[] = $newInst->toArray();
                        $remainingBalance -= $amt;
                    }
                } elseif ($option === 'shift_dates') {
                    $shiftMonths = $validated['shift_months'];
                    foreach ($pendingInstallments as $inst) {
                        $newDate = Carbon::parse($inst->due_date)->addMonths($shiftMonths)->toDateString();
                        $newInst = CustomerInstallment::create([
                            'sale_id' => $sale->id,
                            'installment_no' => $inst->installment_no,
                            'label' => $inst->label,
                            'due_date' => $newDate,
                            'amount' => $inst->amount,
                            'paid_amount' => $inst->paid_amount,
                            'status' => ($inst->paid_amount > 0) ? 'partial' : 'pending',
                            'schedule_type' => $inst->schedule_type,
                            'rescheduled_from_id' => $inst->id
                        ]);
                        $newInstallments[] = $newInst->toArray();
                    }
                }

                EmiRescheduleLog::create([
                    'sale_id' => $sale->id,
                    'action_type' => 'reschedule_' . $option,
                    'reason' => $reason,
                    'old_schedule_snapshot' => $oldSnapshot,
                    'new_schedule_snapshot' => $newInstallments,
                    'performed_by' => auth()->id() ?? 1,
                ]);
            }

            return $receipt;
        });

        return response()->json([
            'success' => true,
            'message' => 'Action recorded successfully.',
            'receipt' => $result,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────
    // D. OUTSTANDING — from Sales.remaining_balance
    // ──────────────────────────────────────────────────────────────────

    public function outstanding(Request $request): View
    {
        $projects = Project::orderBy('name')->get();

        $selectedProjectId = $request->filled('project_id')
            ? $request->input('project_id')
            : (string)($projects->first()?->id ?? '');

        $sales = Sale::with(['customer', 'project', 'unit.unitType', 'unit.floor', 'receipts'])
            ->where('status', 'active')
            ->where('remaining_balance', '>', 0)
            ->when(!empty($selectedProjectId), fn($q) => $q->where('project_id', $selectedProjectId))
            ->orderBy('created_at', 'desc')
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

        return view('emi-collections.outstanding', compact('brackets', 'totals', 'projects', 'selectedProjectId'));
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
        if ($sale->status === 'active') {
            CustomerInstallment::allocatePaymentStatusForSale($sale->id);
        }

        $sale->load(['customer', 'project', 'unit.floor', 'unit.unitType', 'saleUnits.unit.floor', 'saleUnits.unit.unitType', 'receipts.bank']);

        $archiveSnapshot = null;
        $archivedStatuses = ['exchanged', 'cancelled', 'returned', 'resale'];
        if (in_array($sale->status, $archivedStatuses)) {
            $statusLog = \App\Models\SaleStatusLog::where('sale_id', $sale->id)
                ->whereIn('event_type', $archivedStatuses)
                ->whereNotNull('snapshot_data')
                ->latest()
                ->first();

            if (!$statusLog) {
                $statusLog = \App\Models\SaleStatusLog::where('sale_id', $sale->id)
                    ->where('event_type', 'created')
                    ->whereNotNull('snapshot_data')
                    ->first();
            }

            $snapshotReceipts = collect();
            if ($statusLog && isset($statusLog->snapshot_data)) {
                $archiveSnapshot = $statusLog->snapshot_data;
                if (isset($statusLog->snapshot_data['receipts'])) {
                    foreach ($statusLog->snapshot_data['receipts'] as $rData) {
                        $r = new Receipt();
                        $r->forceFill([
                            'id'           => $rData['id'] ?? null,
                            'sale_id'      => $rData['sale_id'] ?? $sale->id,
                            'customer_id'  => $rData['customer_id'] ?? $sale->customer_id,
                            'project_id'   => $rData['project_id'] ?? $sale->project_id,
                            'unit_id'      => $rData['unit_id'] ?? $sale->unit_id,
                            'receipt_date' => isset($rData['receipt_date']) ? Carbon::parse($rData['receipt_date']) : null,
                            'amount'       => $rData['amount'] ?? 0,
                            'payment_mode' => $rData['payment_mode'] ?? '',
                            'reference_no' => $rData['reference_no'] ?? null,
                            'bank_id'      => $rData['bank_id'] ?? null,
                            'partner_id'   => $rData['partner_id'] ?? null,
                        ]);
                        $r->exists = true;
                        $snapshotReceipts->push($r);
                    }
                }
            } else {
                $archiveSnapshot = [
                    'old_sale' => [
                        'id' => $sale->id,
                    ],
                ];
            }

            if ($archiveSnapshot && isset($archiveSnapshot['old_sale']['id']) && $archiveSnapshot['old_sale']['id'] !== $sale->id) {
                $simulated = $this->simulateInstallmentsForSale($sale);
                $archiveSnapshot['installments'] = $simulated->toArray();
            } elseif ($archiveSnapshot && !isset($archiveSnapshot['installments'])) {
                $simulated = $this->simulateInstallmentsForSale($sale);
                $archiveSnapshot['installments'] = $simulated->toArray();
            }

            if ($sale->receipts->isEmpty() && $snapshotReceipts->isNotEmpty()) {
                $sale->setRelation('receipts', $snapshotReceipts);
            }
        }

        $installments = CustomerInstallment::where('sale_id', $sale->id)
            ->orderBy('installment_no')
            ->get();

        $receipts = $sale->receipts->sortBy('id')->values();
        $receiptQueue = [];
        foreach ($receipts as $r) {
            $receiptQueue[] = [
                'id' => $r->id,
                'amount' => (float)$r->amount,
                'original_amount' => (float)$r->amount,
            ];
        }

        $receiptIndex = 0;
        $installmentReceipts = [];

        foreach ($installments as $inst) {
            $instAmount = round((float)$inst->amount, 2);
            $needed = $instAmount;
            $installmentReceipts[$inst->id] = [];

            while ($needed > 0 && $receiptIndex < count($receiptQueue)) {
                $r = &$receiptQueue[$receiptIndex];
                $avail = round($r['amount'], 2);
                if ($avail > 0) {
                    $allocated = min($needed, $avail);
                    $r['amount'] = round($r['amount'] - $allocated, 2);
                    $needed = round($needed - $allocated, 2);
                    
                    $found = false;
                    foreach ($installmentReceipts[$inst->id] as $item) {
                        if ($item['id'] === $r['id']) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $installmentReceipts[$inst->id][] = [
                            'id' => $r['id'],
                            'amount' => $r['original_amount'],
                        ];
                    }
                }
                if (round($r['amount'], 2) <= 0.01) {
                    $receiptIndex++;
                }
            }
        }

        $ledger = collect();
        $runningBalance = 0;
        $opening = 0;

        foreach ($installments as $inst) {
            $instAmount = round((float)$inst->amount, 2);
            $paidAmount = round((float)($inst->paid_amount ?? 0), 2);
            $runningBalance += ($instAmount - $paidAmount);

            $ledger->push([
                'id'              => $inst->id,
                'date'            => $inst->due_date?->format('d M Y') ?? '—',
                'description'     => $inst->label . ' (Due)',
                'debit'           => $instAmount,
                'credit'          => $paidAmount,
                'running_balance' => $runningBalance,
                'type'            => 'installment',
                'status'          => $inst->status,
                'sort_date'       => $inst->due_date,
                'receipts'        => $installmentReceipts[$inst->id] ?? [],
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
                'status'          => in_array($sale->status, $archivedStatuses) ? $sale->status : 'pending',
                'sort_date'       => Carbon::parse($sale->sale_date),
            ]);
        }

        $ledger = $ledger->sort(function ($a, $b) {
            $dateA = $a['sort_date'] ? Carbon::parse($a['sort_date'])->format('Y-m-d') : '9999-12-31';
            $dateB = $b['sort_date'] ? Carbon::parse($b['sort_date'])->format('Y-m-d') : '9999-12-31';
            if ($dateA === $dateB) {
                // If both dates are identical, prioritize paid items first, then installment ID
                $statusOrder = ['paid' => 1, 'partial' => 2, 'pending' => 3, 'overdue' => 4];
                $sA = $statusOrder[$a['status']] ?? 5;
                $sB = $statusOrder[$b['status']] ?? 5;
                if ($sA !== $sB) {
                    return $sA <=> $sB;
                }
                return ($a['id'] ?? 0) <=> ($b['id'] ?? 0);
            }
            return strcmp($dateA, $dateB);
        })->values();

        // Running balance
        $runningBalance = 0;
        $ledger = $ledger->map(function ($row) use (&$runningBalance) {
            $runningBalance += $row['debit'] - $row['credit'];
            $row['running_balance'] = $runningBalance;
            return $row;
        });

        $totalDebits    = $ledger->sum('debit');
        $totalCredits   = in_array($sale->status, $archivedStatuses) ? $sale->receipts->filter(fn($r) => !$r->partner_id)->sum('amount') : $ledger->sum('credit');
        $closingBalance = $installments->isNotEmpty()
            ? max(0, round($totalDebits - $totalCredits, 2))
            : (float)$sale->remaining_balance;
        $allSales       = Sale::with(['customer', 'unit'])->get();
        $banks          = \App\Models\Bank::where('status', 'active')->orderBy('bank_name')->get();
        $paymentModes   = PaymentMode::where('status', 'active')->orderBy('name')->get();
        $restructureLogs = \App\Models\SaleStatusLog::with('performedBy')
            ->where('sale_id', $sale->id)
            ->whereIn('event_type', ['emi_restructure', 'rate_discount_restructure', 'rate_adjustment'])
            ->latest()
            ->get();

        // Retrieve the latest restructured rate from logs (units and sales tables remain unmodified)
        $latestRateLog = $restructureLogs->first(function ($l) {
            return !empty($l->snapshot_data['new_rate']) && (float)$l->snapshot_data['new_rate'] > 0;
        });
        $loggedRate = $latestRateLog ? (float)$latestRateLog->snapshot_data['new_rate'] : null;

        return view('emi-collections.ledger', compact(
            'sale',
            'ledger',
            'opening',
            'totalDebits',
            'totalCredits',
            'closingBalance',
            'allSales',
            'installments',
            'banks',
            'paymentModes',
            'archiveSnapshot',
            'restructureLogs',
            'loggedRate'
        ));
    }

    // ──────────────────────────────────────────────────────────────────
    // F2. LEDGER RATE ADJUSTMENT  (does NOT touch hindustan_units table)
    // ──────────────────────────────────────────────────────────────────

    public function applyLedgerRate(Request $request, Sale $sale): JsonResponse
    {
        $request->validate([
            'new_rate' => ['required', 'numeric', 'min:0.01'],
        ]);

        $newRate    = (float) $request->new_rate;
        $builtUpArea = (float) ($sale->unit?->built_up_area ?? 0);

        if ($builtUpArea <= 0) {
            return response()->json(['error' => 'Unit Built Up Area is not set — cannot recalculate.'], 422);
        }

        $oldRate         = (float) ($sale->rate_per_sqft ?? 0);
        $newBaseAmount   = round($builtUpArea * $newRate, 2);

        // Recalculate GST on new base amount using sale's actual effective GST rate
        $baseForGst    = (float) ($sale->sale_amount ?: $sale->base_amount);
        $gstAmtForGst  = (float) $sale->gst_amount;
        $gstPct        = ($baseForGst > 0 && $gstAmtForGst > 0)
            ? round(($gstAmtForGst / $baseForGst) * 100, 4)
            : (float) ($sale->gst_percentage ?? 0);
        $newGstAmount  = round($newBaseAmount * $gstPct / 100, 2);
        $newTotal      = round($newBaseAmount + $newGstAmount, 2);

        // Paid so far (sum of receipts)
        $totalPaid     = round((float) $sale->receipts()->sum('amount'), 2);
        $newRemaining  = max(0, round($newTotal - $totalPaid, 2));

        DB::transaction(function () use ($sale, $newRate, $newBaseAmount, $newGstAmount, $newTotal, $newRemaining, $oldRate) {
            $sale->update([
                'rate_per_sqft'    => $newRate,
                'sale_amount'      => $newBaseAmount,
                'base_amount'      => $newBaseAmount,
                'gst_amount'       => $newGstAmount,
                'total_amount'     => $newTotal,
                'remaining_balance'=> $newRemaining,
            ]);

            \App\Models\SaleStatusLog::create([
                'sale_id'       => $sale->id,
                'event_type'    => 'rate_adjustment',
                'performed_by'  => auth()->id(),
                'snapshot_data' => [
                    'old_rate'       => $oldRate,
                    'new_rate'       => $newRate,
                    'built_up_area'  => $sale->unit?->built_up_area,
                    'new_base'       => $newBaseAmount,
                    'new_total'      => $newTotal,
                    'new_remaining'  => $newRemaining,
                    'note'           => 'Rate adjusted via Ledger Adjustments panel. Unit base rate NOT changed.',
                ],
            ]);
        });

        return response()->json([
            'success'       => true,
            'new_remaining' => $newRemaining,
            'new_total'     => $newTotal,
            'log' => [
                'date'     => now()->format('d M Y'),
                'old_rate' => $oldRate,
                'new_rate' => $newRate,
            ],
        ]);
    }

    // ──────────────────────────────────────────────────────────────────
    // F3. LEDGER DISCOUNT APPLICATION
    // ──────────────────────────────────────────────────────────────────

    public function applyLedgerDiscount(Request $request, Sale $sale): JsonResponse
    {
        $request->validate([
            'discount_amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $discountAmt      = (float) $request->discount_amount;
        $currentRemaining = (float) $sale->remaining_balance;
        $currentDiscount  = (float) ($sale->final_discount ?? 0);

        if ($discountAmt > $currentRemaining) {
            return response()->json(['error' => 'Discount amount (₹' . number_format($discountAmt, 2) . ') exceeds current Remaining Balance (₹' . number_format($currentRemaining, 2) . ').'], 422);
        }

        $newRemaining    = max(0, round($currentRemaining - $discountAmt, 2));
        $newTotalDiscount = round($currentDiscount + $discountAmt, 2);

        DB::transaction(function () use ($sale, $discountAmt, $newRemaining, $newTotalDiscount, $currentRemaining, $currentDiscount) {
            $sale->update([
                'final_discount'    => $newTotalDiscount,
                'remaining_balance' => $newRemaining,
            ]);

            \App\Models\SaleStatusLog::create([
                'sale_id'       => $sale->id,
                'event_type'    => 'discount_applied',
                'performed_by'  => auth()->id(),
                'snapshot_data' => [
                    'discount_amount'     => $discountAmt,
                    'previous_discount'   => $currentDiscount,
                    'total_discount'      => $newTotalDiscount,
                    'before_remaining'    => $currentRemaining,
                    'after_remaining'     => $newRemaining,
                    'note'                => 'Discount applied via Ledger Adjustments panel.',
                ],
            ]);
        });

        return response()->json([
            'success'       => true,
            'new_remaining' => $newRemaining,
            'log' => [
                'date'   => now()->format('d M Y'),
                'amount' => $discountAmt,
            ],
        ]);
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
            'installments'                  => ['required', 'array'],
            'installments.*.installment_no' => ['required', 'integer'],
            'installments.*.label'          => ['required', 'string', 'max:100'],
            'installments.*.due_date'       => ['required', 'date'],
            'installments.*.amount'         => ['required', 'numeric', 'min:0'],
            'installments.*.status'         => ['required', 'in:pending,paid,overdue,partial'],
            // Optional rate/discount adjustment block
            'adjustment.new_rate'           => ['sometimes', 'nullable', 'numeric', 'min:0.01'],
            'adjustment.built_up_area'      => ['sometimes', 'nullable', 'numeric', 'min:0.01'],
            'adjustment.discount_amount'    => ['sometimes', 'nullable', 'numeric', 'min:0'],
        ]);

        $installmentsData = $request->input('installments');
        $adjustment       = $request->input('adjustment', []);

        // ── Compute effective total amount ────────────────────────────
        // When rate/discount are adjusted, we validate the schedule sum
        // against the EFFECTIVE total (not the original sale->total_amount).
        // The sale record (NOT hindustan_units) will be updated in the transaction.
        $currentScheduleTotal = round((float) CustomerInstallment::where('sale_id', $sale->id)->sum('amount'), 2);
        $initialTotal = $currentScheduleTotal > 0 ? $currentScheduleTotal : (float) $sale->total_amount;
        $effectiveTotal = $initialTotal;

        $hasRateAdj     = !empty($adjustment['new_rate'])        && (float)$adjustment['new_rate']        > 0;
        $hasDiscountAdj = !empty($adjustment['discount_amount']) && (float)$adjustment['discount_amount'] > 0;
        $hasAnyAdj      = $hasRateAdj || $hasDiscountAdj;

        if ($hasAnyAdj) {
            $baseForGst = (float) ($sale->sale_amount ?: $sale->base_amount);
            $gstAmtForGst = (float) $sale->gst_amount;
            $gstPct = ($baseForGst > 0 && $gstAmtForGst > 0)
                ? round(($gstAmtForGst / $baseForGst) * 100, 4)
                : (float) ($sale->gst_percentage ?? 0);
            $lastEdited = $adjustment['last_edited'] ?? ($hasRateAdj ? 'rate' : 'discount');

            if ($lastEdited === 'discount' && $hasDiscountAdj) {
                $discountAmt    = round((float)$adjustment['discount_amount'], 2);
                $effectiveTotal = max(0, round($initialTotal - $discountAmt, 2));
            } elseif ($hasRateAdj) {
                $newRate = round((float)$adjustment['new_rate'], 2);
                $area    = !empty($adjustment['built_up_area'])
                           ? round((float)$adjustment['built_up_area'], 2)
                           : (float)($sale->unit?->built_up_area ?? 0);
                if ($area > 0) {
                    $newBase        = round($area * $newRate, 2);
                    $newGst         = round($newBase * $gstPct / 100, 2);
                    $effectiveTotal = round($newBase + $newGst, 2);
                }
            } elseif ($hasDiscountAdj) {
                $discountAmt    = round((float)$adjustment['discount_amount'], 2);
                $effectiveTotal = max(0, round($initialTotal - $discountAmt, 2));
            }
        } elseif (!empty($adjustment['effective_total']) && (float)$adjustment['effective_total'] > 0) {
            $effectiveTotal = round((float)$adjustment['effective_total'], 2);
        }

        // ── Validate installment sum ──────────────────────────────────
        $sum = round(collect($installmentsData)->sum('amount'), 2);
        if (abs($sum - $effectiveTotal) > 0.01) {
            return response()->json([
                'error' => 'The sum of all installments (₹' . number_format($sum, 2) .
                           ') must equal the effective total amount (₹' . number_format($effectiveTotal, 2) . ').'
            ], 422);
        }

        DB::transaction(function () use ($sale, $installmentsData, $adjustment, $effectiveTotal, $hasAnyAdj, $hasRateAdj, $hasDiscountAdj) {
            $currentInsts = CustomerInstallment::where('sale_id', $sale->id)->orderBy('installment_no')->get();

            // ── 1. Audit log & history snapshot ────────────────────────────
            // Note: The `sales` table and `hindustan_units` table are intentionally
            // NOT modified, preserving original agreement data intact in the database.
            // The edited schedule data is stored in `customer_installments`.
            if ($currentInsts->isNotEmpty() || $hasAnyAdj) {
                $reasonParts = [];
                if ($hasDiscountAdj) {
                    $reasonParts[] = 'Discount: ₹' . number_format($adjustment['discount_amount'], 2);
                }
                if ($hasRateAdj) {
                    $reasonParts[] = 'Rate: ₹' . number_format($adjustment['new_rate'], 2) . '/sqft';
                }
                $reasonText = 'EMI schedule restructured' . (!empty($reasonParts) ? ' (' . implode(', ', $reasonParts) . ')' : '') . ' on ' . now()->format('d M Y, h:i A');

                \App\Models\SaleStatusLog::create([
                    'sale_id'       => $sale->id,
                    'from_status'   => $sale->status,
                    'to_status'     => $sale->status,
                    'event_type'    => 'emi_restructure',
                    'reason'        => $reasonText,
                    'performed_by'  => auth()->id(),
                    'snapshot_data' => [
                        'restructured_at' => now()->toIso8601String(),
                        'old_rate'        => $adjustment['old_rate']        ?? (float)($sale->rate_per_sqft ?? 0),
                        'new_rate'        => $adjustment['new_rate']        ?? null,
                        'built_up_area'   => $adjustment['built_up_area']   ?? (float)($sale->unit?->built_up_area ?? 0),
                        'discount_amount' => $adjustment['discount_amount'] ?? null,
                        'effective_total' => $effectiveTotal,
                        'original_total'  => (float)$sale->total_amount,
                        'note'            => 'Base sales and units tables preserved intact without mutation. Only custom installments schedule updated.',
                        'installments'    => $currentInsts->map(function ($inst) {
                            return [
                                'id'             => $inst->id,
                                'installment_no' => $inst->installment_no,
                                'label'          => $inst->label,
                                'due_date'       => $inst->due_date ? $inst->due_date->format('Y-m-d') : null,
                                'amount'         => (float)$inst->amount,
                                'paid_amount'    => (float)($inst->paid_amount ?? 0),
                                'status'         => $inst->status,
                            ];
                        })->values()->toArray(),
                    ],
                ]);
            }

            // ── 3. Upsert / delete installment rows ───────────────────────
            $currentInstsKeyed = $currentInsts->keyBy('id');
            $keepIds = [];
            foreach ($installmentsData as $inst) {
                if (isset($inst['id']) && $currentInstsKeyed->has($inst['id'])) {
                    $row = $currentInstsKeyed->get($inst['id']);
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

            // Re-allocate payments across updated installments
            CustomerInstallment::allocatePaymentStatusForSale($sale->id);

            // Sync total EMI count onto Sale model
            $newEmiCount = CustomerInstallment::where('sale_id', $sale->id)
                ->where('installment_no', '>', 0)
                ->where('label', 'not like', '%down payment%')
                ->count();
            if ($newEmiCount > 0) {
                $sale->update(['emi_installment_count' => $newEmiCount]);
            }
        });

        return response()->json(['success' => true]);
    }

    private function simulateInstallmentsForSale(Sale $sale)
    {
        $installments = collect();
        if ($sale->payment_plan !== 'emi') {
            return $installments;
        }

        $agreementDate = Carbon::parse($sale->agreement_date ?? $sale->sale_date ?? now());
        $carriedForward = $sale->receipts
            ->filter(function($r) use ($agreementDate) {
                return Carbon::parse($r->receipt_date)->lte($agreementDate);
            })
            ->sum('amount');
        
        $totalAmount = (float)$sale->total_amount;
        $downPayment = (float)$carriedForward;
        $remaining = $totalAmount - $downPayment;
        
        if ($downPayment > 0) {
            $installments->push([
                'installment_no' => 0,
                'label'          => 'Down Payment',
                'due_date'       => $agreementDate->toDateString(),
                'amount'         => $downPayment,
                'status'         => 'paid',
                'paid_amount'    => $downPayment,
                'schedule_type'  => 'fixed_emi',
            ]);
        }

        $numEmi = (int)$sale->emi_installment_count;
        if ($numEmi > 0) {
            $emiAmount = round($remaining / $numEmi, 2);
            $firstDateVal = $sale->first_installment_date ? Carbon::parse($sale->first_installment_date) : $agreementDate->copy()->addMonth();
            
            for ($i = 1; $i <= $numEmi; $i++) {
                $due = $firstDateVal->copy();
                if ($i > 1) {
                    if ($sale->emi_frequency === 'quarterly') {
                        $due->addMonths(($i - 1) * 3);
                    } else {
                        $due->addMonths($i - 1);
                    }
                }
                $amt = ($i === $numEmi)
                    ? round($remaining - ($emiAmount * ($numEmi - 1)), 2)
                    : $emiAmount;
                
                if ($amt > 0) {
                    $installments->push([
                        'installment_no' => $i,
                        'label'          => "EMI {$i}",
                        'due_date'       => $due->toDateString(),
                        'amount'         => $amt,
                        'status'         => 'pending',
                        'paid_amount'    => 0,
                        'schedule_type'  => 'fixed_emi',
                    ]);
                }
            }
        }

        $receipts = $sale->receipts->sortBy('id');
        // Convert installments collection to array of arrays to allow modification inside loop by reference
        $installmentsArray = $installments->toArray();
        foreach ($receipts as $r) {
            $amountToAllocate = (float)$r->amount;
            foreach ($installmentsArray as &$inst) {
                $instAmount = (float)$inst['amount'];
                $instPaid = (float)($inst['paid_amount'] ?? 0);
                $needed = $instAmount - $instPaid;
                
                if ($needed > 0 && $amountToAllocate > 0) {
                    $alloc = min($needed, $amountToAllocate);
                    $inst['paid_amount'] = round($instPaid + $alloc, 2);
                    $amountToAllocate = round($amountToAllocate - $alloc, 2);
                    
                    if (round($inst['paid_amount'], 2) >= round($instAmount, 2)) {
                        $inst['status'] = 'paid';
                    } else {
                        $inst['status'] = 'partial';
                    }
                }
            }
        }

        return collect($installmentsArray);
    }
}
