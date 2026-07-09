<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Project;
use App\Models\System;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\UnitRateLog;
use App\Models\UnitStatusLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(): View
    {
        // SystemScope auto-scopes by logged-in user unless Owner
        $projects = Project::withCount('units')->paginate(12);

        // Fetch counts of available units per project
        foreach ($projects as $project) {
            $project->available_units_count = Unit::where('project_id', $project->id)
                ->where('status', 'available')
                ->count();
        }

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        $user = Auth::user();
        if (!$user->hasPermissionTo('projects.manage')) {
            abort(403, 'Unauthorized action.');
        }

        // Owners can choose any system, others are locked to their own
        if ($user->hasMultiSystemAccess()) {
            $systems = System::where('is_active', true)->get();
        } else {
            $systems = System::where('id', $user->system_id)->get();
        }

        return view('projects.create', compact('systems'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->hasPermissionTo('projects.manage')) {
            abort(403);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state_or_emirate' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'rera_number' => ['nullable', 'string', 'max:100'],
            'total_floors' => ['required', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'expected_completion_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:planning,ongoing,completed,on_hold'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        // Owner must supply system_id. Others use their system_id
        if ($user->hasMultiSystemAccess()) {
            $rules['system_id'] = ['required', 'exists:systems,id'];
        }

        $validated = $request->validate($rules);

        if (!$user->hasMultiSystemAccess()) {
            $validated['system_id'] = $user->system_id;
        }

        // Upload image
        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('projects', 'public');
        }

        // Generate short code
        $codePrefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $validated['name']), 0, 3));
        $count = Project::withoutGlobalScopes()->where('system_id', $validated['system_id'])->count() + 1;
        $validated['code'] = "{$codePrefix}-" . sprintf('%02d', $count);

        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('status', 'Project created successfully.');
    }

    public function show(Project $project): View
    {
        // View unit details, floor configuration and bulk tools
        $project->load(['floors.units.unitType', 'floors.units.rateLogs.user', 'floors.units.statusLogs.user']);
        $unitTypes = UnitType::where('is_active', true)->get();

        return view('projects.show', compact('project', 'unitTypes'));
    }

    public function edit(Project $project): View
    {
        $user = Auth::user();
        if (!$user->hasPermissionTo('projects.manage')) {
            abort(403);
        }

        return view('projects.edit', compact('project'));
    }

public function update(Request $request, Project $project): RedirectResponse
{
    $user = Auth::user();

    if (!$user->hasPermissionTo('projects.manage')) {
        abort(403);
    }

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'location' => ['required', 'string', 'max:255'],
        'city' => ['required', 'string', 'max:100'],
        'state_or_emirate' => ['required', 'string', 'max:100'],
        'country' => ['required', 'string', 'max:100'],
        'total_floors' => ['required', 'integer', 'min:1'],
        'start_date' => ['nullable', 'date'],
        'expected_completion_date' => ['nullable', 'date'],
        'status' => ['required', 'in:planning,ongoing,completed,on_hold'],
        'description' => ['nullable', 'string'],
        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    // Upload new image
    if ($request->hasFile('image')) {

        // Delete old image
        if (!empty($project->image_url) && Storage::disk('public')->exists($project->image_url)) {
            Storage::disk('public')->delete($project->image_url);
        }

        // Store new image
        $validated['image_url'] = $request->file('image')->store('projects', 'public');
    }

    // Update project
    $project->update($validated);

    return redirect()
        ->route('projects.index')
        ->with('status', 'Project details updated successfully.');
}

    /**
     * Show bulk generate form
     */
    public function bulkGenerateShow(Project $project): View
    {
        if (!Auth::user()->hasPermissionTo('projects.manage')) {
            abort(403);
        }

        $unitTypes = UnitType::where('is_active', true)->get();

        return view('project.bulk-generate', compact('project', 'unitTypes'));
    }

    /**
     * Store bulk generated floors and units
     */
    public function bulkGenerateStore(Request $request, Project $project): RedirectResponse
    {
        if (!Auth::user()->hasPermissionTo('projects.manage')) {
            abort(403);
        }

        $request->validate([
            'start_floor' => ['required', 'integer'],
            'end_floor' => ['required', 'integer', 'gte:start_floor'],
            'units_per_floor' => ['required', 'integer', 'min:1'],
            'unit_type_id' => ['required', 'exists:unit_types,id'],
            'unit_prefix' => ['nullable', 'string', 'max:10'],
            'bua_area' => ['required', 'numeric', 'min:0.01'],
            'carpet_area' => ['nullable', 'numeric', 'min:0'],
            'base_rate' => ['required', 'numeric', 'min:0'],
            'facing' => ['nullable', 'string', 'max:50'],
        ]);

        $start = (int)$request->start_floor;
        $end = (int)$request->end_floor;
        $unitsPerFloor = (int)$request->units_per_floor;
        $prefix = $request->unit_prefix ?? '';

        DB::transaction(function () use ($project, $request, $start, $end, $unitsPerFloor, $prefix) {
            for ($f = $start; $f <= $end; $f++) {
                // Determine floor name
                if ($f < 0) {
                    $floorName = "Basement " . abs($f);
                } elseif ($f === 0) {
                    $floorName = "Ground Floor";
                } else {
                    $floorName = "Floor " . $f;
                }

                // Create floor
                $floor = Floor::firstOrCreate(
                    [
                        'project_id' => $project->id,
                        'floor_number' => $f,
                    ],
                    [
                        'name' => $floorName,
                    ]
                );

                // Create units for this floor
                for ($u = 1; $u <= $unitsPerFloor; $u++) {
                    $floorStr = $f < 0 ? 'B' . abs($f) : (string)$f;
                    $unitNumber = $prefix . $floorStr . sprintf('%02d', $u);

                    // Check unique constraint to avoid duplicates
                    $exists = Unit::where('project_id', $project->id)
                        ->where('door_no', $unitNumber)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    $expectedSaleAmount = (float)$request->bua_area * (float)$request->base_rate;

                    $unit = Unit::create([
                        'project_id' => $project->id,
                        'floor_id' => $floor->id,
                        'unit_type_id' => $request->unit_type_id,
                        'door_no' => $unitNumber,
                        'built_up_area' => $request->bua_area,
                        'carpet_area' => $request->carpet_area,
                        'expected_rate_per_sqft' => $request->base_rate,
                        'expected_sale_amount' => $expectedSaleAmount,
                        'status' => 'available',
                    ]);

                    // Append initial rate log
                    UnitRateLog::create([
                        'unit_id' => $unit->id,
                        'rate' => $request->base_rate,
                        'effective_from' => now()->toDateString(),
                        'changed_by' => Auth::id(),
                        'reason' => 'Bulk floor/unit generation',
                    ]);

                    // Append initial status log
                    UnitStatusLog::create([
                        'unit_id' => $unit->id,
                        'from_status' => null,
                        'to_status' => 'available',
                        'changed_by' => Auth::id(),
                        'reason' => 'Bulk floor/unit generation',
                    ]);
                }
            }
        });

        return redirect()->route('project.show', $project->id)
            ->with('status', 'Floors and units generated successfully.');
    }
}
