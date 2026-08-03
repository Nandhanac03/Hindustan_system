<?php
namespace App\Http\Controllers;
use App\Models\Sale;
use App\Models\SaleUnit;
use App\Models\SaleStatusLog;
use App\Models\Receipt;
use App\Models\Brokerage;
use App\Models\Broker;
use App\Models\Project;
use App\Models\Unit;
use App\Models\Customer;
use App\Models\Partner;
use App\Models\CustomerInstallment;
use App\Models\Bank;
use App\Models\UnitType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
class SalesController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $projectsList = Project::orderBy('name')->get();
        if (!$request->ajax() && !$request->wantsJson() && !$request->has('project_id') && !$request->filled('project_id') && $projectsList->isNotEmpty()) {
            $request->merge(['project_id' => (string)$projectsList->first()->id]);
        }
        $query = Sale::with(['project', 'unit.unitType', 'unit.floor', 'customer', 'broker', 'receipts', 'saleUnits.unit.floor', 'saleUnits.unit.unitType', 'extraWorks', 'statusLogs']);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        $isReturnExchange = in_array($request->tab, ['exchange', 'sale-return', 'returns', 'cancellations']);
        if ($request->filled('project_id') && !$isReturnExchange) {
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
        $postedVouchers = DB::table('vouchers')
            ->where('type', 'Receipt')
            ->where('status', 'Posted')
            ->whereNotNull('reference_no')
            ->get(['reference_no']);

        $refundsPaidBySale = [];
        foreach ($postedVouchers as $v) {
            $refData = json_decode($v->reference_no, true);
            if (!empty($refData['allocations']) && is_array($refData['allocations'])) {
                foreach ($refData['allocations'] as $alloc) {
                    if (($alloc['type'] ?? '') === 'refund' && !empty($alloc['target_id'])) {
                        $sId = (int)$alloc['target_id'];
                        $amt = (float)($alloc['amount'] ?? 0);
                        $refundsPaidBySale[$sId] = ($refundsPaidBySale[$sId] ?? 0.0) + $amt;
                    }
                }
            }
        }

        $sales = $query->orderByDesc('created_at')->get()->map(function ($sale) use ($refundsPaidBySale) {
            $sale->refund_paid = $refundsPaidBySale[$sale->id] ?? 0.00;
            return $sale;
        });
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['sales' => $sales]);
        }
        return view('sales.index', [
            'projects' => Project::orderBy('name')->get(),
            'customers' => Customer::orderBy('name')->get(),
            'brokers' => Broker::orderBy('name')->get(),
            'bankAccounts' => Bank::where('status', 'active')->orderBy('bank_name')->get(),
            'unitTypes' => UnitType::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
    public function availableUnits(int $projectId): JsonResponse
    {
        $units = Unit::where('project_id', $projectId)
            ->where('status', 'available')
            ->where('is_active', true)
            ->with(['floor', 'unitType'])
            ->get()
            ->sortBy([
                fn($a, $b) => ($a->floor?->number ?? 0) <=> ($b->floor?->number ?? 0),
                fn($a, $b) => strnatcmp($a->door_no ?? '', $b->door_no ?? ''),
            ])
            ->map(function ($unit) {
                return [
                    'id'                     => $unit->id,
                    'door_no'                => $unit->door_no,
                    'floor_name'             => $unit->floor?->name ?? '',
                    'floor_number'           => $unit->floor?->number ?? 0,
                    'built_up_area'          => $unit->built_up_area,
                    'expected_rate_per_sqft' => $unit->expected_rate_per_sqft,
                    'expected_sale_amount'   => $unit->expected_sale_amount,
                    'unit_type_id'           => $unit->unit_type_id,
                    'unit_type_name'         => $unit->unitType?->name ?? '',
                    'unit_type_category'     => $unit->unitType?->category ?? '',
                ];
            })
            ->values();
        $unitTypes = UnitType::where('project_id', $projectId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'category']);

        return response()->json([
            'units'     => $units,
            'unitTypes' => $unitTypes
        ]);
    }
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_id'             => ['required', 'exists:projects,id'],
            'customer_id'            => ['required', 'exists:customers,id'],
            'agreement_date'         => ['required', 'date'],
            'registration_date'      => ['nullable', 'date'],
            'broker_involved'        => ['nullable', 'boolean'],
            'broker_id'              => ['nullable', 'required_if:broker_involved,true', 'exists:brokers,id'],
            'brokerage_status'       => ['nullable', Rule::in(['pending', 'partial', 'paid'])],
            'initial_payment_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_mode'           => ['nullable', 'string'],
            'reference_no'           => ['nullable', 'string'],
            'bank_id'                => ['nullable', 'exists:banks,id'],
            'initial_payment_date'   => ['nullable', 'date'],
            'payment_plan'           => ['required', Rule::in(['lump_sum', 'emi'])],
            'emi_installment_count'  => ['nullable', 'required_if:payment_plan,emi', 'integer', 'min:1'],
            'emi_frequency'          => ['nullable', 'required_if:payment_plan,emi', Rule::in(['monthly', 'quarterly'])],
            'first_installment_date' => ['nullable', 'required_if:payment_plan,emi', 'date'],
            'notes'                  => ['nullable', 'string'],
            'units'                  => ['required', 'array', 'min:1'],
            'units.*.unit_id'        => ['required', 'exists:hindustan_units,id'],
            'units.*.wing'           => ['nullable', 'string'],
            'units.*.rate_per_sqft'  => ['nullable', 'numeric', 'min:0'],
            'units.*.sale_rate_per_sqft' => ['nullable', 'numeric', 'min:0'],
            'units.*.sale_amount'    => ['required', 'numeric', 'min:0'],
            'units.*.gst_percentage' => ['nullable', 'numeric', 'min:0'],
            'units.*.gst_amount'     => ['nullable', 'numeric', 'min:0'],
            'broker_involved'        => ['nullable', 'boolean'],
            'broker_id'              => ['nullable', 'required_if:broker_involved,true', 'exists:brokers,id'],
            'brokerage_type'         => ['nullable', Rule::in(['percentage', 'fixed'])],
            'brokerage_value'        => ['nullable', 'numeric', 'min:0'],
            'brokerage_status'       => ['nullable', Rule::in(['pending', 'paid'])],
            'extra_works'            => ['nullable', 'array'],
            'extra_works.*.description' => ['required', 'string'],
            'extra_works.*.amount'      => ['required', 'numeric', 'min:0'],
            'extra_works.*.gst_type'    => ['required', Rule::in(['exclusive', 'inclusive', 'none'])],
            'extra_works.*.gst_percentage' => ['nullable', 'numeric', 'min:0'],
            'broker_involved'        => ['nullable', 'boolean'],
            'broker_id'              => ['nullable', 'required_if:broker_involved,true', 'exists:brokers,id'],
            'brokerage_type'         => ['nullable', Rule::in(['percentage', 'fixed'])],
            'brokerage_value'        => ['nullable', 'numeric', 'min:0'],
            'brokerage_status'       => ['nullable', Rule::in(['pending', 'paid'])],
            'extra_works'            => ['nullable', 'array'],
            'extra_works.*.description' => ['required', 'string'],
            'extra_works.*.amount'      => ['required', 'numeric', 'min:0'],
            'extra_works.*.gst_type'    => ['required', Rule::in(['exclusive', 'inclusive', 'none'])],
            'extra_works.*.gst_percentage' => ['required', 'numeric', 'min:0'],
        ]);

        return DB::transaction(function () use ($validated) {
            $unitsData = $validated['units'];
            // Compute aggregated totals
            $totalSaleAmount = 0.0;
            $totalGstAmount = 0.0;
            $totalBaseAmount = 0.0;
            $totalContractAmount = 0.0;
            $totalBrokerageAmount = 0.0;
            $processedUnits = [];
            $extraWorksData = $validated['extra_works'] ?? [];
            $processedExtraWorks = [];
            foreach ($extraWorksData as $ewItem) {
                $ewAmount = (float)$ewItem['amount'];
                $ewGstPct = isset($ewItem['gst_percentage']) && $ewItem['gst_percentage'] !== '' ? (float)$ewItem['gst_percentage'] : 0.0;
                $ewGstType = $ewItem['gst_type'] ?? 'none';
                $ewGstAmount = 0.0;
                $ewLineTotal = 0.0;
                if ($ewGstType === 'exclusive') {
                    $ewGstAmount = round($ewAmount * ($ewGstPct / 100), 2);
                    $ewLineTotal = round($ewAmount + $ewGstAmount, 2);
                } elseif ($ewGstType === 'inclusive') {
                    $base = $ewAmount / (1 + ($ewGstPct / 100));
                    $ewGstAmount = round($ewAmount - $base, 2);
                    $ewLineTotal = round($ewAmount, 2);
                    $ewAmount = round($base, 2);
                } else {
                    $ewGstAmount = 0.0;
                    $ewLineTotal = round($ewAmount, 2);
                }
                $processedExtraWorks[] = [
                    'description' => $ewItem['description'],
                    'amount' => $ewAmount,
                    'gst_type' => $ewGstType,
                    'gst_percentage' => $ewGstPct,
                    'gst_amount' => $ewGstAmount,
                    'line_total' => $ewLineTotal,
                ];
                $totalSaleAmount += $ewAmount;
                $totalGstAmount += $ewGstAmount;
                $totalBaseAmount += $ewAmount;
                $totalContractAmount += $ewLineTotal;
            }
            foreach ($unitsData as $item) {
                $unitModel = Unit::findOrFail($item['unit_id']);
                $area = (float)$unitModel->built_up_area ?: 1.0;
                $isUnitParking = $unitModel->unitType && (strtolower($unitModel->unitType->name) === 'parking' || strtolower($unitModel->unitType->category) === 'parking');
                $expectedRate = (float)($item['rate_per_sqft'] ?? $unitModel->expected_rate_per_sqft ?? 0.0);
                $saleRate = (float)($item['sale_rate_per_sqft'] ?? $item['rate_per_sqft'] ?? 0.0);
                $rate = $isUnitParking ? 0.0 : ($saleRate > 0 ? $saleRate : $expectedRate);
                $amount = (float)$item['sale_amount'];
                $inputGstPct = isset($item['gst_percentage']) && $item['gst_percentage'] !== '' ? (float)$item['gst_percentage'] : null;
                $inputGstAmount = isset($item['gst_amount']) && $item['gst_amount'] !== '' ? (float)$item['gst_amount'] : null;

                $gstAmount = 0.0;
                $gstPct = 0.0;

                if ($inputGstAmount !== null && $inputGstAmount > 0) {
                    $gstAmount = round($inputGstAmount, 2);
                    $gstPct = $amount > 0 ? round(($gstAmount / $amount) * 100, 4) : 0.0;
                } elseif ($inputGstPct !== null && $inputGstPct > 0 && $amount > 0) {
                    $gstPct = $inputGstPct;
                    $gstAmount = round($amount * ($gstPct / 100), 2);
                }

                $baseAmount = $amount;
                $lineTotal = round($amount + $gstAmount, 2);
                $gstType = ($gstAmount > 0 || $gstPct > 0) ? 'exclusive' : 'none';
                $processedUnits[] = [
                    'unit_id' => $item['unit_id'],
                    'wing' => $item['wing'] ?? null,
                    'rate_per_sqft' => $rate,
                    'area_sqft' => $area,
                    'base_amount' => $baseAmount,
                    'gst_type' => $gstType,
                    'gst_percentage' => $gstPct,
                    'gst_amount' => $gstAmount,
                    'line_total' => $lineTotal,
                    'brokerage_type' => null,
                    'brokerage_value' => null,
                    'brokerage_amount' => 0.0,
                ];
                $totalSaleAmount += $amount;
                $totalGstAmount += $gstAmount;
                $totalBaseAmount += $baseAmount;
                $totalContractAmount += $lineTotal;
            }
            $brokerInvolved = (bool)($validated['broker_involved'] ?? false);
            $initialPayment = (float)($validated['initial_payment_amount'] ?? 0);
            // Populating first unit's info in main table to prevent backward compatibility issues
            $firstLine = $processedUnits[0];
            $sale = Sale::create([
                'sale_number'            => $this->generateSaleNumber($validated['project_id'], $firstLine['unit_id'], $validated['customer_id']),
                'project_id'             => $validated['project_id'],
                'unit_id'                => $firstLine['unit_id'], // fallback
                'customer_id'            => $validated['customer_id'],
                'broker_id'              => $brokerInvolved ? ($validated['broker_id'] ?? null) : null,
                'rate_per_sqft'          => $firstLine['rate_per_sqft'],
                'sale_amount'            => $totalSaleAmount,
                'gst_applicable'         => $totalGstAmount > 0,
                'gst_type'               => $firstLine['gst_type'],
                'gst_percentage'         => $totalGstAmount > 0 ? 18 : null,
                'gst_amount'             => $totalGstAmount,
                'base_amount'            => $totalBaseAmount,
                'total_amount'           => $totalContractAmount,
                'sale_date'              => $validated['agreement_date'],
                'agreement_date'         => $validated['agreement_date'],
                'registration_date'      => $validated['registration_date'] ?? null,
                'status'                 => 'active',
                'broker_involved'        => $brokerInvolved,
                'payment_plan'           => $validated['payment_plan'],
                'emi_type'               => $validated['payment_plan'] === 'emi' ? 'equal' : null,
                'emi_installment_count'  => $validated['emi_installment_count'] ?? null,
                'emi_frequency'          => $validated['emi_frequency'] ?? null,
                'first_installment_date' => $validated['first_installment_date'] ?? null,
                'remaining_balance'      => round($totalContractAmount - $initialPayment, 2),
                'notes'                  => $validated['notes'] ?? null,
                'created_by'             => auth()->id(),
                'bank_id'                => $validated['bank_id'] ?? null,
            ]);
            // Save extra works
            foreach ($processedExtraWorks as $ew) {
                $sale->extraWorks()->create($ew);
            }
            // Save individual units & update status
            foreach ($processedUnits as $pUnit) {
                $su = SaleUnit::create(array_merge($pUnit, ['sale_id' => $sale->id]));
                $unitModel = Unit::findOrFail($pUnit['unit_id']);
                $unitDifference = (float)$unitModel->expected_sale_amount - (float)$pUnit['line_total'];
                $unitModel->update([
                    'status'             => 'sold',
                    'sale_rate_per_sqft' => $pUnit['rate_per_sqft'],
                    'sale_amount'        => $pUnit['line_total'],
                    'difference'         => $unitDifference,
                    'gst_behavior'       => $pUnit['gst_type'],
                    'gst_amount'         => $pUnit['gst_amount'],
                ]);
            }
            // Create global brokerage mapping if broker involved
            if ($brokerInvolved) {
                $unitsBaseSum = 0.0;
                foreach ($unitsData as $u) {
                    $unitsBaseSum += (float)$u['sale_amount'];
                }
                $bVal = (float)($validated['brokerage_value'] ?? 0);
                $bType = $validated['brokerage_type'] ?? 'percentage';
                $brokerageAmount = 0.0;
                if ($bType === 'percentage') {
                    $brokerageAmount = round($unitsBaseSum * ($bVal / 100), 2);
                } else {
                    $brokerageAmount = round($bVal, 2);
                }
                Brokerage::create([
                    'sale_id'            => $sale->id,
                    'broker_id'          => $validated['broker_id'],
                    'commission_type'    => $bType,
                    'commission_percent' => $bType === 'percentage' ? $bVal : null,
                    'commission_amount'  => $brokerageAmount,
                    'paid_amount'        => 0,
                    'status'             => $validated['brokerage_status'] ?? 'pending',
                    'remarks'            => 'Overall brokerage commission',
                ]);
            }
            SaleStatusLog::create([
                'sale_id'      => $sale->id,
                'from_status'  => null,
                'to_status'    => 'active',
                'event_type'   => 'created',
                'performed_by' => auth()->id(),
            ]);
            // Create initial payment receipt
            if ($initialPayment > 0) {
                $receipt = Receipt::create([
                    'sale_id'      => $sale->id,
                    'customer_id'  => $validated['customer_id'],
                    'project_id'   => $validated['project_id'],
                    'unit_id'      => $firstLine['unit_id'],
                    'receipt_date' => $validated['initial_payment_date'] ?? $validated['agreement_date'],
                    'amount'       => $initialPayment,
                    'payment_mode' => $validated['payment_mode'] ?? 'cash',
                    'reference_no' => $validated['reference_no'] ?? null,
                    'bank_id'      => $validated['bank_id'] ?? null,
                    'remarks'      => 'Initial payment at sale creation',
                    'created_by'   => auth()->id(),
                ]);
                // Receipt::allocateToPartners($receipt);
            }
            $this->syncDefaultEmiSchedule($sale);
            \App\Models\CustomerInstallment::allocatePaymentStatusForSale($sale->id);
            return response()->json(['sale' => $sale->load(['receipts', 'brokerage'])], 201);
        });
    }
    public function show(int $id): JsonResponse
    {
        $sale = Sale::with(['project', 'unit.floor', 'unit.unitType', 'customer', 'broker', 'statusLogs', 'receipts', 'brokerage', 'saleUnits.unit.floor', 'saleUnits.unit.unitType', 'extraWorks'])->findOrFail($id);
        $sale->status_logs = $sale->statusLogs;
        return response()->json(['sale' => $sale]);
    }
    public function update(Request $request, int $id): JsonResponse
    {
        $sale = Sale::findOrFail($id);
        $validated = $request->validate([
            'sale_date'              => ['required', 'date'],
            'registration_date'      => ['nullable', 'date'],
            'broker_involved'        => ['nullable', 'boolean'],
            'broker_id'              => ['nullable', 'required_if:broker_involved,true', 'exists:brokers,id'],
            'brokerage_status'       => ['nullable', Rule::in(['pending', 'partial', 'paid'])],
            'initial_payment_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_mode'           => ['nullable', 'string'],
            'reference_no'           => ['nullable', 'string'],
            'bank_id'                => ['nullable', 'exists:banks,id'],
            'initial_payment_date'   => ['nullable', 'date'],
            'payment_plan'           => ['required', Rule::in(['lump_sum', 'emi'])],
            'emi_installment_count'  => ['nullable', 'required_if:payment_plan,emi', 'integer', 'min:1'],
            'emi_frequency'          => ['nullable', 'required_if:payment_plan,emi', Rule::in(['monthly', 'quarterly'])],
            'first_installment_date' => ['nullable', 'required_if:payment_plan,emi', 'date'],
            'notes'                  => ['nullable', 'string'],
            'units'                  => ['required', 'array', 'min:1'],
            'units.*.unit_id'        => ['required', 'exists:hindustan_units,id'],
            'units.*.wing'           => ['nullable', 'string'],
            'units.*.rate_per_sqft'  => ['nullable', 'numeric', 'min:0'],
            'units.*.sale_rate_per_sqft' => ['nullable', 'numeric', 'min:0'],
            'units.*.sale_amount'    => ['required', 'numeric', 'min:0'],
            'units.*.gst_percentage' => ['nullable', 'numeric', 'min:0'],
            'units.*.gst_amount'     => ['nullable', 'numeric', 'min:0'],
            'broker_involved'        => ['nullable', 'boolean'],
            'broker_id'              => ['nullable', 'required_if:broker_involved,true', 'exists:brokers,id'],
            'brokerage_type'         => ['nullable', Rule::in(['percentage', 'fixed'])],
            'brokerage_value'        => ['nullable', 'numeric', 'min:0'],
            'brokerage_status'       => ['nullable', Rule::in(['pending', 'paid'])],
            'extra_works'            => ['nullable', 'array'],
            'extra_works.*.description' => ['required', 'string'],
            'extra_works.*.amount'      => ['required', 'numeric', 'min:0'],
            'extra_works.*.gst_type'    => ['required', Rule::in(['exclusive', 'inclusive', 'none'])],
            'extra_works.*.gst_percentage' => ['nullable', 'numeric', 'min:0'],
        ]);
        return DB::transaction(function () use ($validated, $sale) {
            $unitsData = $validated['units'];
            // Compute aggregated totals
            $totalSaleAmount = 0.0;
            $totalGstAmount = 0.0;
            $totalBaseAmount = 0.0;
            $totalContractAmount = 0.0;
            $extraWorksData = $validated['extra_works'] ?? [];
            $processedExtraWorks = [];
            foreach ($extraWorksData as $ewItem) {
                $ewAmount = (float)$ewItem['amount'];
                $ewGstPct = isset($ewItem['gst_percentage']) && $ewItem['gst_percentage'] !== '' ? (float)$ewItem['gst_percentage'] : 0.0;
                $ewGstType = $ewItem['gst_type'] ?? 'none';
                $ewGstAmount = 0.0;
                $ewLineTotal = 0.0;
                if ($ewGstType === 'exclusive') {
                    $ewGstAmount = round($ewAmount * ($ewGstPct / 100), 2);
                    $ewLineTotal = round($ewAmount + $ewGstAmount, 2);
                } elseif ($ewGstType === 'inclusive') {
                    $base = $ewAmount / (1 + ($ewGstPct / 100));
                    $ewGstAmount = round($ewAmount - $base, 2);
                    $ewLineTotal = round($ewAmount, 2);
                    $ewAmount = round($base, 2);
                } else {
                    $ewGstAmount = 0.0;
                    $ewLineTotal = round($ewAmount, 2);
                }
                $processedExtraWorks[] = [
                    'description' => $ewItem['description'],
                    'amount' => $ewAmount,
                    'gst_type' => $ewGstType,
                    'gst_percentage' => $ewGstPct,
                    'gst_amount' => $ewGstAmount,
                    'line_total' => $ewLineTotal,
                ];
                $totalSaleAmount += $ewAmount;
                $totalGstAmount += $ewGstAmount;
                $totalBaseAmount += $ewAmount;
                $totalContractAmount += $ewLineTotal;
            }
            $submittedUnitIds = collect($unitsData)->pluck('unit_id')->toArray();
            // Set removed units back to available
            $oldUnitIds = $sale->saleUnits()->pluck('unit_id')->toArray();
            $removedUnitIds = array_diff($oldUnitIds, $submittedUnitIds);
            if (!empty($removedUnitIds)) {
                Unit::whereIn('id', $removedUnitIds)->update([
                    'status'             => 'available',
                    'sale_rate_per_sqft' => null,
                    'sale_amount'        => null,
                    'difference'         => null,
                    'gst_behavior'       => 'none',
                    'gst_amount'         => 0.00,
                ]);
                $sale->saleUnits()->whereIn('unit_id', $removedUnitIds)->delete();
            }
            $processedUnits = [];
            foreach ($unitsData as $item) {
                $unitModel = Unit::findOrFail($item['unit_id']);
                $area = (float)$unitModel->built_up_area ?: 1.0;
                $isUnitParking = $unitModel->unitType && (strtolower($unitModel->unitType->name) === 'parking' || strtolower($unitModel->unitType->category) === 'parking');
                $expectedRate = (float)($item['rate_per_sqft'] ?? $unitModel->expected_rate_per_sqft ?? 0.0);
                $saleRate = (float)($item['sale_rate_per_sqft'] ?? $item['rate_per_sqft'] ?? 0.0);
                $rate = $isUnitParking ? 0.0 : ($saleRate > 0 ? $saleRate : $expectedRate);
                $amount = (float)$item['sale_amount'];
                $inputGstPct = isset($item['gst_percentage']) && $item['gst_percentage'] !== '' ? (float)$item['gst_percentage'] : null;
                $inputGstAmount = isset($item['gst_amount']) && $item['gst_amount'] !== '' ? (float)$item['gst_amount'] : null;

                $gstAmount = 0.0;
                $gstPct = 0.0;

                if ($inputGstAmount !== null && $inputGstAmount > 0) {
                    $gstAmount = round($inputGstAmount, 2);
                    $gstPct = $amount > 0 ? round(($gstAmount / $amount) * 100, 4) : 0.0;
                } elseif ($inputGstPct !== null && $inputGstPct > 0 && $amount > 0) {
                    $gstPct = $inputGstPct;
                    $gstAmount = round($amount * ($gstPct / 100), 2);
                }

                $baseAmount = $amount;
                $lineTotal = round($amount + $gstAmount, 2);
                $gstType = ($gstAmount > 0 || $gstPct > 0) ? 'exclusive' : 'none';
                $processedUnits[] = [
                    'unit_id' => $item['unit_id'],
                    'wing' => $item['wing'] ?? null,
                    'rate_per_sqft' => $rate,
                    'area_sqft' => $area,
                    'base_amount' => $baseAmount,
                    'gst_type' => $gstType,
                    'gst_percentage' => $gstPct,
                    'gst_amount' => $gstAmount,
                    'line_total' => $lineTotal,
                ];
                $totalSaleAmount += $amount;
                $totalGstAmount += $gstAmount;
                $totalBaseAmount += $baseAmount;
                $totalContractAmount += $lineTotal;
            }
            $brokerInvolved = (bool)($validated['broker_involved'] ?? false);
            $initialPayment = (float)($validated['initial_payment_amount'] ?? 0);
            // Populating first unit's info in main table to prevent backward compatibility issues
            $firstLine = $processedUnits[0];
            $sale->update([
                'unit_id'                => $firstLine['unit_id'], // fallback
                'broker_id'              => $brokerInvolved ? ($validated['broker_id'] ?? null) : null,
                'rate_per_sqft'          => $firstLine['rate_per_sqft'],
                'sale_amount'            => $totalSaleAmount,
                'gst_applicable'         => $totalGstAmount > 0,
                'gst_type'               => $firstLine['gst_type'],
                'gst_percentage'         => $totalGstAmount > 0 ? 18 : null,
                'gst_amount'             => $totalGstAmount,
                'base_amount'            => $totalBaseAmount,
                'total_amount'           => $totalContractAmount,
                'sale_date'              => $validated['sale_date'],
                'agreement_date'         => $validated['sale_date'],
                'registration_date'      => $validated['registration_date'] ?? null,
                'broker_involved'        => $brokerInvolved,
                'payment_plan'           => $validated['payment_plan'],
                'emi_type'               => $validated['payment_plan'] === 'emi' ? 'equal' : null,
                'emi_installment_count'  => $validated['emi_installment_count'] ?? null,
                'emi_frequency'          => $validated['emi_frequency'] ?? null,
                'first_installment_date' => $validated['first_installment_date'] ?? null,
                'notes'                  => $validated['notes'] ?? null,
                'bank_id'                => $validated['bank_id'] ?? null,
            ]);
            // Sync extra works
            $sale->extraWorks()->delete();
            foreach ($processedExtraWorks as $ew) {
                $sale->extraWorks()->create($ew);
            }
            // Save/update individual units & update status
            foreach ($processedUnits as $pUnit) {
                $su = $sale->saleUnits()->updateOrCreate(
                    ['unit_id' => $pUnit['unit_id']],
                    $pUnit
                );
                $unitModel = Unit::findOrFail($pUnit['unit_id']);
                $unitDifference = (float)$unitModel->expected_sale_amount - (float)$pUnit['line_total'];
                $unitModel->update([
                    'status'             => 'sold',
                    'sale_rate_per_sqft' => $pUnit['rate_per_sqft'],
                    'sale_amount'        => $pUnit['line_total'],
                    'difference'         => $unitDifference,
                    'gst_behavior'       => $pUnit['gst_type'],
                    'gst_amount'         => $pUnit['gst_amount'],
                ]);
            }
            // Sync overall brokerage
            if ($brokerInvolved) {
                $unitsBaseSum = 0.0;
                foreach ($unitsData as $u) {
                    $unitsBaseSum += (float)$u['sale_amount'];
                }
                $bVal = (float)($validated['brokerage_value'] ?? 0);
                $bType = $validated['brokerage_type'] ?? 'percentage';
                $brokerageAmount = 0.0;
                if ($bType === 'percentage') {
                    $brokerageAmount = round($unitsBaseSum * ($bVal / 100), 2);
                } else {
                    $brokerageAmount = round($bVal, 2);
                }
                $sale->brokerage()->updateOrCreate(
                    ['sale_id' => $sale->id],
                    [
                        'broker_id'          => $validated['broker_id'],
                        'commission_type'    => $bType,
                        'commission_percent' => $bType === 'percentage' ? $bVal : null,
                        'commission_amount'  => $brokerageAmount,
                        'paid_amount'        => 0,
                        'status'             => $validated['brokerage_status'] ?? 'pending',
                        'remarks'            => 'Updated overall commission',
                    ]
                );
            } else {
                $sale->brokerage()->delete();
            }
            // Sync initial payment receipt
            $receipt = $sale->receipts()->where('remarks', 'Initial payment at sale creation')->first();
            if ($initialPayment > 0) {
                if ($receipt) {
                    $receipt->update([
                        'amount'       => $initialPayment,
                        'payment_mode' => $validated['payment_mode'] ?? 'Cash',
                        'reference_no' => $validated['reference_no'] ?? null,
                        'bank_id'      => $validated['bank_id'] ?? null,
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
                        'bank_id'      => $validated['bank_id'] ?? null,
                        'remarks'      => 'Initial payment at sale creation',
                        'created_by'   => auth()->id(),
                    ]);
                }
            } else {
                if ($receipt) {
                    $receipt->delete();
                }
            }
            $sale->update([
                'remaining_balance' => round($sale->total_amount - $sale->receipts()->sum('amount'), 2),
            ]);
            // Sync/regenerate EMI schedules if plan is EMI
            $this->syncDefaultEmiSchedule($sale);
            \App\Models\CustomerInstallment::allocatePaymentStatusForSale($sale->id);
            return response()->json(['sale' => $sale->load(['receipts', 'brokerage'])], 200);
        });
    }
    public function addReceipt(Request $request, int $id): JsonResponse
    {
        $sale = Sale::findOrFail($id);
        $validated = $request->validate([
            'amount'       => ['required', 'numeric', 'min:0.01'],
            'receipt_date' => ['required', 'date'],
            'payment_mode' => ['required', 'string'],
            'reference_no' => ['nullable', 'string'],
            'bank_id'    => ['nullable', 'exists:banks,id'],
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
            'bank_id'      => $validated['bank_id'] ?? null,
            'remarks'      => $validated['remarks'] ?? null,
            'created_by'   => auth()->id(),
        ]);
        // Receipt::allocateToPartners($receipt);
        $sale->update([
            'remaining_balance' => round($sale->total_amount - $sale->receipts()->sum('amount'), 2),
        ]);
        CustomerInstallment::allocatePaymentStatusForSale($sale->id);
        return response()->json(['receipt' => $receipt, 'sale' => $sale->fresh(['receipts', 'brokerage'])], 201);
    }
    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status'                 => ['required', Rule::in(['cancelled', 'returned', 'exchanged', 'resale'])],
            'reason'                 => ['required', 'string'],
            'cancellation_fee'       => ['nullable', 'numeric', 'min:0'],
            'refund_amount'          => ['nullable', 'numeric', 'min:0'],
            'revert_unsold'          => ['nullable', 'boolean'],
            'new_unit_id'            => ['nullable', 'exists:hindustan_units,id'],
            'carry_forward'          => ['nullable', 'boolean'],
            'payment_plan'           => ['nullable', Rule::in(['lump_sum', 'emi'])],
            'emi_type'               => ['nullable', Rule::in(['equal', 'milestone'])],
            'emi_installment_count'  => ['nullable', 'integer', 'min:1'],
            'emi_frequency'          => ['nullable', Rule::in(['monthly', 'quarterly'])],
            'first_installment_date' => ['nullable', 'date'],
            'initial_payment_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_mode'           => ['nullable', 'string'],
            'reference_no'           => ['nullable', 'string'],
            'bank_id'                => ['nullable', 'exists:banks,id'],
            'initial_payment_date'   => ['nullable', 'date'],
        ]);
        $sale = Sale::with('saleUnits')->findOrFail($id);
        $fromStatus = $sale->status;
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
            // Build rich archive snapshot of the old unit & sale BEFORE modifications
            $oldUnitSnapshot = $this->buildSaleSnapshot($sale->id, [
                'exchange_meta' => [
                    'target_unit_id'    => $newUnit->id,
                    'target_door_no'    => $newUnit->door_no,
                    'carry_forward'     => !empty($validated['carry_forward']),
                    'exchange_reason'   => $validated['reason'],
                    'exchanged_at'      => now()->toDateTimeString(),
                    'exchanged_by_user' => auth()->user()?->name ?? 'System',
                ],
            ]);

            $sale->update([
                'status' => 'exchanged',
                'cancellation_reason' => $validated['reason'],
                'cancelled_at' => now(),
            ]);
            foreach ($sale->saleUnits as $su) {
                Unit::where('id', $su->unit_id)->update([
                    'status'             => 'available',
                    'sale_rate_per_sqft' => null,
                    'sale_amount'        => null,
                    'difference'         => null,
                    'gst_behavior'       => 'none',
                    'gst_amount'         => 0.00,
                ]);
            }
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
            
            $paymentPlan = $validated['payment_plan'] ?? $sale->payment_plan ?? 'lump_sum';
            $emiType = $validated['emi_type'] ?? $sale->emi_type ?? 'equal';
            $emiCount = isset($validated['emi_installment_count']) ? (int)$validated['emi_installment_count'] : ($sale->emi_installment_count ?? 12);
            $emiFreq = $validated['emi_frequency'] ?? $sale->emi_frequency ?? 'monthly';
            $firstDate = !empty($validated['first_installment_date']) ? \Carbon\Carbon::parse($validated['first_installment_date']) : null;

            $newSale = Sale::create([
                'sale_number'            => $this->generateSaleNumber($newUnit->project_id, $newUnit->id, $sale->customer_id),
                'project_id'             => $newUnit->project_id,
                'unit_id'                => $newUnit->id,
                'customer_id'            => $sale->customer_id,
                'broker_id'              => $sale->broker_id,
                'rate_per_sqft'          => $newRate,
                'sale_amount'            => $newAmount,
                'gst_applicable'         => $gstType !== 'none',
                'gst_type'               => $gstType,
                'gst_percentage'         => $gstType !== 'none' ? 18 : null,
                'gst_amount'             => $gstAmount,
                'base_amount'            => $baseAmount,
                'total_amount'           => $totalAmount,
                'sale_date'              => now(),
                'agreement_date'         => now(),
                'status'                 => 'active',
                'broker_involved'        => $sale->broker_involved,
                'payment_plan'           => $paymentPlan,
                'emi_type'               => $emiType,
                'emi_installment_count'  => $emiCount,
                'emi_frequency'          => $emiFreq,
                'first_installment_date' => $firstDate,
                'remaining_balance'      => $totalAmount,
                'notes'                  => 'Exchanged from sale ' . $sale->sale_number . '. ' . $validated['reason'],
                'created_by'             => auth()->id(),
            ]);
            
            \App\Models\SaleUnit::create([
                'sale_id'          => $newSale->id,
                'unit_id'          => $newUnit->id,
                'wing'             => null,
                'rate_per_sqft'    => $newRate,
                'area_sqft'        => (float)$newUnit->built_up_area ?: 1.0,
                'base_amount'      => $baseAmount,
                'gst_type'         => $gstType,
                'gst_percentage'   => $gstType !== 'none' ? 18 : 0,
                'gst_amount'       => $gstAmount,
                'line_total'       => $totalAmount,
                'brokerage_type'   => null,
                'brokerage_value'  => null,
                'brokerage_amount' => 0.0,
            ]);

            // Create initial payment receipt if provided
            $initialPayment = (float)($validated['initial_payment_amount'] ?? 0);
            if ($initialPayment > 0) {
                Receipt::create([
                    'sale_id'      => $newSale->id,
                    'customer_id'  => $newSale->customer_id,
                    'project_id'   => $newSale->project_id,
                    'unit_id'      => $newSale->unit_id,
                    'receipt_date' => $validated['initial_payment_date'] ?? now()->toDateString(),
                    'amount'       => $initialPayment,
                    'payment_mode' => $validated['payment_mode'] ?? 'Cash',
                    'reference_no' => $validated['reference_no'] ?? null,
                    'bank_id'      => $validated['bank_id'] ?? null,
                    'remarks'      => 'Initial booking payment for exchanged unit',
                    'created_by'   => auth()->id(),
                ]);
            }

            if (!empty($validated['carry_forward'])) {
                Receipt::where('sale_id', $sale->id)->update([
                    'sale_id' => $newSale->id,
                    'project_id' => $newUnit->project_id,
                    'unit_id' => $newUnit->id,
                ]);
                $sale->update(['remaining_balance' => $sale->total_amount - $sale->receipts()->sum('amount')]);
                $newSale->update(['remaining_balance' => $totalAmount - $newSale->receipts()->sum('amount')]);
            } else {
                if ($initialPayment > 0) {
                    $newSale->update(['remaining_balance' => round($totalAmount - $initialPayment, 2)]);
                }
            }
            $newUnitDifference = (float)$newUnit->expected_sale_amount - $newAmount;
            $newUnit->update([
                'status'             => 'sold',
                'sale_rate_per_sqft' => $newRate,
                'sale_amount'        => $newAmount,
                'difference'         => $newUnitDifference,
                'gst_behavior'       => $gstType,
                'gst_amount'         => $gstAmount,
            ]);
            SaleStatusLog::create([
                'sale_id'       => $sale->id,
                'from_status'   => $fromStatus,
                'to_status'     => 'exchanged',
                'event_type'    => 'exchanged',
                'reason'        => $validated['reason'],
                'snapshot_data' => $oldUnitSnapshot,
                'performed_by'  => auth()->id(),
            ]);
            \App\Models\CustomerInstallment::where('sale_id', $sale->id)->delete();
            $this->syncDefaultEmiSchedule($newSale);
            \App\Models\CustomerInstallment::allocatePaymentStatusForSale($newSale->id);
            // Build rich snapshot of the new unit & sale AFTER modifications and installment sync
            $newUnitSnapshot = $this->buildSaleSnapshot($newSale->id);
            SaleStatusLog::create([
                'sale_id'       => $newSale->id,
                'from_status'   => null,
                'to_status'     => 'active',
                'event_type'    => 'created',
                'reason'        => 'Created via unit exchange from sale ' . $sale->sale_number,
                'snapshot_data' => $newUnitSnapshot,
                'performed_by'  => auth()->id(),
            ]);
            return response()->json(['sale' => $newSale->load(['receipts', 'brokerage', 'unit.floor', 'project', 'customer', 'statusLogs'])]);
        }
        // Build rich snapshot of the unit & sale BEFORE cancellation/returns/resales
        $snapshotData = $this->buildSaleSnapshot($sale->id);

        $sale->update([
            'status'               => $validated['status'],
            'cancellation_reason'  => in_array($validated['status'], ['cancelled', 'returned']) ? $validated['reason'] : $sale->cancellation_reason,
            'cancelled_at'         => in_array($validated['status'], ['cancelled', 'returned']) ? now() : $sale->cancelled_at,
            'is_resale'            => $validated['status'] === 'resale' ? true : $sale->is_resale,
            'cancellation_fee'     => in_array($validated['status'], ['cancelled', 'returned']) ? ($validated['cancellation_fee'] ?? 0.00) : $sale->cancellation_fee,
            'refund_amount'        => in_array($validated['status'], ['cancelled', 'returned']) ? ($validated['refund_amount'] ?? 0.00) : $sale->refund_amount,
        ]);
        $shouldFreeUnit = in_array($validated['status'], ['cancelled', 'returned', 'resale']);
        if ($shouldFreeUnit) {
            foreach ($sale->saleUnits as $su) {
                Unit::where('id', $su->unit_id)->update([
                    'status'             => 'available',
                    'sale_rate_per_sqft' => null,
                    'sale_amount'        => null,
                    'difference'         => null,
                    'gst_behavior'       => 'none',
                    'gst_amount'         => 0.00,
                ]);
            }
            \App\Models\CustomerInstallment::where('sale_id', $sale->id)->delete();
        }
        SaleStatusLog::create([
            'sale_id'      => $sale->id,
            'from_status'  => $fromStatus,
            'to_status'    => $validated['status'],
            'event_type'   => $validated['status'],
            'reason'       => $validated['reason'],
            'snapshot_data'=> $snapshotData,
            'performed_by' => auth()->id(),
        ]);
        return response()->json(['sale' => $sale->load(['receipts', 'brokerage', 'unit.floor', 'project', 'customer'])]);
    }
    private function buildSaleSnapshot(int $saleId, array $extraMeta = []): array
    {
        $saleFull = Sale::with(['unit.unitType', 'unit.floor', 'project', 'customer', 'receipts', 'saleUnits.unit'])->findOrFail($saleId);
        $installments = \App\Models\CustomerInstallment::where('sale_id', $saleId)->orderBy('installment_no')->get();

        $snapshot = [
            'old_sale' => [
                'id'                     => $saleFull->id,
                'sale_number'            => $saleFull->sale_number,
                'sale_date'              => $saleFull->sale_date ? \Carbon\Carbon::parse($saleFull->sale_date)->format('Y-m-d') : null,
                'agreement_date'         => $saleFull->agreement_date ? \Carbon\Carbon::parse($saleFull->agreement_date)->format('Y-m-d') : null,
                'status'                 => $saleFull->status,
                'payment_plan'           => $saleFull->payment_plan,
                'emi_type'               => $saleFull->emi_type,
                'emi_installment_count'  => $saleFull->emi_installment_count,
                'emi_frequency'          => $saleFull->emi_frequency,
                'first_installment_date' => $saleFull->first_installment_date ? \Carbon\Carbon::parse($saleFull->first_installment_date)->format('Y-m-d') : null,
                'rate_per_sqft'          => (float)$saleFull->rate_per_sqft,
                'sale_amount'            => (float)$saleFull->sale_amount,
                'gst_type'               => $saleFull->gst_type,
                'gst_amount'             => (float)$saleFull->gst_amount,
                'base_amount'            => (float)$saleFull->base_amount,
                'total_amount'           => (float)$saleFull->total_amount,
                'remaining_balance'      => (float)$saleFull->remaining_balance,
                'total_paid'             => (float)$saleFull->receipts->sum('amount'),
            ],
            'old_unit' => [
                'id'                   => $saleFull->unit?->id,
                'door_no'              => $saleFull->unit?->door_no,
                'floor_name'           => $saleFull->unit?->floor?->name,
                'unit_type_name'       => $saleFull->unit?->unitType?->name,
                'built_up_area'        => (float)($saleFull->unit?->built_up_area ?? 0),
                'expected_sale_amount' => (float)($saleFull->unit?->expected_sale_amount ?? 0),
            ],
            'customer' => [
                'id'    => $saleFull->customer?->id,
                'name'  => $saleFull->customer?->name,
                'phone' => $saleFull->customer?->phone,
            ],
            'project' => [
                'id'   => $saleFull->project?->id,
                'name' => $saleFull->project?->name,
            ],
            'receipts' => $saleFull->receipts->map(function ($r) {
                return [
                    'id'             => $r->id,
                    'receipt_number' => $r->receipt_number,
                    'receipt_date'   => $r->receipt_date ? \Carbon\Carbon::parse($r->receipt_date)->format('Y-m-d') : null,
                    'amount'         => (float)$r->amount,
                    'payment_mode'   => $r->payment_mode,
                    'reference_no'   => $r->reference_no,
                    'status'         => $r->status ?? 'posted',
                ];
            })->values()->toArray(),
            'installments' => $installments->map(function ($inst) {
                return [
                    'id'             => $inst->id,
                    'installment_no' => $inst->installment_no,
                    'label'          => $inst->label,
                    'due_date'       => $inst->due_date ? \Carbon\Carbon::parse($inst->due_date)->format('Y-m-d') : null,
                    'amount'         => (float)$inst->amount,
                    'status'         => $inst->status,
                    'schedule_type'  => $inst->schedule_type,
                ];
            })->values()->toArray(),
        ];

        return array_merge($snapshot, $extraMeta);
    }
    private function syncDefaultEmiSchedule(Sale $sale, array $milestones = []): void
    {
        CustomerInstallment::where('sale_id', $sale->id)->delete();
        if ($sale->payment_plan !== 'emi') {
            return;
        }
        $totalAmount = (float)$sale->total_amount;
        $remaining = (float)$sale->remaining_balance;
        $downPayment = round($totalAmount - $remaining, 2);
        $startDate = \Carbon\Carbon::parse($sale->agreement_date ?? $sale->sale_date ?? now());
        if ($downPayment > 0) {
            CustomerInstallment::create([
                'sale_id'        => $sale->id,
                'installment_no' => 0,
                'label'          => 'Down Payment',
                'due_date'       => $startDate->toDateString(),
                'amount'         => $downPayment,
                'paid_amount'    => $downPayment,
                'status'         => 'paid',
                'schedule_type'  => 'fixed_emi',
            ]);
        }
        if ($sale->emi_type === 'equal') {
            $numEmi = (int)$sale->emi_installment_count;
            if ($numEmi <= 0) {
                return;
            }
            $emiAmount = round($remaining / $numEmi, 2);
            $firstDateVal = $sale->first_installment_date ? \Carbon\Carbon::parse($sale->first_installment_date) : $startDate->copy()->addMonth();
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
        } elseif ($sale->emi_type === 'milestone') {
            $numMilestones = count($milestones);
            if ($numMilestones === 0) {
                return;
            }
            $cumulativeAllocated = 0.0;
            for ($i = 0; $i < $numMilestones; $i++) {
                $m = $milestones[$i];
                $pct = (float)$m['percentage'];
                $due = \Carbon\Carbon::parse($m['due_date']);
                if ($i === $numMilestones - 1) {
                    $amt = round($remaining - $cumulativeAllocated, 2);
                } else {
                    $amt = round($remaining * ($pct / 100.0), 2);
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
        }
    }

    protected function generateSaleNumber($projectId, $unitId, $customerId): string
    {
        $project = Project::find($projectId);
        $projectCode = 'PRJ';
        if ($project) {
            if (!empty($project->code)) {
                $projectCode = strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $project->code));
            } else {
                $words = explode(' ', trim($project->name));
                if (count($words) >= 2) {
                    $projectCode = strtoupper(substr($words[0], 0, 3) . '-' . sprintf('%02d', $project->id));
                } else {
                    $projectCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $project->name), 0, 4));
                }
            }
        }

        $unit = Unit::with('unitType')->find($unitId);
        $unitTypeName = 'FLAT';
        $doorNo = '00';
        if ($unit) {
            if ($unit->unitType) {
                $rawName  = strtolower($unit->unitType->name ?? '');
                $category = strtolower($unit->unitType->category ?? '');

                if (str_contains($rawName, 'park') || $category === 'parking') {
                    $unitTypeName = 'PA';
                } elseif (str_contains($rawName, 'apart')) {
                    $unitTypeName = 'AP';
                } elseif (str_contains($rawName, 'flat') || str_contains($rawName, 'bhk')) {
                    $unitTypeName = 'FL';
                } elseif (str_contains($rawName, 'shop')) {
                    $unitTypeName = 'SH';
                } elseif (str_contains($rawName, 'office')) {
                    $unitTypeName = 'OF';
                } else {
                    $clean = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $unit->unitType->name));
                    $unitTypeName = (strlen($clean) > 5) ? substr($clean, 0, 5) : $clean;
                }
            }
            if (!empty($unit->door_no)) {
                $doorNo = strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $unit->door_no));
            }
        }

        $customer = Customer::find($customerId);
        $custSnippet = 'CUST';
        if ($customer && !empty($customer->name)) {
            $cleanName = strtoupper(preg_replace('/[^A-Za-z]/', '', $customer->name));
            if (strlen($cleanName) >= 4) {
                $custSnippet = substr($cleanName, 0, 4);
            } elseif (strlen($cleanName) > 0) {
                $custSnippet = $cleanName;
            }
        }

        $baseSaleNo = "{$projectCode}-{$unitTypeName}-{$doorNo}-{$custSnippet}";

        $saleNo = $baseSaleNo;
        $counter = 1;
        while (Sale::where('sale_number', $saleNo)->exists()) {
            $saleNo = "{$baseSaleNo}-{$counter}";
            $counter++;
        }

        return $saleNo;
    }
}