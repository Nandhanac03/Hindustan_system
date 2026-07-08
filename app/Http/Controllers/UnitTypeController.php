<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\UnitType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UnitTypeController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::orderBy('name')->get();
        $selectedProjectId = $request->input('project_id', Project::where('is_active', true)->value('id') ?? ($projects->first()?->id));

        $unitTypes = UnitType::with(['project'])
            ->withCount('units')
            ->when($selectedProjectId, function ($q) use ($selectedProjectId) {
                $q->where(function ($sub) use ($selectedProjectId) {
                    $sub->whereNull('project_id')->orWhere('project_id', $selectedProjectId);
                });
            })
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('unit_types.index', compact('unitTypes', 'projects', 'selectedProjectId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'name'       => 'required|string|max:255',
            'category'   => 'required|string|max:100',
            'is_active'  => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        UnitType::create($validated);

        return redirect()->route('unit-types.index', ['project_id' => $request->project_id])
            ->with('success', 'Unit Type "' . $validated['name'] . '" added successfully.');
    }

    public function update(Request $request, UnitType $unitType): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'name'       => 'required|string|max:255',
            'category'   => 'required|string|max:100',
            'is_active'  => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $unitType->update($validated);

        return redirect()->route('unit-types.index', ['project_id' => $request->project_id])
            ->with('success', 'Unit Type updated successfully.');
    }

    public function destroy(UnitType $unitType): RedirectResponse
    {
        if ($unitType->units()->exists()) {
            return back()->with('error', 'Cannot delete "' . $unitType->name . '" because there are units linked to this unit type. You may deactivate it instead.');
        }

        $unitType->delete();

        return back()->with('success', 'Unit Type deleted successfully.');
    }
}
