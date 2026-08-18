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
        $user = Auth::user();
        $projects = Project::withCount('units')->latest()->paginate(50);

        foreach ($projects as $project) {
            $project->available_units_count = Unit::where('project_id', $project->id)
                ->where('status', 'available')
                ->count();
        }

        $systems = collect();
        if ($user->hasPermissionTo('projects.manage')) {
            if ($user->hasMultiSystemAccess()) {
                $systems = System::where('is_active', true)->get();
            } else {
                $systems = System::where('id', $user->system_id)->get();
            }
        }

        return view('projects.index', compact('projects', 'systems'));
    }

    public function create(): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->hasPermissionTo('projects.manage')) {
            abort(403, 'Unauthorized action.');
        }

        return redirect()->route('projects.index', ['open_create' => 1]);
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
            'total_floors' => ['required', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'expected_completion_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:planning,ongoing,completed,on_hold'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg,bmp,heic', 'max:10240'],
        ];

        if (!$request->filled('system_id')) {
            $request->merge(['system_id' => $user->system_id ?? 1]);
        }

        $rules['system_id'] = ['nullable', 'exists:systems,id'];

        $validated = $request->validate($rules);

        if (empty($validated['system_id'])) {
            $validated['system_id'] = $user->system_id ?? 1;
        }

        // Upload image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $validated['image_url'] = $path;

            // Shared hosting copy fallback to ensure public availability on servers
            $this->syncToPublicStorage($path);
        }

        // Generate short code
        $words = explode(' ', $validated['name']);
        $code = '';
        foreach ($words as $word) {
            if (!empty($word)) {
                $code .= strtoupper(substr($word, 0, 1));
            }
        }
        $validated['code'] = $code;

        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('status', 'Project created successfully.');
    }

    public function show(Project $project): View
    {
        $project->load(['floors.units.unitType', 'floors.units.rateLogs.user', 'floors.units.statusLogs.user', 'partnerShares.partner']);
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
            'code' => ['nullable', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state_or_emirate' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'total_floors' => ['required', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'expected_completion_date' => ['nullable', 'date'],
            'status' => ['required', 'in:planning,ongoing,completed,on_hold'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,svg,bmp,heic', 'max:10240'],
        ]);

        // Upload new image
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if (!empty($project->image_url)) {
                if (Storage::disk('public')->exists($project->image_url)) {
                    Storage::disk('public')->delete($project->image_url);
                }
                if (file_exists(public_path('storage/' . $project->image_url))) {
                    @unlink(public_path('storage/' . $project->image_url));
                }
            }

            // Store new image
            $path = $request->file('image')->store('projects', 'public');
            $validated['image_url'] = $path;

            // Shared hosting copy fallback to ensure public availability on servers
            $this->syncToPublicStorage($path);
        }

        // Update project
        $project->update($validated);

        return redirect()
            ->route('projects.index')
            ->with('status', 'Project details updated successfully.');
    }

    /**
     * Shared hosting fallback helper to mirror uploaded files to public/storage
     */
    private function syncToPublicStorage(string $path): void
    {
        try {
            $source = storage_path('app/public/' . $path);
            $target = public_path('storage/' . $path);

            if (!file_exists($source)) {
                return;
            }

            $targetDir = dirname($target);
            if (!file_exists($targetDir)) {
                @mkdir($targetDir, 0755, true);
            }

            if (!file_exists($target) || (realpath($source) !== realpath($target))) {
                @copy($source, $target);
            }
        } catch (\Throwable $e) {
            // Silence permission errors if symlink handles it
        }
    }

    public function bulkGenerateShow(Project $project): View
    {
        if (!Auth::user()->hasPermissionTo('projects.manage')) {
            abort(403);
        }

        $unitTypes = UnitType::where('is_active', true)->get();

        return view('project.bulk-generate', compact('project', 'unitTypes'));
    }

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
            'bua_area' => ['nullable', 'numeric', 'min:0'],
            'carpet_area' => ['nullable', 'numeric', 'min:0'],
            'base_rate' => ['required', 'numeric', 'min:0'],
            'facing' => ['nullable', 'string', 'max:50'],
        ]);

        $start = (int)$request->start_floor;
        $end = (int)$request->end_floor;
        $unitsPerFloor = (int)$request->units_per_floor;
        $prefix = $request->unit_prefix ?? '';
        $buaArea = $request->filled('bua_area') ? (float)$request->bua_area : null;
        $carpetArea = $request->filled('carpet_area') ? (float)$request->carpet_area : null;
        $baseRate = (float)($request->base_rate ?? 0);

        DB::transaction(function () use ($project, $request, $start, $end, $unitsPerFloor, $prefix, $buaArea, $carpetArea, $baseRate) {
            for ($f = $start; $f <= $end; $f++) {
                if ($f < 0) {
                    $floorName = "Basement " . abs($f);
                } elseif ($f === 0) {
                    $floorName = "Ground Floor";
                } else {
                    $floorName = "Floor " . $f;
                }

                $floor = Floor::firstOrCreate(
                    [
                        'project_id' => $project->id,
                        'floor_number' => $f,
                    ],
                    [
                        'name' => $floorName,
                    ]
                );

                for ($u = 1; $u <= $unitsPerFloor; $u++) {
                    $floorPrefix = Floor::getDoorPrefix($f);
                    $unitNumber = trim($prefix . ' ' . $floorPrefix) . ' ' . $u;

                    $exists = Unit::where('project_id', $project->id)
                        ->where('floor_id', $floor->id)
                        ->where('unit_type_id', $request->unit_type_id)
                        ->where('door_no', $unitNumber)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    $expectedSaleAmount = $buaArea !== null ? ($buaArea * $baseRate) : null;

                    $unit = Unit::create([
                        'project_id' => $project->id,
                        'floor_id' => $floor->id,
                        'unit_type_id' => $request->unit_type_id,
                        'door_no' => $unitNumber,
                        'built_up_area' => $buaArea,
                        'carpet_area' => $carpetArea,
                        'expected_rate_per_sqft' => $baseRate,
                        'expected_sale_amount' => $expectedSaleAmount,
                        'status' => 'available',
                    ]);

                    UnitRateLog::create([
                        'unit_id' => $unit->id,
                        'rate' => $request->base_rate,
                        'effective_from' => now()->toDateString(),
                        'changed_by' => Auth::id(),
                        'reason' => 'Bulk floor/unit generation',
                    ]);

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

        return redirect()->route('projects.show', $project->id)
            ->with('status', 'Floors and units generated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->hasPermissionTo('projects.manage')) {
            abort(403);
        }

        if (!empty($project->image_url)) {
            if (Storage::disk('public')->exists($project->image_url)) {
                Storage::disk('public')->delete($project->image_url);
            }
            if (file_exists(public_path('storage/' . $project->image_url))) {
                @unlink(public_path('storage/' . $project->image_url));
            }
        }

        DB::transaction(function () use ($project) {
            $unitIds = $project->units()->pluck('id');
            UnitStatusLog::whereIn('unit_id', $unitIds)->delete();
            UnitRateLog::whereIn('unit_id', $unitIds)->delete();

            $project->units()->delete();
            $project->floors()->delete();
            $project->delete();
        });

        return redirect()->route('projects.index')
            ->with('status', 'Project deleted successfully.');
    }
}
