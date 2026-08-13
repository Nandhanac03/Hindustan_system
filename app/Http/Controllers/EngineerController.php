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
        $query = Engineer::with('project')->orderBy('engineer_code');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('engineer_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->input('project_id'));
        }

        $engineers = $query->paginate(20)->withQueryString();
        $projects  = Project::orderBy('name')->get();

        $all = Engineer::all();
        $totalEngineers  = $all->count();
        $activeEngineers = $all->where('is_active', true)->count();
        $assignedCount   = $all->whereNotNull('project_id')->count();

        return view('engineers.index', compact(
            'engineers',
            'projects',
            'totalEngineers',
            'activeEngineers',
            'assignedCount'
        ));
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
