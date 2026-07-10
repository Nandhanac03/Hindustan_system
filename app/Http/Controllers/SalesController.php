<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleStatusLog;
use App\Models\Receipt;
use App\Models\Brokerage;
use App\Models\Broker;
use App\Models\Project;
use App\Models\Unit;
use App\Models\Customer;
use App\Models\Partner;
use App\Models\CustomerInstallment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class SalesController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $projectsList = Project::orderBy('name')->get();
        if (!$request->has('project_id') && !$request->filled('project_id') && $projectsList->isNotEmpty()) {
            $request->merge(['project_id' => (string)$projectsList->first()->id]);
        }

        $query = Sale::with(['project', 'unit', 'customer', 'broker', 'receipts']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('sale_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('sale_date', '<=', $request->date_to);
        }

        $sales = $query->orderByDesc('sale_date')->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['sales' => $sales]);
        }

        return view('sales.index', [
            'projects' => Project::orderBy('name')->get(),
            'customers' => Customer::orderBy('name')->get(),
            'brokers' => Broker::orderBy('name')->get(),
        ]);
    }

    public function availableUnits(int $projectId): JsonResponse
    {
        $units = Unit::where('project_id', $projectId)
            ->where('status', 'available')
            ->where('is_active', true)
            ->with('floor')
            ->get()
            ->map(function ($unit) {
                return [
                    'id' => $unit->id,
                    'door_no' => $unit->door_no,
                    'floor_name' => $unit->floor->name ?? '',
                    'built_up_area' => $unit->built_up_area,
                    'expected_rate_per_sqft' => $unit->expected_rate_per_sqft,
                    'expected_sale_amount' => $unit->expected_sale_amount,
                ];
            });

        return response()->json(['units' => $units]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_id'             => ['required', 'exists:projects,id'],
            'unit_id'                => ['required', 'exists:hindustan_units,id'],
            'customer_id'            => ['required', 'exists:customers,id'],
            'agreement_date'         => ['required', 'date'],
            'registration_date'      => ['nullable', 'date'],
            'rate_per_sqft'          => ['required', 'numeric', 'min:0'],
            'sale_amount'            => ['required', 'numeric', 'min:0'],
            'gst_type'               => ['required', Rule::in(['none', 'inclusive', 'exclusive'])],
            'broker_involved'        => ['nullable', 'boolean'],
            'broker_id'              => ['nullable', 'required_if:broker_involved,true', 'exists:brokers,id'],
            'brokerage_type'         => ['nullable', 'required_if:broker_involved,true', Rule::in(['percentage', 'fixed'])],
            'brokerage_value'        => ['nullable', 'required_if:broker_involved,true', 'numeric', 'min:0'],
            'brokerage_status'       => ['nullable', Rule::in(['pending', 'partial', 'paid'])],
            'initial_payment_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_mode'           => ['nullable', 'string'],
            'reference_no'           => ['nullable', 'string'],
            'bank_name'              => ['nullable', 'string'],
            'initial_payment_date'   => ['nullable', 'date'],
            'payment_plan'           => ['required', Rule::in(['lump_sum', 'emi'])],
            'emi_plan_type'          => ['nullable', 'string', Rule::in(['fixed-12', 'clp', 'fixed-36'])],
            'notes'                  => ['nullable', 'string'],
        ]);

        // GST math matches the reference implementation exactly:
        // exclusive -> entered amount is base, GST added on top (amount * 0.18)
        // inclusive -> entered amount already includes GST (amount * 18/118)
        $amount = $validated['sale_amount'];
        $gstAmount = 0.0;
        if ($validated['gst_type'] === 'exclusive') {
            $gstAmount = round($amount * 0.18, 2);
        } elseif ($validated['gst_type'] === 'inclusive') {
            $gstAmount = round($amount * 18 / 118, 2);
        }
        $baseAmount = $validated['gst_type'] === 'inclusive' ? round($amount - $gstAmount, 2) : $amount;
        $totalAmount = $validated['gst_type'] === 'exclusive' ? round($amount + $gstAmount, 2) : $amount;

        $brokerInvolved = (bool) ($validated['broker_involved'] ?? false);
        $initialPayment = $validated['initial_payment_amount'] ?? 0;

        $sale = Sale::create([
            'sale_number'       => 'SL-' . strtoupper(uniqid()),
            'project_id'        => $validated['project_id'],
            'unit_id'           => $validated['unit_id'],
            'customer_id'       => $validated['customer_id'],
            'broker_id'         => $brokerInvolved ? ($validated['broker_id'] ?? null) : null,
            'rate_per_sqft'     => $validated['rate_per_sqft'],
            'sale_amount'       => $amount,
            'gst_applicable'    => $validated['gst_type'] !== 'none',
            'gst_type'          => $validated['gst_type'],
            'gst_percentage'    => $validated['gst_type'] !== 'none' ? 18 : null,
            'gst_amount'        => $gstAmount,
            'base_amount'       => $baseAmount,
            'total_amount'      => $totalAmount,
            'sale_date'         => $validated['agreement_date'],
            'agreement_date'    => $validated['agreement_date'],
            'registration_date' => $validated['registration_date'] ?? null,
            'status'            => 'active',
            'broker_involved'   => $brokerInvolved,
            'payment_plan'      => $validated['payment_plan'],
            'emi_plan_type'     => $validated['emi_plan_type'] ?? 'fixed-12',
            'remaining_balance' => round($totalAmount - $initialPayment, 2),
            'notes'             => $validated['notes'] ?? null,
            'created_by'        => auth()->id(),
        ]);

        // Mark the unit as sold and update pricing details
        $unit = Unit::findOrFail($validated['unit_id']);
        $unitDifference = (float)$unit->expected_sale_amount - (float)$validated['sale_amount'];
        $unit->update([
            'status'             => 'sold',
            'sale_rate_per_sqft' => (float)$validated['rate_per_sqft'],
            'sale_amount'        => (float)$validated['sale_amount'],
            'difference'         => $unitDifference,
            'gst_behavior'       => $validated['gst_type'],
            'gst_amount'         => $gstAmount,
        ]);

        SaleStatusLog::create([
            'sale_id'      => $sale->id,
            'from_status'  => null,
            'to_status'    => 'active',
            'event_type'   => 'created',
            'performed_by' => auth()->id(),
        ]);

        // Create the initial payment as a receipt, so it's tracked like any other payment
        if ($initialPayment > 0) {
            Receipt::create([
                'sale_id'      => $sale->id,
                'customer_id'  => $validated['customer_id'],
                'project_id'   => $validated['project_id'],
                'unit_id'      => $validated['unit_id'],
                'receipt_date' => $validated['initial_payment_date'] ?? $validated['agreement_date'],
                'amount'       => $initialPayment,
                'payment_mode' => $validated['payment_mode'] ?? 'cash',
                'reference_no' => $validated['reference_no'] ?? null,
                'bank_name'    => $validated['bank_name'] ?? null,
                'remarks'      => 'Initial payment at sale creation',
                'created_by'   => auth()->id(),
            ]);
        }

        // Create the brokerage record if a broker is involved
        if ($brokerInvolved && $validated['brokerage_value'] > 0) {
            $commissionAmount = $validated['brokerage_type'] === 'percentage'
                ? round($totalAmount * ($validated['brokerage_value'] / 100), 2)
                : round($validated['brokerage_value'], 2);

            Brokerage::create([
                'sale_id'            => $sale->id,
                'broker_id'          => $validated['broker_id'],
                'commission_type'    => $validated['brokerage_type'],
                'commission_percent' => $validated['brokerage_type'] === 'percentage' ? $validated['brokerage_value'] : null,
                'commission_amount'  => $commissionAmount,
                'paid_amount'        => 0,
                'status'             => $validated['brokerage_status'] ?? 'pending',
                'remarks'            => 'Auto-created at sale',
            ]);
        }

        $this->syncDefaultEmiSchedule($sale);

        return response()->json(['sale' => $sale->load(['receipts', 'brokerage'])], 201);
    }

    public function show(int $id): JsonResponse
    {
        $sale = Sale::with(['project', 'unit.floor', 'customer', 'broker', 'statusLogs', 'receipts', 'brokerage'])->findOrFail($id);
        $sale->status_logs = $sale->statusLogs;

        return response()->json(['sale' => $sale]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $sale = Sale::findOrFail($id);

        $validated = $request->validate([
            'sale_amount' => ['required', 'numeric', 'min:0'],
            'sale_date'   => ['required', 'date'],
            'gst_type'    => ['required', Rule::in(['none', 'inclusive', 'exclusive'])],
            'notes'       => ['nullable', 'string'],
            'rate_per_sqft' => ['nullable', 'numeric'],
            'broker_involved' => ['nullable', 'boolean'],
            'broker_id' => ['nullable', 'exists:brokers,id'],
            'brokerage_type' => ['nullable', Rule::in(['percentage', 'fixed'])],
            'brokerage_value' => ['nullable', 'numeric'],
            'brokerage_amount' => ['nullable', 'numeric'],
            'brokerage_status' => ['nullable', Rule::in(['pending', 'paid'])],
            'registration_date' => ['nullable', 'date'],
            'payment_plan'      => ['nullable', Rule::in(['lump_sum', 'emi'])],
            'emi_plan_type'     => ['nullable', 'string', Rule::in(['fixed-12', 'clp', 'fixed-36'])],
            'initial_payment_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_mode' => ['nullable', 'string'],
            'reference_no' => ['nullable', 'string'],
            'bank_name' => ['nullable', 'string'],
            'initial_payment_date' => ['nullable', 'date'],
        ]);

        $amount = $validated['sale_amount'];
        $gstAmount = 0.0;
        if ($validated['gst_type'] === 'exclusive') {
            $gstAmount = round($amount * 0.18, 2);
        } elseif ($validated['gst_type'] === 'inclusive') {
            $gstAmount = round($amount * 18 / 118, 2);
        }
        $baseAmount = $validated['gst_type'] === 'inclusive' ? round($amount - $gstAmount, 2) : $amount;
        $totalAmount = $validated['gst_type'] === 'exclusive' ? round($amount + $gstAmount, 2) : $amount;

        $sale->update([
            'sale_amount'    => $validated['sale_amount'],
            'sale_date'      => $validated['sale_date'],
            'agreement_date' => $validated['sale_date'],
            'registration_date' => $validated['registration_date'] ?? null,
            'rate_per_sqft'   => $validated['rate_per_sqft'] ?? $sale->rate_per_sqft,
            'gst_applicable'    => $validated['gst_type'] !== 'none',
            'gst_type'          => $validated['gst_type'],
            'gst_percentage'    => $validated['gst_type'] !== 'none' ? 18 : null,
            'gst_amount'        => $gstAmount,
            'base_amount'       => $baseAmount,
            'total_amount'      => $totalAmount,
            'broker_involved'   => !empty($validated['broker_involved']),
            'broker_id'         => (!empty($validated['broker_involved']) && !empty($validated['broker_id'])) ? $validated['broker_id'] : null,
            'payment_plan'      => $validated['payment_plan'] ?? $sale->payment_plan,
            'emi_plan_type'     => $validated['emi_plan_type'] ?? $sale->emi_plan_type ?? 'fixed-12',
            'notes'             => $validated['notes'] ?? null,
        ]);

        // Update Unit pricing details since Sale updated
        if ($sale->unit) {
            $unitDifference = (float)$sale->unit->expected_sale_amount - (float)$validated['sale_amount'];
            $sale->unit->update([
                'sale_rate_per_sqft' => $validated['rate_per_sqft'] ?? $sale->unit->sale_rate_per_sqft,
                'sale_amount'        => (float)$validated['sale_amount'],
                'difference'         => $unitDifference,
                'gst_behavior'       => $validated['gst_type'],
                'gst_amount'         => $gstAmount,
            ]);
        }

        // Manage Brokerage
        if (!empty($validated['broker_involved']) && !empty($validated['broker_id'])) {
            $commissionAmount = $validated['brokerage_type'] === 'percentage'
                ? round($totalAmount * ($validated['brokerage_value'] / 100), 2)
                : round($validated['brokerage_value'] ?? 0, 2);

            $sale->brokerage()->updateOrCreate(
                ['sale_id' => $sale->id],
                [
                    'broker_id'          => $validated['broker_id'],
                    'commission_type'    => $validated['brokerage_type'] ?? 'fixed',
                    'commission_percent' => ($validated['brokerage_type'] ?? 'fixed') === 'percentage' ? $validated['brokerage_value'] : null,
                    'commission_amount'  => $commissionAmount,
                    'paid_amount'        => 0,
                    'status'             => $validated['brokerage_status'] ?? 'pending',
                    'remarks'            => 'Updated at sale edit',
                ]
            );
        } else {
            $sale->brokerage()->delete();
        }

        // Manage Initial Payment Receipt
        $initialPayment = (float)($validated['initial_payment_amount'] ?? 0);
        $receipt = $sale->receipts()->where('remarks', 'Initial payment at sale creation')->first();

        if ($initialPayment > 0) {
            if ($receipt) {
                $receipt->update([
                    'amount'       => $initialPayment,
                    'payment_mode' => $validated['payment_mode'] ?? 'Cash',
                    'reference_no' => $validated['reference_no'] ?? null,
                    'bank_name'    => $validated['bank_name'] ?? null,
                    'receipt_date' => $validated['initial_payment_date'] ?? $validated['sale_date'],
                ]);
            } else {
                $sale->receipts()->create([
                    'customer_id'  => $sale->customer_id,
                    'project_id'   => $sale->project_id,
                    'unit_id'      => $sale->unit_id,
                    'receipt_date' => $validated['initial_payment_date'] ?? $validated['sale_date'],
                    'amount'       => $initialPayment,
                    'payment_mode' => $validated['payment_mode'] ?? 'Cash',
                    'reference_no' => $validated['reference_no'] ?? null,
                    'bank_name'    => $validated['bank_name'] ?? null,
                    'remarks'      => 'Initial payment at sale creation',
                    'created_by'   => auth()->id(),
                ]);
            }
        } else {
            if ($receipt) {
                $receipt->delete();
            }
        }

        // Recalculate remaining balance
        $sale->update([
            'remaining_balance' => round($totalAmount - $sale->receipts()->sum('amount'), 2),
        ]);

        $this->syncDefaultEmiSchedule($sale);

        return response()->json(['sale' => $sale->load(['receipts', 'brokerage', 'unit.floor', 'project', 'customer'])]);
    }

    /**
     * Record an additional payment against an existing sale (e.g. an EMI
     * installment), and recompute the remaining balance from the full
     * receipts history rather than trusting a client-supplied balance.
     */
    public function addReceipt(Request $request, int $id): JsonResponse
    {
        $sale = Sale::findOrFail($id);

        $validated = $request->validate([
            'amount'       => ['required', 'numeric', 'min:0.01'],
            'receipt_date' => ['required', 'date'],
            'payment_mode' => ['required', 'string'],
            'reference_no' => ['nullable', 'string'],
            'bank_name'    => ['nullable', 'string'],
            'remarks'      => ['nullable', 'string'],
        ]);

        $receipt = Receipt::create([
            'sale_id'      => $sale->id,
            'customer_id'  => $sale->customer_id,
            'project_id'   => $sale->project_id,
            'unit_id'      => $sale->unit_id,
            'receipt_date' => $validated['receipt_date'],
            'amount'       => $validated['amount'],
            'payment_mode' => $validated['payment_mode'],
            'reference_no' => $validated['reference_no'] ?? null,
            'bank_name'    => $validated['bank_name'] ?? null,
            'remarks'      => $validated['remarks'] ?? null,
            'created_by'   => auth()->id(),
        ]);

        $sale->update([
            'remaining_balance' => round($sale->total_amount - $sale->receipts()->sum('amount'), 2),
        ]);

        return response()->json(['receipt' => $receipt, 'sale' => $sale->fresh(['receipts', 'brokerage'])], 201);
    }

    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['cancelled', 'returned', 'exchanged', 'resale'])],
            'reason' => ['required', 'string'],
            'cancellation_fee' => ['nullable', 'numeric', 'min:0'],
            'refund_amount' => ['nullable', 'numeric', 'min:0'],
            'revert_unsold' => ['nullable', 'boolean'],
            'new_unit_id' => ['nullable', 'exists:hindustan_units,id'],
            'carry_forward' => ['nullable', 'boolean'],
        ]);

        $sale = Sale::findOrFail($id);
        $fromStatus = $sale->status;

        // Validation Rule: Before processing a Sales Return, the system must verify that the sale status is Cancelled.
        if ($validated['status'] === 'returned' && $fromStatus !== 'cancelled') {
            return response()->json([
                'message' => 'Sales Return can only be processed for cancelled sales.',
                'errors' => [
                    'status' => ['Sales Return can only be processed for cancelled sales.']
                ]
            ], 422);
        }

        if ($validated['status'] === 'exchanged') {
            $newUnitId = $validated['new_unit_id'];
            if (!$newUnitId) {
                return response()->json([
                    'message' => 'Target Unit is required for unit exchange.',
                    'errors' => ['new_unit_id' => ['Target Unit is required for unit exchange.']]
                ], 422);
            }

            $newUnit = Unit::findOrFail($newUnitId);
            if ($newUnit->status !== 'available') {
                return response()->json([
                    'message' => 'Target Unit is not available for exchange.',
                    'errors' => ['new_unit_id' => ['Target Unit is not available for exchange.']]
                ], 422);
            }

            // 1. Mark old sale as exchanged, free up old unit
            $sale->update([
                'status' => 'exchanged',
                'cancellation_reason' => $validated['reason'],
                'cancelled_at' => now(),
            ]);
            Unit::where('id', $sale->unit_id)->update([
                'status'             => 'available',
                'sale_rate_per_sqft' => null,
                'sale_amount'        => null,
                'difference'         => null,
                'gst_behavior'       => 'none',
                'gst_amount'         => 0.00,
            ]);

            // 2. Create the new active sale
            $newAmount = (float)$newUnit->expected_sale_amount;
            $newRate = (float)$newUnit->expected_rate_per_sqft;
            $gstType = $sale->gst_type ?? 'none';
            $gstAmount = 0.0;
            if ($gstType === 'exclusive') {
                $gstAmount = round($newAmount * 0.18, 2);
            } elseif ($gstType === 'inclusive') {
                $gstAmount = round($newAmount * 18 / 118, 2);
            }
            $baseAmount = $gstType === 'inclusive' ? round($newAmount - $gstAmount, 2) : $newAmount;
            $totalAmount = $gstType === 'exclusive' ? round($newAmount + $gstAmount, 2) : $newAmount;

            $newSale = Sale::create([
                'sale_number'       => 'SL-' . strtoupper(uniqid()),
                'project_id'        => $newUnit->project_id,
                'unit_id'           => $newUnit->id,
                'customer_id'       => $sale->customer_id,
                'broker_id'         => $sale->broker_id,
                'rate_per_sqft'     => $newRate,
                'sale_amount'       => $newAmount,
                'gst_applicable'    => $gstType !== 'none',
                'gst_type'          => $gstType,
                'gst_percentage'    => $gstType !== 'none' ? 18 : null,
                'gst_amount'        => $gstAmount,
                'base_amount'       => $baseAmount,
                'total_amount'      => $totalAmount,
                'sale_date'         => now(),
                'agreement_date'    => now(),
                'status'            => 'active',
                'broker_involved'   => $sale->broker_involved,
                'payment_plan'      => $sale->payment_plan ?? 'lump_sum',
                'remaining_balance' => $totalAmount,
                'notes'             => 'Exchanged from sale ' . $sale->sale_number . '. ' . $validated['reason'],
                'created_by'        => auth()->id(),
            ]);

            // 3. Re-associate receipts if carry_forward is checked
            if (!empty($validated['carry_forward'])) {
                Receipt::where('sale_id', $sale->id)->update([
                    'sale_id' => $newSale->id,
                    'project_id' => $newUnit->project_id,
                    'unit_id' => $newUnit->id,
                ]);

                // Recalculate remaining balances
                $sale->update(['remaining_balance' => $sale->total_amount - $sale->receipts()->sum('amount')]);
                $newSale->update(['remaining_balance' => $totalAmount - $newSale->receipts()->sum('amount')]);
            }

            // 4. Mark new unit as sold and update pricing details
            $newUnitDifference = (float)$newUnit->expected_sale_amount - $newAmount;
            $newUnit->update([
                'status'             => 'sold',
                'sale_rate_per_sqft' => $newRate,
                'sale_amount'        => $newAmount,
                'difference'         => $newUnitDifference,
                'gst_behavior'       => $gstType,
                'gst_amount'         => $gstAmount,
            ]);

            // 5. Log status changes
            SaleStatusLog::create([
                'sale_id'      => $sale->id,
                'from_status'  => $fromStatus,
                'to_status'    => 'exchanged',
                'event_type'   => 'exchanged',
                'reason'       => $validated['reason'],
                'performed_by' => auth()->id(),
            ]);

            SaleStatusLog::create([
                'sale_id'      => $newSale->id,
                'from_status'  => null,
                'to_status'    => 'active',
                'event_type'   => 'created',
                'reason'       => 'Created via unit exchange from sale ' . $sale->sale_number,
                'performed_by' => auth()->id(),
            ]);

            \App\Models\CustomerInstallment::where('sale_id', $sale->id)->delete();
            $this->syncDefaultEmiSchedule($newSale);

            return response()->json(['sale' => $newSale->load(['receipts', 'brokerage', 'unit.floor', 'project', 'customer'])]);
        }

        $sale->update([
            'status'               => $validated['status'],
            'cancellation_reason'  => in_array($validated['status'], ['cancelled', 'returned']) ? $validated['reason'] : $sale->cancellation_reason,
            'cancelled_at'         => in_array($validated['status'], ['cancelled', 'returned']) ? now() : $sale->cancelled_at,
            'is_resale'            => $validated['status'] === 'resale' ? true : $sale->is_resale,
            'cancellation_fee'     => $validated['status'] === 'returned' ? ($validated['cancellation_fee'] ?? 0.00) : $sale->cancellation_fee,
            'refund_amount'        => $validated['status'] === 'returned' ? ($validated['refund_amount'] ?? 0.00) : $sale->refund_amount,
        ]);

        $shouldFreeUnit = false;
        if ($validated['status'] === 'cancelled') {
            $shouldFreeUnit = true;
        } elseif ($validated['status'] === 'returned') {
            $shouldFreeUnit = $request->has('revert_unsold') ? !empty($validated['revert_unsold']) : true;
        } elseif ($validated['status'] === 'resale') {
            $shouldFreeUnit = true;
        }

        if ($shouldFreeUnit) {
            Unit::where('id', $sale->unit_id)->update([
                'status'             => 'available',
                'sale_rate_per_sqft' => null,
                'sale_amount'        => null,
                'difference'         => null,
                'gst_behavior'       => 'none',
                'gst_amount'         => 0.00,
            ]);
            \App\Models\CustomerInstallment::where('sale_id', $sale->id)->delete();
        }

        SaleStatusLog::create([
            'sale_id'      => $sale->id,
            'from_status'  => $fromStatus,
            'to_status'    => $validated['status'],
            'event_type'   => $validated['status'],
            'reason'       => $validated['reason'],
            'performed_by' => auth()->id(),
        ]);

        return response()->json(['sale' => $sale->load(['receipts', 'brokerage', 'unit.floor', 'project', 'customer'])]);
    }

    private function syncDefaultEmiSchedule(Sale $sale): void
    {
        // Always wipe existing schedule first to avoid duplicates
        CustomerInstallment::where('sale_id', $sale->id)->delete();

        if ($sale->payment_plan !== 'emi') {
            return;
        }

        $totalAmount = (float)$sale->total_amount;
        $remaining = (float)$sale->remaining_balance;
        $downPayment = round($totalAmount - $remaining, 2);

        $startDate = \Carbon\Carbon::parse($sale->agreement_date ?? $sale->sale_date ?? now());

        // 1. Down payment (status = paid if fully received, otherwise pending/partial)
        if ($downPayment > 0) {
            CustomerInstallment::create([
                'sale_id'        => $sale->id,
                'installment_no' => 0,
                'label'          => 'Down Payment',
                'due_date'       => $startDate->toDateString(),
                'amount'         => $downPayment,
                'status'         => 'paid',
                'schedule_type'  => 'fixed_emi',
            ]);
        }

        $planType = $sale->emi_plan_type ?? 'fixed-12';

        if ($planType === 'fixed-12') {
            // 2. 12 Monthly EMI installments
            $numEmi = 12;
            $emiAmount = $numEmi > 0 ? round($remaining / $numEmi, 2) : 0;

            for ($i = 1; $i <= $numEmi; $i++) {
                $due = $startDate->copy()->addMonths($i);
                $amt = ($i === $numEmi)
                    ? round($remaining - ($emiAmount * ($numEmi - 1)), 2)
                    : $emiAmount;

                if ($amt > 0) {
                    CustomerInstallment::create([
                        'sale_id'        => $sale->id,
                        'installment_no' => $i,
                        'label'          => "EMI {$i}",
                        'due_date'       => $due->toDateString(),
                        'amount'         => $amt,
                        'status'         => 'pending',
                        'schedule_type'  => 'fixed_emi',
                    ]);
                }
            }
        } elseif ($planType === 'clp') {
            // Construction Linked Milestone Plan (CLP)
            // Milestones with percentages (Total = 90%)
            $milestones = [
                ['label' => 'Excavation & Foundation Start', 'pct' => 15],
                ['label' => 'Plinth Level Casting', 'pct' => 15],
                ['label' => 'Ground Floor Slab Casting', 'pct' => 10],
                ['label' => 'First Floor Slab Casting', 'pct' => 10],
                ['label' => 'Masonry & Internal Brickwork', 'pct' => 15],
                ['label' => 'Sanitary & External Plastering', 'pct' => 15],
                ['label' => 'Final Handover possession', 'pct' => 10],
            ];

            $numMilestones = count($milestones);
            $cumulativeAllocated = 0.0;

            for ($i = 0; $i < $numMilestones; $i++) {
                $m = $milestones[$i];
                $due = $startDate->copy()->addMonths(($i + 1) * 3); // estimated 3 month intervals
                
                // Scale percentage so the milestones sum to 100% of remaining balance
                if ($i === $numMilestones - 1) {
                    $amt = round($remaining - $cumulativeAllocated, 2);
                } else {
                    $amt = round($remaining * ($m['pct'] / 90.0), 2);
                    $cumulativeAllocated += $amt;
                }

                if ($amt > 0) {
                    CustomerInstallment::create([
                        'sale_id'        => $sale->id,
                        'installment_no' => $i + 1,
                        'label'          => $m['label'],
                        'due_date'       => $due->toDateString(),
                        'amount'         => $amt,
                        'status'         => 'pending',
                        'schedule_type'  => 'milestone_clp',
                    ]);
                }
            }
        } elseif ($planType === 'fixed-36') {
            // 36-Month Milestone + Fixed Combo Plan
            // 35 monthly payments representing ~2% each (scaled) + 3 mid-step/registry payments (~5% each)
            // Sum of weights = 35 * 2 + 3 * 5 = 70 + 15 = 85.
            $monthlyWeight = 2.0;
            $milestoneWeight = 5.0;
            $totalWeight = 85.0;

            $cumulativeAllocated = 0.0;
            $instIndex = 1;

            // Generate 35 monthly installments
            for ($i = 1; $i <= 35; $i++) {
                $due = $startDate->copy()->addMonths($i);
                $amt = round($remaining * ($monthlyWeight / $totalWeight), 2);
                $cumulativeAllocated += $amt;

                if ($amt > 0) {
                    CustomerInstallment::create([
                        'sale_id'        => $sale->id,
                        'installment_no' => $instIndex++,
                        'label'          => "EMI {$i} (Month {$i})",
                        'due_date'       => $due->toDateString(),
                        'amount'         => $amt,
                        'status'         => 'pending',
                        'schedule_type'  => 'combo_fixed_36',
                    ]);
                }
            }

            // Milestone 1 (Plinth, Month 10)
            $amtPlinth = round($remaining * ($milestoneWeight / $totalWeight), 2);
            $cumulativeAllocated += $amtPlinth;
            CustomerInstallment::create([
                'sale_id'        => $sale->id,
                'installment_no' => $instIndex++,
                'label'          => 'Plinth Stage (Milestone 1)',
                'due_date'       => $startDate->copy()->addMonths(10)->toDateString(),
                'amount'         => $amtPlinth,
                'status'         => 'pending',
                'schedule_type'  => 'combo_fixed_36',
            ]);

            // Milestone 2 (Roof, Month 22)
            $amtRoof = round($remaining * ($milestoneWeight / $totalWeight), 2);
            $cumulativeAllocated += $amtRoof;
            CustomerInstallment::create([
                'sale_id'        => $sale->id,
                'installment_no' => $instIndex++,
                'label'          => 'Roof Stage (Milestone 2)',
                'due_date'       => $startDate->copy()->addMonths(22)->toDateString(),
                'amount'         => $amtRoof,
                'status'         => 'pending',
                'schedule_type'  => 'combo_fixed_36',
            ]);

            // Milestone 3 (Handover / Registry, Month 36) - adjusts for rounding
            $amtHandover = round($remaining - $cumulativeAllocated, 2);
            CustomerInstallment::create([
                'sale_id'        => $sale->id,
                'installment_no' => $instIndex++,
                'label'          => 'Handover & Registry (Milestone 3)',
                'due_date'       => $startDate->copy()->addMonths(36)->toDateString(),
                'amount'         => $amtHandover,
                'status'         => 'pending',
                'schedule_type'  => 'combo_fixed_36',
            ]);
        }
    }
}