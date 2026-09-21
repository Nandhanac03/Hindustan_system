<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request): View
    {
        $projects = Project::orderBy('name')->get();
        
        $selectedProjectId = $request->input('project_id', '');
        $selectedStatus = $request->input('status', 'All');
        $search = $request->input('search', '');

        $query = Category::with('project');

        if ($selectedProjectId !== '' && $selectedProjectId !== null && $selectedProjectId !== 'All') {
            $query->where('project_id', $selectedProjectId);
        }

        if ($selectedStatus && $selectedStatus !== 'All') {
            $query->where('status', strtolower($selectedStatus));
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('category', 'like', "%{$search}%")
                  ->orWhereHas('project', function ($pQuery) use ($search) {
                      $pQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $categories = $query->orderBy('category', 'asc')->get();

        $categoriesArray = $categories->map(function ($c) {
            return [
                'id'           => $c->id,
                'category'     => $c->category,
                'project_id'   => (string)($c->project_id ?? ''),
                'project_name' => $c->project->name ?? 'Unassigned (Global)',
                'status'       => $c->status ?? 'active',
                'created_at'   => $c->created_at ? $c->created_at->format('d-M-Y H:i') : '—',
            ];
        })->values();

        // Calculate KPI summary stats
        $totalCategories = Category::count();
        $activeCategories = Category::where('status', 'active')->count();
        $inactiveCategories = Category::where('status', 'inactive')->count();
        $globalCategories = Category::whereNull('project_id')->count();
        $assignedCount = Category::whereNotNull('project_id')->count();

        return view('categories.index', compact(
            'categories',
            'categoriesArray',
            'projects',
            'selectedProjectId',
            'selectedStatus',
            'search',
            'totalCategories',
            'activeCategories',
            'inactiveCategories',
            'globalCategories',
            'assignedCount'
        ));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category'   => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'status'     => 'nullable|string|in:active,inactive',
        ]);

        if (empty($validated['status'])) {
            $validated['status'] = 'active';
        }

        $newCategory = Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category "' . $newCategory->category . '" added successfully.');
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'category'   => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'status'     => 'required|string|in:active,inactive',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Toggle status between active and inactive.
     */
    public function toggleStatus(Category $category): RedirectResponse
    {
        $newStatus = strtolower($category->status) === 'active' ? 'inactive' : 'active';
        $category->update(['status' => $newStatus]);

        return back()->with('success', 'Category status changed to ' . ucfirst($newStatus) . '.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $categoryName = $category->category;
        $category->delete();

        return back()->with('success', 'Category "' . $categoryName . '" deleted successfully.');
    }
}
