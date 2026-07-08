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
            $query = Unit::with(['floor', 'unitType', 'rateLogs.user', 'booking'])->where('project_id', $project->id);

            if ($request->filled('search')) {
                $query->where('door_no', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('floor_id')) {
                $query->where('floor_id', $request->floor_id);
            }

            if ($request->filled('status')) {
                if ($request->status === 'recently_added') {
                    // Do not filter by status, just sort later
                } else {
                    $query->where('status', $request->status);
                }
            }

            if ($request->filled('unit_type_id')) {
                $query->where('unit_type_id', $request->unit_type_id);
            }

            if ($request->filled('status') && $request->status === 'recently_added') {
                $query->latest();
            } else {
                $query->orderBy('door_no');
            }

            $units = $query->paginate(10);

            return response()->json([
                'units' => $units->items(),
                'pagination' => [
                    'current_page' => $units->currentPage(),
                    'last_page' => $units->lastPage(),
                    'total' => $units->total(),
                ]
            ]);
        }

        return view('units.index', compact('project', 'floors', 'unitTypes', 'projects'));
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
            'door_no' => ['required', 'string', 'max:255'],
            'built_up_area' => ['required', 'numeric', 'min:0.01'],
            'carpet_area' => ['nullable', 'numeric', 'min:0'],
            'expected_rate_per_sqft' => ['required', 'numeric', 'min:0'],
        ]);

        // Check unique door_no in project
        $exists = Unit::where('project_id', $validated['project_id'])
            ->where('door_no', $validated['door_no'])
            ->exists();
        if ($exists) {
            return response()->json(['errors' => ['door_no' => ['The door number has already been taken in this project.']]], 422);
        }

        $unit = null;
        DB::transaction(function () use ($validated, &$unit) {
            $expectedRate = (float)$validated['expected_rate_per_sqft'];
            $validated['expected_sale_amount'] = (float)$validated['built_up_area'] * $expectedRate;
            $validated['status'] = 'available';

            $unit = Unit::create($validated);

            // Record initial rate
            $this->rateService->updateRate($unit, $expectedRate, now()->toDateString(), 'Initial Rate');

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

        $unit->load(['unitType', 'floor', 'rateLogs.user', 'statusLogs.user', 'booking']);

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
            'door_no' => ['required', 'string', 'max:255'],
            'built_up_area' => ['required', 'numeric', 'min:0.01'],
            'carpet_area' => ['nullable', 'numeric', 'min:0'],
        ]);

        // Check unique door_no excluding current unit
        $exists = Unit::where('project_id', $unit->project_id)
            ->where('door_no', $validated['door_no'])
            ->where('id', '!=', $unit->id)
            ->exists();
        if ($exists) {
            return response()->json(['errors' => ['door_no' => ['The door number has already been taken in this project.']]], 422);
        }

        // recalculate expected sale amount
        $validated['expected_sale_amount'] = (float)$validated['built_up_area'] * (float)($unit->expected_rate_per_sqft ?? 0.0);
        if ($unit->sale_rate_per_sqft) {
            $validated['sale_amount'] = (float)$validated['built_up_area'] * (float)$unit->sale_rate_per_sqft;
            $validated['difference'] = $validated['expected_sale_amount'] - $validated['sale_amount'];
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
            'built_up_area' => ['required', 'numeric', 'min:0.01'],
            'carpet_area' => ['nullable', 'numeric', 'min:0'],
            'expected_rate_per_sqft' => ['required', 'numeric', 'min:0'],
        ]);

        $project_id = (int)$request->project_id;
        $floor_id = (int)$request->floor_id;
        $unit_type_id = (int)$request->unit_type_id;
        $prefix = $request->unit_prefix ?? '';
        $start = (int)$request->start_number;
        $count = (int)$request->count;
        $built_up_area = (float)$request->built_up_area;
        $carpet = $request->carpet_area ? (float)$request->carpet_area : null;
        $expected_rate_per_sqft = (float)$request->expected_rate_per_sqft;

        $created = [];

        DB::transaction(function () use ($project_id, $floor_id, $unit_type_id, $prefix, $start, $count, $built_up_area, $carpet, $expected_rate_per_sqft, &$created) {
            for ($i = 0; $i < $count; $i++) {
                $num = $start + $i;
                $unitNumber = $prefix . $num;

                // check uniqueness
                $exists = Unit::where('project_id', $project_id)
                    ->where('door_no', $unitNumber)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $expectedSaleAmount = $built_up_area * $expected_rate_per_sqft;

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
                $this->rateService->updateRate($unit, $expected_rate_per_sqft, now()->toDateString(), 'Bulk creation');

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
