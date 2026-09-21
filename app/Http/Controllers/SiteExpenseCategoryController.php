<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SiteExpenseCategory;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class SiteExpenseCategoryController extends Controller
{
    /**
     * Ensure the site_expense_categories table exists and has seed data.
     */
    protected function ensureTableExists(): void
    {
        if (!Schema::hasTable('site_expense_categories')) {
            Schema::create('site_expense_categories', function (Blueprint $table) {
                $table->id();
                $table->string('category_code', 50)->nullable();
                $table->string('category_name', 255);
                $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
                $table->text('description')->nullable();
                $table->string('status', 20)->default('active');
                $table->timestamps();
            });
        }
    }

    /**
     * Display a listing of site expense categories.
     */
    public function index(Request $request): View
    {
        $this->ensureTableExists();

        $projects = Project::orderBy('name')->get();

        $selectedProjectId = $request->input('project_id', '');
        $selectedStatus = $request->input('status', 'All');
        $search = $request->input('search', '');

        $query = SiteExpenseCategory::with('project');

        if ($selectedProjectId !== '' && $selectedProjectId !== null && $selectedProjectId !== 'All') {
            $query->where('project_id', $selectedProjectId);
        }

        if ($selectedStatus && $selectedStatus !== 'All') {
            $query->where('status', strtolower($selectedStatus));
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('category_name', 'like', "%{$search}%")
                  ->orWhere('category_code', 'like', "%{$search}%")
                  ->orWhereHas('project', function ($pQuery) use ($search) {
                      $pQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $categories = $query->orderBy('category_code', 'asc')->orderBy('category_name', 'asc')->get();

        $categoriesArray = $categories->map(function ($c) {
            return [
                'id'            => $c->id,
                'category_code' => $c->category_code ?? '',
                'category_name' => $c->category_name,
                'category'      => $c->category_name, // compatibility alias
                'project_id'    => (string)($c->project_id ?? ''),
                'project_name'  => $c->project->name ?? 'Unassigned (Global)',
                'description'   => $c->description ?? '',
                'status'        => $c->status ?? 'active',
                'created_at'    => $c->created_at ? $c->created_at->format('d-M-Y H:i') : '—',
            ];
        })->values();

        // Calculate KPI summary stats
        $totalCategories = SiteExpenseCategory::count();
        $activeCategories = SiteExpenseCategory::where('status', 'active')->count();
        $inactiveCategories = SiteExpenseCategory::where('status', 'inactive')->count();
        $globalCategories = SiteExpenseCategory::whereNull('project_id')->count();
        $assignedCount = SiteExpenseCategory::whereNotNull('project_id')->count();

        return view('site-expense-categories.index', compact(
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
     * Store a newly created site expense category.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->ensureTableExists();

        $validated = $request->validate([
            'category_code' => 'nullable|string|max:50',
            'category_name' => 'required|string|max:255',
            'project_id'    => 'nullable|exists:projects,id',
            'description'   => 'nullable|string|max:1000',
            'status'        => 'nullable|string|in:active,inactive',
        ]);

        if (empty($validated['status'])) {
            $validated['status'] = 'active';
        }

        $newCategory = SiteExpenseCategory::create($validated);

        return redirect()->route('site-expense-categories.index')
            ->with('success', 'Site Expense Category "' . $newCategory->category_name . '" added successfully.');
    }

    /**
     * Update the specified site expense category.
     */
    public function update(Request $request, SiteExpenseCategory $siteExpenseCategory): RedirectResponse
    {
        $validated = $request->validate([
            'category_code' => 'nullable|string|max:50',
            'category_name' => 'required|string|max:255',
            'project_id'    => 'nullable|exists:projects,id',
            'description'   => 'nullable|string|max:1000',
            'status'        => 'required|string|in:active,inactive',
        ]);

        $siteExpenseCategory->update($validated);

        return redirect()->route('site-expense-categories.index')
            ->with('success', 'Site Expense Category updated successfully.');
    }

    /**
     * Toggle status between active and inactive.
     */
    public function toggleStatus(SiteExpenseCategory $siteExpenseCategory): RedirectResponse
    {
        $newStatus = strtolower($siteExpenseCategory->status) === 'active' ? 'inactive' : 'active';
        $siteExpenseCategory->update(['status' => $newStatus]);

        return back()->with('success', 'Site Expense Category status changed to ' . ucfirst($newStatus) . '.');
    }

    /**
     * Remove the specified site expense category.
     */
    public function destroy(SiteExpenseCategory $siteExpenseCategory): RedirectResponse
    {
        $name = $siteExpenseCategory->category_name;
        $siteExpenseCategory->delete();

        return back()->with('success', 'Site Expense Category "' . $name . '" deleted successfully.');
    }
}
