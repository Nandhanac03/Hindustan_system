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
        // Fetch first active project matching logged-in user's system_id (enforced by SystemScope)
        $project = Project::where('is_active', true)->first();

        if (!$project) {
            abort(404, 'No active project found for this system.');
        }

        $floors = Floor::where('project_id', $project->id)->orderBy('floor_number')->get();
        $unitTypes = UnitType::where('is_active', true)->get();

        if ($request->wantsJson() || $request->ajax()) {
            $query = Unit::with(['floor', 'unitType'])->where('project_id', $project->id);

            if ($request->filled('search')) {
                $query->where('unit_number', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('floor_id')) {
                $query->where('floor_id', $request->floor_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('unit_type_id')) {
                $query->where('unit_type_id', $request->unit_type_id);
            }

            $units = $query->orderBy('unit_number')->get();

            return response()->json([
                'units' => $units,
            ]);
        }

        return view('units.index', compact('project', 'floors', 'unitTypes'));
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user->hasPermissionTo('units.manage')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'floor_id' => ['required', 'exists:floors,id'],
            'unit_type_id' => ['required', 'exists:unit_types,id'],
            'unit_number' => ['required', 'string', 'max:255'],
            'bua_area' => ['required', 'numeric', 'min:0.01'],
            'carpet_area' => ['nullable', 'numeric', 'min:0'],
            'area_unit' => ['required', 'in:sqft,sqm'],
            'facing' => ['nullable', 'string', 'max:255'],
            'base_rate' => ['required', 'numeric', 'min:0'],
        ]);

        // Check unique unit_number in project
        $exists = Unit::where('project_id', $validated['project_id'])
            ->where('unit_number', $validated['unit_number'])
            ->exists();
        if ($exists) {
            return response()->json(['errors' => ['unit_number' => ['The unit number has already been taken in this project.']]], 422);
        }

        $unit = null;
        DB::transaction(function () use ($validated, &$unit) {
            $baseRate = (float)$validated['base_rate'];
            unset($validated['base_rate']);

            $validated['status'] = 'available';
            $validated['is_active'] = true;

            $unit = Unit::create($validated);

            // Record initial rate
            $this->rateService->updateRate($unit, $baseRate, now()->toDateString(), 'Initial Rate');

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

        $unit->load(['unitType', 'floor', 'rateLogs.user', 'statusLogs.user']);

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
        if (!$user->hasPermissionTo('units.manage')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'floor_id' => ['required', 'exists:floors,id'],
            'unit_type_id' => ['required', 'exists:unit_types,id'],
            'unit_number' => ['required', 'string', 'max:255'],
            'bua_area' => ['required', 'numeric', 'min:0.01'],
            'carpet_area' => ['nullable', 'numeric', 'min:0'],
            'area_unit' => ['required', 'in:sqft,sqm'],
            'facing' => ['nullable', 'string', 'max:255'],
        ]);

        // Check unique unit_number excluding current unit
        $exists = Unit::where('project_id', $unit->project_id)
            ->where('unit_number', $validated['unit_number'])
            ->where('id', '!=', $unit->id)
            ->exists();
        if ($exists) {
            return response()->json(['errors' => ['unit_number' => ['The unit number has already been taken in this project.']]], 422);
        }

        $unit->update($validated);

        return response()->json(['success' => true, 'unit' => $unit]);
    }

    public function destroy(Unit $unit): JsonResponse
    {
        $user = Auth::user();
        if (!$user->hasPermissionTo('units.manage')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Only allow deleting units with status = available
        if ($unit->status !== 'available') {
            return response()->json(['error' => 'Only units with available status can be deleted.'], 422);
        }

        $unit->delete();

        return response()->json(['success' => true]);
    }

    public function bulkStore(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user->hasPermissionTo('units.manage')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'floor_id' => ['required', 'exists:floors,id'],
            'unit_type_id' => ['required', 'exists:unit_types,id'],
            'unit_prefix' => ['nullable', 'string', 'max:10'],
            'start_number' => ['required', 'integer', 'min:1'],
            'count' => ['required', 'integer', 'min:1', 'max:100'],
            'bua_area' => ['required', 'numeric', 'min:0.01'],
            'carpet_area' => ['nullable', 'numeric', 'min:0'],
            'area_unit' => ['required', 'in:sqft,sqm'],
            'facing' => ['nullable', 'string', 'max:50'],
            'base_rate' => ['required', 'numeric', 'min:0'],
        ]);

        $project_id = (int)$request->project_id;
        $floor_id = (int)$request->floor_id;
        $unit_type_id = (int)$request->unit_type_id;
        $prefix = $request->unit_prefix ?? '';
        $start = (int)$request->start_number;
        $count = (int)$request->count;
        $bua = (float)$request->bua_area;
        $carpet = $request->carpet_area ? (float)$request->carpet_area : null;
        $area_unit = $request->area_unit;
        $facing = $request->facing;
        $base_rate = (float)$request->base_rate;

        $created = [];

        DB::transaction(function () use ($project_id, $floor_id, $unit_type_id, $prefix, $start, $count, $bua, $carpet, $area_unit, $facing, $base_rate, &$created) {
            for ($i = 0; $i < $count; $i++) {
                $num = $start + $i;
                $unitNumber = $prefix . $num;

                // check uniqueness
                $exists = Unit::where('project_id', $project_id)
                    ->where('unit_number', $unitNumber)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $unit = Unit::create([
                    'project_id' => $project_id,
                    'floor_id' => $floor_id,
                    'unit_type_id' => $unit_type_id,
                    'unit_number' => $unitNumber,
                    'bua_area' => $bua,
                    'carpet_area' => $carpet,
                    'area_unit' => $area_unit,
                    'facing' => $facing,
                    'status' => 'available',
                    'base_rate' => $base_rate,
                    'is_active' => true,
                ]);

                // Initial rate log
                $this->rateService->updateRate($unit, $base_rate, now()->toDateString(), 'Bulk creation');

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
        if (!Auth::user()->hasPermissionTo('units.rate.manage')) {
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
        if (!Auth::user()->hasPermissionTo('units.manage')) {
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
