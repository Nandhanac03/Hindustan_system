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
            $query = Unit::with(['floor', 'unitType', 'rateLogs.user', 'booking', 'sale.customer'])
                ->join('floors', "{$table}.floor_id", '=', 'floors.id')
                ->where("{$table}.project_id", $project->id)
                ->select("{$table}.*");

            if ($request->filled('search')) {
                $query->where("{$table}.door_no", 'like', '%' . $request->search . '%');
            }

            if ($request->filled('floor_id')) {
                $query->where("{$table}.floor_id", $request->floor_id);
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

        return view('units.index', compact('project', 'floors', 'unitTypes', 'projects'));
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
                // Delete associated rate/status logs and cancelled bookings before deleting unit
                $unit->rateLogs()->delete();
                $unit->statusLogs()->delete();
                \App\Models\Booking::where('unit_id', $unit->id)->delete();
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

        $this->rateService->updateRate(
            $unit,
            (float)$request->rate,
            $request->effective_from,
            $request->reason
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
}
