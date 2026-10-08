<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Engineer;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EngineerController extends Controller
{
    public function index(Request $request): View
    {
        $engineers = Engineer::with('project')->orderBy('id', 'desc')->get();
        $projects  = Project::orderBy('name')->get();

        $totalEngineers  = $engineers->count();
        $activeEngineers = $engineers->where('is_active', true)->count();
        $assignedCount   = $engineers->whereNotNull('project_id')->count();

        return view('engineers.index', [
            'engineers' => $engineers,
            'engineersArray' => $engineers->map(function ($eng) {
                return [
                    'id' => $eng->id,
                    'engineer_code' => $eng->engineer_code,
                    'name' => $eng->name,
                    'email' => $eng->email ?? '',
                    'phone' => $eng->phone ?? '',
                    'designation' => $eng->designation,
                    'specialization' => $eng->specialization ?? '',
                    'project_id' => $eng->project_id ? (string)$eng->project_id : '',
                    'project_name' => $eng->project->name ?? 'Unassigned (Global)',
                    'is_active' => (bool) $eng->is_active,
                ];
            })->values()->toArray(),
            'projects' => $projects,
            'projectsArray' => $projects->map(fn($p) => ['id' => (string)$p->id, 'name' => $p->name])->values()->toArray(),
            'totalEngineers' => $totalEngineers,
            'activeEngineers' => $activeEngineers,
            'assignedCount' => $assignedCount,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'engineer_code'  => 'required|string|max:20|unique:engineers,engineer_code',
            'name'           => 'required|string|max:100',
            'email'          => 'nullable|email|max:100',
            'phone'          => 'nullable|string|max:20',
            'designation'    => 'required|string|max:100',
            'specialization' => 'nullable|string|max:100',
            'project_id'     => 'nullable|exists:projects,id',
            'is_active'      => 'nullable|boolean',
        ]);

        $validated['engineer_code'] = strtoupper(trim($validated['engineer_code']));
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        Engineer::create($validated);

        return redirect()->route('engineers.index')
            ->with('success', 'Engineer created successfully.');
    }

    public function update(Request $request, Engineer $engineer): RedirectResponse
    {
        $validated = $request->validate([
            'engineer_code'  => 'required|string|max:20|unique:engineers,engineer_code,' . $engineer->id,
            'name'           => 'required|string|max:100',
            'email'          => 'nullable|email|max:100',
            'phone'          => 'nullable|string|max:20',
            'designation'    => 'required|string|max:100',
            'specialization' => 'nullable|string|max:100',
            'project_id'     => 'nullable|exists:projects,id',
            'is_active'      => 'nullable|boolean',
        ]);

        $validated['engineer_code'] = strtoupper(trim($validated['engineer_code']));
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : false;

        $engineer->update($validated);

        return redirect()->route('engineers.index')
            ->with('success', 'Engineer updated successfully.');
    }

    public function destroy(Engineer $engineer): RedirectResponse
    {
        $name = $engineer->name;
        $engineer->delete();

        return redirect()->route('engineers.index')
            ->with('success', "Engineer '{$name}' deleted successfully.");
    }

    public function toggleStatus(Engineer $engineer): RedirectResponse
    {
        $engineer->update(['is_active' => !$engineer->is_active]);

        $statusStr = $engineer->is_active ? 'activated' : 'deactivated';
        return redirect()->route('engineers.index')
            ->with('success', "Engineer '{$engineer->name}' {$statusStr}.");
    }
}
