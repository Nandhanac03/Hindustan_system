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
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class SalesController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = Sale::with(['project', 'unit', 'customer', 'broker']);

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
            'remaining_balance' => round($totalAmount - $initialPayment, 2),
            'notes'             => $validated['notes'] ?? null,
            'created_by'        => auth()->id(),
        ]);

        // Mark the unit as sold
        Unit::where('id', $validated['unit_id'])->update(['status' => 'sold']);

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

        return response()->json(['sale' => $sale->load(['receipts', 'brokerage'])], 201);
    }

    public function show(int $id): JsonResponse
    {
        $sale = Sale::with(['project', 'unit', 'customer', 'broker', 'statusLogs', 'receipts', 'brokerage'])->findOrFail($id);
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
            'gst_applicable'    => $validated['gst_type'] !== 'none',
            'gst_type'          => $validated['gst_type'],
            'gst_percentage'    => $validated['gst_type'] !== 'none' ? 18 : null,
            'gst_amount'        => $gstAmount,
            'base_amount'       => $baseAmount,
            'total_amount'      => $totalAmount,
            'remaining_balance' => round($totalAmount - $sale->receipts()->sum('amount'), 2),
            'notes'             => $validated['notes'] ?? null,
        ]);

        return response()->json(['sale' => $sale->load(['receipts', 'brokerage'])]);
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
        ]);

        $sale = Sale::findOrFail($id);
        $fromStatus = $sale->status;

        $sale->update([
            'status'               => $validated['status'],
            'cancellation_reason'  => in_array($validated['status'], ['cancelled', 'returned']) ? $validated['reason'] : $sale->cancellation_reason,
            'cancelled_at'         => in_array($validated['status'], ['cancelled', 'returned']) ? now() : $sale->cancelled_at,
            'is_resale'            => $validated['status'] === 'resale' ? true : $sale->is_resale,
        ]);

        // Free up the unit again if cancelled, returned, or resold
        if (in_array($validated['status'], ['cancelled', 'returned', 'resale'])) {
            Unit::where('id', $sale->unit_id)->update(['status' => 'available']);
        }

        SaleStatusLog::create([
            'sale_id'      => $sale->id,
            'from_status'  => $fromStatus,
            'to_status'    => $validated['status'],
            'event_type'   => $validated['status'],
            'reason'       => $validated['reason'],
            'performed_by' => auth()->id(),
        ]);

        return response()->json(['sale' => $sale]);
    }
}