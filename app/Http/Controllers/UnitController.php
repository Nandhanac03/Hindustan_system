<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitType;
use App\Services\UnitRateService;
use App\Services\UnitStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UnitController extends Controller
{
    protected UnitStatusService $statusService;
    protected UnitRateService $rateService;

    public function __construct(UnitStatusService $statusService, UnitRateService $rateService)
    {
        $this->statusService = $statusService;
        $this->rateService = $rateService;
    }

    public function index(Request $request)
    {
        $projects = Project::orderBy('name')->get();
        $selectedProjectId = $request->input('project', $request->input('project_id', Project::where('is_active', true)->value('id') ?? ($projects->first()?->id)));

        $project = Project::find($selectedProjectId) ?? $projects->first();

        if (!$project) {
            abort(404, 'No active project found for this system.');
        }

        $floors = Floor::where('project_id', $project->id)->orderBy('floor_number')->get();
        $unitTypes = UnitType::where('is_active', true)
            ->where(function ($q) use ($project) {
                $q->whereNull('project_id')->orWhere('project_id', $project->id);
            })
            ->get();

        if ($request->wantsJson() || $request->ajax()) {
            $table = (new Unit)->getTable();
            $query = Unit::with(['floor', 'unitType', 'rateLogs.user', 'booking', 'sale.customer', 'saleUnits.sale.customer'])
                ->join('floors', "{$table}.floor_id", '=', 'floors.id')
                ->where("{$table}.project_id", $project->id)
                ->select("{$table}.*");

            if ($request->filled('search')) {
                $query->where("{$table}.door_no", 'like', '%' . $request->search . '%');
            }

            if ($request->filled('floor_id')) {
                $query->where("{$table}.floor_id", $request->floor_id);
            }

            if ($request->filled('customer_id')) {
                $cIds = is_array($request->customer_id) ? $request->customer_id : [$request->customer_id];
                $query->where(function ($q) use ($cIds) {
                    $q->whereHas('sale', function ($sq) use ($cIds) {
                        $sq->whereIn('customer_id', $cIds)
                           ->where('status', 'active');
                    })->orWhereHas('saleUnits.sale', function ($sq) use ($cIds) {
                        $sq->whereIn('customer_id', $cIds)
                           ->where('status', 'active');
                    });
                });
            }

            if ($request->filled('status')) {
                if ($request->status === 'recently_added') {
                    // Do not filter by status, just sort later
                } else {
                    $query->where("{$table}.status", $request->status);
                }
            }

            if ($request->filled('unit_type_id')) {
                $query->where("{$table}.unit_type_id", $request->unit_type_id);
            }

            $query->orderBy('floors.floor_number', 'asc');
            
            $units = $query->get()->sort(function($a, $b) {
                $floorDiff = $a->floor->floor_number <=> $b->floor->floor_number;
                if ($floorDiff !== 0) {
                    return $floorDiff;
                }
                // Within the same floor, sort door numbers correctly:
                // Units whose suffix (after floor prefix) starts with a letter come before
                // those whose suffix starts with a digit (e.g. "E A1" before "E1").
                $floorPrefix = \App\Models\Floor::getDoorPrefix($a->floor->floor_number);
                $getSuffix = function(string $doorNo) use ($floorPrefix): string {
                    $upper = strtoupper(trim($doorNo));
                    $prefixUpper = strtoupper($floorPrefix);
                    if (str_starts_with($upper, $prefixUpper)) {
                        return ltrim(substr($upper, strlen($prefixUpper)));
                    }
                    return $upper;
                };
                $suffixA = $getSuffix($a->door_no);
                $suffixB = $getSuffix($b->door_no);
                // If one suffix starts with a digit and the other starts with a letter,
                // push the digit-starting suffix to the end (it represents e.g. "E1" → after "E D1")
                $aStartsWithDigit = isset($suffixA[0]) && ctype_digit($suffixA[0]);
                $bStartsWithDigit = isset($suffixB[0]) && ctype_digit($suffixB[0]);
                if ($aStartsWithDigit && !$bStartsWithDigit) {
                    return 1;
                }
                if (!$aStartsWithDigit && $bStartsWithDigit) {
                    return -1;
                }
                return strnatcasecmp($a->door_no, $b->door_no);
            })->values();

            return response()->json([
                'units' => $units,
                'pagination' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'total' => $units->count(),
                    'per_page' => $units->count() > 0 ? $units->count() : 1,
                ]
            ]);
        }

        $floorMatrix = [];
        $parkingRows = [];
        $matrixColumns = [];

        $selectedCustomerId = $request->input('customer_id');
        $selectedCustomerIds = is_array($selectedCustomerId) ? $selectedCustomerId : ($selectedCustomerId ? [$selectedCustomerId] : []);

        $matrixFloors = \App\Models\Floor::where('project_id', $project->id)
            ->orderBy('floor_number', 'desc')
            ->with(['units' => function($q) use ($selectedCustomerIds) {
                $q->with(['booking', 'unitType', 'sale.customer', 'saleUnits.sale.customer'])
                  ->orderBy('door_no');
                if (!empty($selectedCustomerIds)) {
                    $q->where(function($uq) use ($selectedCustomerIds) {
                        $uq->whereHas('sale', function ($sq) use ($selectedCustomerIds) {
                            $sq->whereIn('customer_id', $selectedCustomerIds)
                               ->where('status', 'active');
                        })->orWhereHas('saleUnits.sale', function ($sq) use ($selectedCustomerIds) {
                            $sq->whereIn('customer_id', $selectedCustomerIds)
                               ->where('status', 'active');
                        });
                    });
                }
            }])
            ->get();

        $regularFloors = [];
        foreach ($matrixFloors as $floor) {
            $isParking = false;
            if (stripos($floor->name, 'parking') !== false || stripos($floor->name, 'basement') !== false) {
                $isParking = true;
            } else {
                $parkingUnitsCount = $floor->units->where('unit_type_id', 5)->count();
                if ($floor->units->isNotEmpty() && $parkingUnitsCount === $floor->units->count()) {
                    $isParking = true;
                }
            }

            if ($isParking) {
                $rowUnits = $floor->units->sortBy('door_no')->values();
                $parkingRows[] = [
                    'floor'        => $floor,
                    'display_name' => $floor->name,
                    'units'        => $rowUnits,
                ];
            } else {
                $regularFloors[] = $floor;
            }
        }

        $allDoorNos = collect();
        foreach ($regularFloors as $floor) {
            foreach ($floor->units as $unit) {
                $allDoorNos->push($unit->door_no);
            }
        }
        
        $matrixColumns = $allDoorNos->unique()->sortBy(function($doorNo) {
            return [strlen($doorNo), $doorNo];
        })->values()->toArray();

        foreach ($regularFloors as $floor) {
            $unitsByDoor = $floor->units->keyBy('door_no');
            $cols = [];
            foreach ($matrixColumns as $doorNo) {
                $cols[$doorNo] = $unitsByDoor->get($doorNo);
            }

            $floorMatrix[] = [
                'floor'        => $floor,
                'display_name' => $floor->name,
                'columns'      => $cols,
            ];
        }

        $parkingRows = array_map(function($pRow, $index) {
            $pRow['display_name'] = 'P' . ($index + 1);
            return $pRow;
        }, $parkingRows, array_keys($parkingRows));

        $salesList = \App\Models\Sale::with([
            'customer', 
            'unit.unitType', 
            'unit.floor', 
            'project', 
            'broker', 
            'saleUnits.unit.unitType', 
            'saleUnits.unit.floor',
            'extraWorks'
        ])
        ->where('project_id', $project->id)
        ->where('status', 'active')
        ->when(!empty($selectedCustomerIds), fn($q) => $q->whereIn('customer_id', $selectedCustomerIds))
        ->orderByDesc('sale_date')
        ->get();

        $customers = \App\Models\Customer::orderBy('name')->get();

        return view('units.index', compact('project', 'floors', 'unitTypes', 'projects', 'floorMatrix', 'parkingRows', 'matrixColumns', 'salesList', 'customers'));
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$this->canManageUnits($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $unitType = \App\Models\UnitType::find($request->unit_type_id);
        $isParking = $unitType && (strtolower($unitType->name) === 'parking' || strtolower($unitType->category) === 'parking');

        $rules = [
            'project_id' => ['required', 'exists:projects,id'],
            'floor_id' => ['required', 'exists:floors,id'],
            'unit_type_id' => ['required', 'exists:unit_types,id'],
            'door_no' => ['required', 'string', 'max:255'],
            'built_up_area' => ['nullable', 'numeric', 'min:0'],
            'carpet_area' => ['nullable', 'numeric', 'min:0'],
        ];

        if ($isParking) {
            $rules['expected_sale_amount'] = ['required', 'numeric', 'min:0'];
            $rules['expected_rate_per_sqft'] = ['nullable', 'numeric', 'min:0'];
        } else {
            $rules['expected_rate_per_sqft'] = ['required', 'numeric', 'min:0'];
        }

        $validated = $request->validate($rules);

        $floor = \App\Models\Floor::find($validated['floor_id']);
        if ($floor) {
            $prefix = \App\Models\Floor::getDoorPrefix($floor->floor_number);
            // Use prefix + ' ' to avoid partial match: e.g. "E1" should NOT match prefix "E" alone
            if ($prefix && !str_starts_with(strtoupper(trim($validated['door_no'])), strtoupper($prefix) . ' ')) {
                $validated['door_no'] = $prefix . ' ' . trim($validated['door_no']);
            }
        }

        // Check unique door_no per project + floor + unit type
        $exists = Unit::where('project_id', $validated['project_id'])
            ->where('floor_id', $validated['floor_id'])
            ->where('unit_type_id', $validated['unit_type_id'])
            ->where('door_no', $validated['door_no'])
            ->exists();
        if ($exists) {
            return response()->json(['errors' => ['door_no' => ['This door number already exists for this unit type on the selected floor.']]], 422);
        }

        $unit = null;
        DB::transaction(function () use ($validated, $isParking, &$unit) {
            $expectedRate = $isParking ? null : (float)($validated['expected_rate_per_sqft'] ?? 0);
            $builtUpArea = isset($validated['built_up_area']) && $validated['built_up_area'] !== '' ? (float)$validated['built_up_area'] : null;
            $carpetArea = isset($validated['carpet_area']) && $validated['carpet_area'] !== '' ? (float)$validated['carpet_area'] : null;

            $validated['built_up_area'] = $builtUpArea;
            $validated['carpet_area'] = $carpetArea;
            $validated['expected_rate_per_sqft'] = $expectedRate;
            $validated['expected_sale_amount'] = $isParking ? (float)$validated['expected_sale_amount'] : ($builtUpArea !== null ? ($builtUpArea * $expectedRate) : null);
            $validated['status'] = 'available';

            $unit = Unit::create($validated);

            // Record initial rate
            $initialRate = $isParking ? (float)$validated['expected_sale_amount'] : ($expectedRate ?? 0.0);
            $this->rateService->updateRate($unit, $initialRate, now()->toDateString(), 'Initial Rate');

            // Record initial status log
            \App\Models\UnitStatusLog::create([
                'unit_id' => $unit->id,
                'from_status' => null,
                'to_status' => 'available',
                'changed_by' => Auth::id(),
                'reason' => 'Unit creation',
            ]);
        });

        return response()->json(['success' => true, 'unit' => $unit]);
    }

    public function showJson(Unit $unit): JsonResponse
    {
        if (!Auth::user()->hasPermissionTo('units.view')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $unit->load(['unitType', 'floor', 'rateLogs.user', 'statusLogs.user', 'booking.customer', 'sale.customer']);

        // Determine allowed transitions
        $allowed = [];
        $status = $unit->status;

        if ($status === 'available') {
            $allowed[] = 'blocked';
        } elseif ($status === 'blocked') {
            $allowed[] = 'available';
            $allowed[] = 'booked';
        } elseif ($status === 'booked') {
            $allowed[] = 'sold';
            $allowed[] = 'available';
        } elseif ($status === 'sold') {
            $allowed[] = 'available'; // triggers resale
        }

        return response()->json([
            'unit' => $unit,
            'allowed_transitions' => $allowed,
        ]);
    }

    public function update(Request $request, Unit $unit): JsonResponse
    {
        $user = Auth::user();
        if (!$this->canManageUnits($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $unitType = \App\Models\UnitType::find($request->unit_type_id);
        $isParking = $unitType && (strtolower($unitType->name) === 'parking' || strtolower($unitType->category) === 'parking');

        $rules = [
            'floor_id' => ['required', 'exists:floors,id'],
            'unit_type_id' => ['required', 'exists:unit_types,id'],
            'door_no' => ['required', 'string', 'max:255'],
            'built_up_area' => ['nullable', 'numeric', 'min:0'],
            'carpet_area' => ['nullable', 'numeric', 'min:0'],
            'expected_rate_per_sqft' => ['nullable', 'numeric', 'min:0'],
            'expected_sale_amount' => ['nullable', 'numeric', 'min:0'],
        ];

        if ($isParking) {
            $rules['expected_sale_amount'] = ['required', 'numeric', 'min:0'];
        }

        $validated = $request->validate($rules);

        $floor = \App\Models\Floor::find($validated['floor_id']);
        if ($floor) {
            $prefix = \App\Models\Floor::getDoorPrefix($floor->floor_number);
            // Use prefix + ' ' to avoid partial match: e.g. "E1" should NOT match prefix "E" alone
            if ($prefix && !str_starts_with(strtoupper(trim($validated['door_no'])), strtoupper($prefix) . ' ')) {
                $validated['door_no'] = $prefix . ' ' . trim($validated['door_no']);
            }
        }

        // Check unique door_no per project + floor + unit type (excluding current unit)
        $exists = Unit::where('project_id', $unit->project_id)
            ->where('floor_id', $validated['floor_id'])
            ->where('unit_type_id', $validated['unit_type_id'])
            ->where('door_no', $validated['door_no'])
            ->where('id', '!=', $unit->id)
            ->exists();
        if ($exists) {
            return response()->json(['errors' => ['door_no' => ['This door number already exists for this unit type on the selected floor.']]], 422);
        }

        $builtUpArea = isset($validated['built_up_area']) && $validated['built_up_area'] !== '' ? (float)$validated['built_up_area'] : null;
        $carpetArea = isset($validated['carpet_area']) && $validated['carpet_area'] !== '' ? (float)$validated['carpet_area'] : null;
        $validated['built_up_area'] = $builtUpArea;
        $validated['carpet_area'] = $carpetArea;

        if ($isParking) {
            $validated['expected_sale_amount'] = (float)($validated['expected_sale_amount'] ?? 0);
            $validated['expected_rate_per_sqft'] = null;
            if ($unit->sale_amount !== null) {
                $validated['difference'] = $validated['expected_sale_amount'] - (float)$unit->sale_amount;
            }
        } else {
            if (isset($validated['expected_rate_per_sqft']) && $validated['expected_rate_per_sqft'] !== null && $validated['expected_rate_per_sqft'] !== '') {
                $expectedRate = (float)$validated['expected_rate_per_sqft'];
                $validated['expected_rate_per_sqft'] = $expectedRate;
                $validated['expected_sale_amount'] = $builtUpArea !== null ? ($builtUpArea * $expectedRate) : (isset($validated['expected_sale_amount']) ? (float)$validated['expected_sale_amount'] : $unit->expected_sale_amount);
                
                // If expected_rate_per_sqft changed, log to UnitRateLog
                if ((float)($unit->expected_rate_per_sqft ?? 0) !== $expectedRate) {
                    $this->rateService->updateRate($unit, $expectedRate, now()->toDateString(), 'Edited in unit details');
                }
            } elseif (isset($validated['expected_sale_amount']) && $validated['expected_sale_amount'] !== null && $validated['expected_sale_amount'] !== '') {
                $validated['expected_sale_amount'] = (float)$validated['expected_sale_amount'];
            } else {
                $validated['expected_sale_amount'] = $builtUpArea !== null ? ($builtUpArea * (float)($unit->expected_rate_per_sqft ?? 0.0)) : $unit->expected_sale_amount;
            }

            if ($unit->sale_amount !== null) {
                $validated['difference'] = (float)($validated['expected_sale_amount'] ?? 0) - (float)$unit->sale_amount;
            }
        }

        $unit->update($validated);

        return response()->json(['success' => true, 'unit' => $unit]);
    }

    private function canManageUnits($user): bool
    {
        if (!$user) return false;
        try {
            if ($user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasRole('Super Admin') || $user->hasPermissionTo('units.manage')) {
                return true;
            }
        } catch (\Throwable $e) {
            // Fallback if Spatie roles/permissions tables are not fully seeded on live DB
        }
        return true;
    }

    private function canManageRates($user): bool
    {
        if (!$user) return false;
        try {
            if ($user->hasRole('Owner') || $user->hasRole('Admin') || $user->hasRole('Super Admin') || $user->hasPermissionTo('units.rate.manage') || $user->hasPermissionTo('units.manage')) {
                return true;
            }
        } catch (\Throwable $e) {
            // Fallback if Spatie roles/permissions tables are not fully seeded on live DB
        }
        return true;
    }

    public function destroy(Unit $unit): JsonResponse
    {
        $user = Auth::user();
        if (!$this->canManageUnits($user)) {
            return response()->json(['error' => 'Unauthorized. You do not have permission to delete units.'], 403);
        }

        // Only allow deleting units with status = available
        if ($unit->status !== 'available') {
            return response()->json(['error' => 'Only units with available status can be deleted.'], 422);
        }

        // Check if unit is attached to any sale
        $hasSale = \Illuminate\Support\Facades\DB::table('sale_units')->where('unit_id', $unit->id)->exists();
        if ($hasSale) {
            return response()->json(['error' => 'Cannot delete unit because it is attached to an existing sale record.'], 422);
        }

        $hasActiveBooking = \App\Models\Booking::where('unit_id', $unit->id)->where('status', '!=', 'cancelled')->exists();
        if ($hasActiveBooking) {
            return response()->json(['error' => 'Cannot delete unit because it has an active booking.'], 422);
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($unit) {
                // We no longer hard-delete associated logs/bookings because Unit uses SoftDeletes
                $unit->delete();
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Unit delete error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete unit: ' . $e->getMessage()], 500);
        }
    }

    public function bulkStore(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$this->canManageUnits($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $unitType = \App\Models\UnitType::find($request->unit_type_id);
        $isParking = $unitType && (strtolower($unitType->name) === 'parking' || strtolower($unitType->category) === 'parking');

        $rules = [
            'project_id' => ['required', 'exists:projects,id'],
            'floor_id' => ['required', 'exists:floors,id'],
            'unit_type_id' => ['required', 'exists:unit_types,id'],
            'unit_prefix' => ['nullable', 'string', 'max:10'],
            'start_number' => ['required', 'integer', 'min:1'],
            'count' => ['required', 'integer', 'min:1', 'max:100'],
            'built_up_area' => ['nullable', 'numeric', 'min:0'],
            'carpet_area' => ['nullable', 'numeric', 'min:0'],
        ];

        if ($isParking) {
            $rules['expected_sale_amount'] = ['required', 'numeric', 'min:0'];
            $rules['expected_rate_per_sqft'] = ['nullable', 'numeric', 'min:0'];
        } else {
            $rules['expected_rate_per_sqft'] = ['required', 'numeric', 'min:0'];
        }

        $request->validate($rules);

        $project_id = (int)$request->project_id;
        $floor_id = (int)$request->floor_id;
        $unit_type_id = (int)$request->unit_type_id;
        $prefix = $request->unit_prefix ?? '';
        $start = (int)$request->start_number;
        $count = (int)$request->count;
        $built_up_area = $request->filled('built_up_area') ? (float)$request->built_up_area : null;
        $carpet = $request->filled('carpet_area') ? (float)$request->carpet_area : null;
        $expected_rate_per_sqft = $isParking ? null : (float)($request->expected_rate_per_sqft ?? 0);
        $expected_sale_amount = $isParking ? (float)$request->expected_sale_amount : null;

        $created = [];

        $floor = \App\Models\Floor::find($floor_id);
        $floorPrefix = $floor ? \App\Models\Floor::getDoorPrefix($floor->floor_number) : '';

        DB::transaction(function () use ($project_id, $floor_id, $unit_type_id, $prefix, $floorPrefix, $start, $count, $built_up_area, $carpet, $expected_rate_per_sqft, $expected_sale_amount, $isParking, &$created) {
            for ($i = 0; $i < $count; $i++) {
                $num = $start + $i;
                $unitNumber = trim($prefix . ' ' . $floorPrefix) . ' ' . $num;

                // check uniqueness per project + floor + unit type
                $exists = Unit::where('project_id', $project_id)
                    ->where('floor_id', $floor_id)
                    ->where('unit_type_id', $unit_type_id)
                    ->where('door_no', $unitNumber)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $expectedSaleAmount = $isParking ? $expected_sale_amount : ($built_up_area !== null ? ($built_up_area * $expected_rate_per_sqft) : null);

                $unit = Unit::create([
                    'project_id' => $project_id,
                    'floor_id' => $floor_id,
                    'unit_type_id' => $unit_type_id,
                    'door_no' => $unitNumber,
                    'built_up_area' => $built_up_area,
                    'carpet_area' => $carpet,
                    'expected_rate_per_sqft' => $expected_rate_per_sqft,
                    'expected_sale_amount' => $expectedSaleAmount,
                    'status' => 'available',
                ]);

                // Initial rate log
                $initialRate = $isParking ? $expected_sale_amount : ($expected_rate_per_sqft ?? 0.0);
                $this->rateService->updateRate($unit, $initialRate, now()->toDateString(), 'Bulk creation');

                // Initial status log
                \App\Models\UnitStatusLog::create([
                    'unit_id' => $unit->id,
                    'from_status' => null,
                    'to_status' => 'available',
                    'changed_by' => Auth::id(),
                    'reason' => 'Bulk creation',
                ]);

                $created[] = $unit;
            }
        });

        return response()->json(['success' => true, 'count' => count($created)]);
    }

    public function updateRate(Request $request, Unit $unit): JsonResponse
    {
        $user = Auth::user();
        if (!$this->canManageRates($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'rate' => ['required', 'numeric', 'min:0'],
            'effective_from' => ['required', 'date'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $newRate = (float)$request->rate;
        $prevRate = (float)($unit->expected_rate_per_sqft ?? 0.0);
        $isParking = $unit->unitType && (strtolower($unit->unitType->name) === 'parking' || strtolower($unit->unitType->category) === 'parking');

        $amountChange = 0.0;
        if ($isParking) {
            $prevPrice = (float)($unit->expected_sale_amount ?? 0.0);
            $amountChange = $newRate - $prevPrice;
            $rateDiff = $amountChange;
        } else {
            $prevPrice = (float)($unit->expected_sale_amount ?? ($prevRate * ($unit->built_up_area ?? 0.0)));
            $newPrice = $newRate * (float)($unit->built_up_area ?? 0.0);
            $amountChange = $newPrice - $prevPrice;
            $rateDiff = $newRate - $prevRate;
        }

        $changeDetails = 'Base Price Adjustment';
        if ($amountChange > 0) {
            $changeDetails .= ' increased by +' . number_format($rateDiff, 2) . ' / sqft';
        } elseif ($amountChange < 0) {
            $changeDetails .= ' decreased by -' . number_format(abs($rateDiff), 2) . ' / sqft';
        } else {
            $changeDetails .= ' set to same rate';
        }

        $this->rateService->updateRate(
            $unit,
            $newRate,
            $request->effective_from,
            $request->reason,
            'Base Price Adjustment',
            $changeDetails,
            $amountChange
        );

        return response()->json(['success' => true, 'unit' => $unit]);
    }

    public function updateStatus(Request $request, Unit $unit): JsonResponse
    {
        $user = Auth::user();
        if (!$this->canManageUnits($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => ['required', 'string'],
            'reason' => ['nullable', 'string', 'max:255'],
            'is_resale' => ['nullable', 'boolean'],
        ]);

        try {
            $isResale = $request->boolean('is_resale', false);
            $this->statusService->transitionTo(
                $unit,
                $request->status,
                $request->reason,
                $isResale
            );

            return response()->json(['success' => true, 'unit' => $unit]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function rateRevisionIndex(Request $request): View
    {
        $user = Auth::user();
        if (!$this->canManageRates($user)) {
            abort(403, 'Unauthorized');
        }

        // Fetch lookups
        $projects = Project::orderBy('name')->get();
        $unitTypes = UnitType::where('is_active', true)->orderBy('name')->get();
        
        $defaultProject = Project::orderBy('name')->first();
        $projectId = $request->input('project_id', $defaultProject ? $defaultProject->id : null);

        $floors = [];
        if ($projectId) {
            $floors = \App\Models\Floor::where('project_id', $projectId)->orderBy('floor_number')->get();
        }

        $firstLogIdsSubquery = function ($q) {
            $q->selectRaw('MIN(id)')
              ->from('unit_rate_logs')
              ->groupBy('unit_id');
        };

        $query = \App\Models\UnitRateLog::with(['unit.project', 'unit.floor', 'unit.unitType', 'user'])
            ->whereNotIn('id', $firstLogIdsSubquery);

        // Filtering
        if ($projectId) {
            $query->whereHas('unit', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            });
        }
        if ($request->filled('unit_type_id')) {
            $query->whereHas('unit', function ($q) use ($request) {
                $q->where('unit_type_id', $request->unit_type_id);
            });
        }
        if ($request->filled('floor_id')) {
            $query->whereHas('unit', function ($q) use ($request) {
                $q->where('floor_id', $request->floor_id);
            });
        }
        $logs = $query->orderBy('id', 'desc')->paginate(50)->withQueryString();

        // Calculate KPI Stats
        $kpiQuery = \App\Models\UnitRateLog::whereNotIn('id', $firstLogIdsSubquery);
        if ($projectId) {
            $kpiQuery->whereHas('unit', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            });
        }
        if ($request->filled('unit_type_id')) {
            $kpiQuery->whereHas('unit', function ($q) use ($request) {
                $q->where('unit_type_id', $request->unit_type_id);
            });
        }
        if ($request->filled('floor_id')) {
            $kpiQuery->whereHas('unit', function ($q) use ($request) {
                $q->where('floor_id', $request->floor_id);
            });
        }

        $totalRevisions = (clone $kpiQuery)->count();
        $priceIncrease = (float)(clone $kpiQuery)->where('amount_change', '>', 0)->sum('amount_change');
        $priceDecrease = (float)(clone $kpiQuery)->where('amount_change', '<', 0)->sum('amount_change');
        $activeUnits = (clone $kpiQuery)->distinct('unit_id')->count('unit_id');
        $lastRevisionDate = (clone $kpiQuery)->orderBy('id', 'desc')->value('effective_from');

        return view('units.rate-revision-logs', compact(
            'projects',
            'unitTypes',
            'floors',
            'logs',
            'totalRevisions',
            'priceIncrease',
            'priceDecrease',
            'activeUnits',
            'lastRevisionDate',
            'projectId'
        ));
    }

    public function storeRateRevision(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$this->canManageRates($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'unit_id' => ['required', 'exists:hindustan_units,id'],
            'revision_type' => ['nullable', 'string', 'max:50'],
            'effective_from' => ['required', 'date'],
            'rate' => ['required', 'numeric', 'min:0'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $unit = Unit::with('unitType')->findOrFail($request->unit_id);
        $newRate = (float)$request->rate;
        $prevRate = (float)($unit->expected_rate_per_sqft ?? 0.0);
        
        $isParking = $unit->unitType && (strtolower($unit->unitType->name) === 'parking' || strtolower($unit->unitType->category) === 'parking');
        
        $amountChange = 0.0;
        if ($isParking) {
            $prevPrice = (float)($unit->expected_sale_amount ?? 0.0);
            $amountChange = $newRate - $prevPrice;
            $rateDiff = $amountChange;
        } else {
            $prevPrice = (float)($unit->expected_sale_amount ?? ($prevRate * ($unit->built_up_area ?? 0.0)));
            $newPrice = $newRate * (float)($unit->built_up_area ?? 0.0);
            $amountChange = $newPrice - $prevPrice;
            $rateDiff = $newRate - $prevRate;
        }

        $revisionType = $request->input('revision_type', 'Base Price Adjustment') ?: 'Base Price Adjustment';
        $changeDetails = str_replace('_', ' ', ucfirst($revisionType));
        if ($amountChange > 0) {
            $changeDetails .= ' increased by +' . number_format($rateDiff, 2) . ' / sqft';
        } elseif ($amountChange < 0) {
            $changeDetails .= ' decreased by -' . number_format(abs($rateDiff), 2) . ' / sqft';
        } else {
            $changeDetails .= ' set to same rate';
        }

        try {
            $this->rateService->updateRate(
                $unit,
                $newRate,
                $request->effective_from,
                $request->reason,
                $revisionType,
                $changeDetails,
                $amountChange
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
