<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FloorController extends Controller
{
    public function index(Request $request): View
    {
        // Fetch projects for dropdown
        $projects = Project::orderBy('name')->get();
        $activeProject = Project::where('is_active', true)->first();

        // Selected project filter
        $selectedProjectId = $request->input('project_id', $activeProject?->id ?? ($projects->first()?->id));

        $floors = Floor::with('project')
            ->when($selectedProjectId, fn ($query) => $query->where('project_id', $selectedProjectId))
            ->withCount('units')
            ->orderBy('floor_number')
            ->get();

        return view('floors.index', compact('floors', 'projects', 'selectedProjectId', 'activeProject'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id'   => 'required|exists:projects,id',
            'floor_number' => 'required|integer',
            'name'         => 'required|string|max:255',
        ]);

        // Check if floor number already exists for this project
        $exists = Floor::where('project_id', $validated['project_id'])
            ->where('floor_number', $validated['floor_number'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Floor number ' . $validated['floor_number'] . ' already exists for the selected project.');
        }

        Floor::create($validated);

        return redirect()->route('floors.index', ['project_id' => $validated['project_id']])
            ->with('success', 'Floor "' . $validated['name'] . '" added successfully.');
    }

    public function update(Request $request, Floor $floor): RedirectResponse
    {
        $validated = $request->validate([
            'floor_number' => 'required|integer',
            'name'         => 'required|string|max:255',
        ]);

        // Check unique constraint for floor_number
        $exists = Floor::where('project_id', $floor->project_id)
            ->where('floor_number', $validated['floor_number'])
            ->where('id', '!=', $floor->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Floor number ' . $validated['floor_number'] . ' already exists on this project.');
        }

        $floor->update($validated);

        return redirect()->route('floors.index', ['project_id' => $floor->project_id])
            ->with('success', 'Floor updated successfully.');
    }

    public function destroy(Floor $floor): RedirectResponse
    {
        $projectId = $floor->project_id;

        if ($floor->units()->exists()) {
            return back()->with('error', 'Cannot delete "' . $floor->name . '" because there are units assigned to this floor.');
        }

        $floorName = $floor->name;
        $floor->delete();

        return redirect()->route('floors.index', ['project_id' => $projectId])
            ->with('success', 'Floor "' . $floorName . '" deleted successfully.');
    }
}
