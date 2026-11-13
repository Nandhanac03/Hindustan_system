<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ErpModule;
use App\Models\ErpSubModule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ModuleController extends Controller
{
    protected string $storagePath;

    public function __construct()
    {
        $this->storagePath = database_path('data/erp_modules.json');
    }

    /**
     * Fetch all modules and submodules from the database table (with JSON fallback)
     */
    protected function getModulesList(): array
    {
        try {
            if (Schema::hasTable('erp_modules')) {
                $modules = ErpModule::with(['subModules' => function ($q) {
                    $q->orderBy('order', 'asc')->orderBy('id', 'asc');
                }])->orderBy('order', 'asc')->orderBy('id', 'asc')->get();

                if ($modules->isNotEmpty()) {
                    return $modules->map(function ($m) {
                        return [
                            'id' => $m->id,
                            'name' => $m->name,
                            'code' => $m->code,
                            'section' => $m->section,
                            'icon' => $m->icon ?? 'folder',
                            'description' => $m->description ?? '',
                            'order' => (int) $m->order,
                            'status' => $m->status ?? 'active',
                            'sub_modules' => $m->subModules->map(function ($sm) {
                                $rawActions = is_array($sm->actions) ? $sm->actions : (json_decode($sm->actions, true) ?: ['view']);
                                $cleanActions = [];
                                foreach ($rawActions as $act) {
                                    if ($act === 'export') {
                                        if (!in_array('excel', $cleanActions, true)) $cleanActions[] = 'excel';
                                        if (!in_array('pdf', $cleanActions, true)) $cleanActions[] = 'pdf';
                                    } elseif (!in_array($act, $cleanActions, true)) {
                                        $cleanActions[] = $act;
                                    }
                                }
                                return [
                                    'id' => $sm->id,
                                    'erp_module_id' => $sm->erp_module_id,
                                    'name' => $sm->name,
                                    'code' => $sm->code,
                                    'route_name' => $sm->route_name ?? '',
                                    'actions' => $cleanActions,
                                    'description' => $sm->description ?? '',
                                    'order' => (int) $sm->order,
                                    'status' => $sm->status ?? 'active',
                                ];
                            })->values()->toArray(),
                        ];
                    })->values()->toArray();
                }
            }
        } catch (\Throwable $e) {
            // Fallback to JSON if database is temporarily unavailable
        }

        if (File::exists($this->storagePath)) {
            $content = File::get($this->storagePath);
            return json_decode($content, true) ?: [];
        }

        return [];
    }

    /**
     * Sync JSON cache for redundancy
     */
    protected function syncJsonCache(): void
    {
        try {
            if (Schema::hasTable('erp_modules')) {
                $modules = ErpModule::with(['subModules' => function ($q) {
                    $q->orderBy('order', 'asc')->orderBy('id', 'asc');
                }])->orderBy('order', 'asc')->orderBy('id', 'asc')->get();

                $export = $modules->map(function ($m) {
                    return [
                        'id' => $m->id,
                        'name' => $m->name,
                        'code' => $m->code,
                        'section' => $m->section,
                        'icon' => $m->icon ?? 'folder',
                        'description' => $m->description ?? '',
                        'order' => (int) $m->order,
                        'status' => $m->status ?? 'active',
                        'sub_modules' => $m->subModules->map(function ($sm) {
                            return [
                                'id' => $sm->id,
                                'name' => $sm->name,
                                'code' => $sm->code,
                                'route_name' => $sm->route_name ?? '',
                                'actions' => is_array($sm->actions) ? $sm->actions : (json_decode($sm->actions, true) ?: ['view']),
                                'description' => $sm->description ?? '',
                                'status' => $sm->status ?? 'active',
                            ];
                        })->values()->toArray(),
                    ];
                })->values()->toArray();

                File::ensureDirectoryExists(dirname($this->storagePath));
                File::put($this->storagePath, json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
        } catch (\Throwable $e) {
            // Silence sync errors
        }
    }

    public function index(Request $request): View
    {
        $modules = $this->getModulesList();

        $totalModules = count($modules);
        $totalSubModules = 0;
        $sections = [];

        foreach ($modules as $m) {
            $totalSubModules += count($m['sub_modules'] ?? []);
            if (!empty($m['section']) && !in_array($m['section'], $sections, true)) {
                $sections[] = $m['section'];
            }
        }

        $activeModulesCount = count(array_filter($modules, fn($m) => ($m['status'] ?? 'active') === 'active'));

        return view('admin.modules.index', [
            'modules' => $modules,
            'totalModules' => $totalModules,
            'totalSubModules' => $totalSubModules,
            'activeModulesCount' => $activeModulesCount,
            'sections' => $sections,
        ]);
    }

    public function storeModule(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:50'],
            'section' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ]);

        try {
            $maxOrder = ErpModule::max('order') ?? 0;

            $module = ErpModule::create([
                'name' => trim($validated['name']),
                'code' => trim($validated['code']),
                'section' => trim($validated['section']),
                'icon' => 'folder',
                'description' => $validated['description'] ?? null,
                'order' => $maxOrder + 1,
                'status' => $validated['status'] ?? 'active',
            ]);

            $this->syncJsonCache();

            $formattedModule = [
                'id' => $module->id,
                'name' => $module->name,
                'code' => $module->code,
                'section' => $module->section,
                'icon' => $module->icon,
                'description' => $module->description ?? '',
                'order' => (int) $module->order,
                'status' => $module->status,
                'sub_modules' => [],
            ];

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Module "' . $module->name . '" created in database successfully.',
                    'module' => $formattedModule,
                ]);
            }

            return redirect()->route('admin.modules.index')->with('success', 'Module created successfully.');
        } catch (\Throwable $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
            }
            return redirect()->route('admin.modules.index')->with('error', 'Error creating module: ' . $e->getMessage());
        }
    }

    public function updateModule(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:50'],
            'section' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ]);

        try {
            $module = ErpModule::findOrFail($id);
            $module->update([
                'name' => trim($validated['name']),
                'code' => trim($validated['code']),
                'section' => trim($validated['section']),
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'] ?? 'active',
            ]);

            $this->syncJsonCache();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Module updated successfully in database.',
                ]);
            }

            return redirect()->route('admin.modules.index')->with('success', 'Module updated successfully.');
        } catch (\Throwable $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
            }
            return redirect()->route('admin.modules.index')->with('error', 'Error updating module: ' . $e->getMessage());
        }
    }

    public function destroyModule(Request $request, int $id): JsonResponse|RedirectResponse
    {
        try {
            $module = ErpModule::findOrFail($id);
            $name = $module->name;
            $module->delete();

            $this->syncJsonCache();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Module "' . $name . '" deleted from database.',
                ]);
            }

            return redirect()->route('admin.modules.index')->with('success', 'Module deleted successfully.');
        } catch (\Throwable $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
            }
            return redirect()->route('admin.modules.index')->with('error', 'Error deleting module.');
        }
    }

    public function toggleModuleStatus(Request $request, int $id): JsonResponse
    {
        try {
            $module = ErpModule::findOrFail($id);
            $module->status = $module->status === 'active' ? 'inactive' : 'active';
            $module->save();

            $this->syncJsonCache();

            return response()->json([
                'success' => true,
                'status' => $module->status,
                'message' => "Module status updated to {$module->status}.",
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function storeSubModule(Request $request, int $moduleId): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:50'],
            'route_name' => ['nullable', 'string', 'max:100'],
            'actions' => ['nullable', 'array'],
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ]);

        try {
            $module = ErpModule::findOrFail($moduleId);
            $maxOrder = ErpSubModule::where('erp_module_id', $moduleId)->max('order') ?? 0;

            $rawActions = $validated['actions'] ?? ['view', 'create', 'edit', 'delete', 'excel', 'pdf'];
            $cleanActions = [];
            foreach ($rawActions as $act) {
                if ($act === 'export') {
                    if (!in_array('excel', $cleanActions, true)) $cleanActions[] = 'excel';
                    if (!in_array('pdf', $cleanActions, true)) $cleanActions[] = 'pdf';
                } elseif (!in_array($act, $cleanActions, true)) {
                    $cleanActions[] = $act;
                }
            }

            $subModule = ErpSubModule::create([
                'erp_module_id' => $module->id,
                'name' => trim($validated['name']),
                'code' => trim($validated['code']),
                'route_name' => trim($validated['route_name'] ?? ''),
                'actions' => $cleanActions,
                'description' => $validated['description'] ?? null,
                'order' => $maxOrder + 1,
                'status' => $validated['status'] ?? 'active',
            ]);

            $this->syncJsonCache();

            $formatted = [
                'id' => $subModule->id,
                'erp_module_id' => $subModule->erp_module_id,
                'name' => $subModule->name,
                'code' => $subModule->code,
                'route_name' => $subModule->route_name ?? '',
                'actions' => $cleanActions,
                'description' => $subModule->description ?? '',
                'order' => (int) $subModule->order,
                'status' => $subModule->status,
            ];

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sub-module "' . $subModule->name . '" saved to database successfully.',
                    'sub_module' => $formatted,
                ]);
            }

            return redirect()->route('admin.modules.index')->with('success', 'Sub-module created successfully.');
        } catch (\Throwable $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
            }
            return redirect()->route('admin.modules.index')->with('error', 'Error creating sub-module: ' . $e->getMessage());
        }
    }

    public function updateSubModule(Request $request, int $moduleId, int $subModuleId): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:50'],
            'route_name' => ['nullable', 'string', 'max:100'],
            'actions' => ['nullable', 'array'],
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ]);

        try {
            $rawActions = $validated['actions'] ?? ['view'];
            $cleanActions = [];
            foreach ($rawActions as $act) {
                if ($act === 'export') {
                    if (!in_array('excel', $cleanActions, true)) $cleanActions[] = 'excel';
                    if (!in_array('pdf', $cleanActions, true)) $cleanActions[] = 'pdf';
                } elseif (!in_array($act, $cleanActions, true)) {
                    $cleanActions[] = $act;
                }
            }

            $subModule = ErpSubModule::where('erp_module_id', $moduleId)->where('id', $subModuleId)->firstOrFail();
            $subModule->update([
                'name' => trim($validated['name']),
                'code' => trim($validated['code']),
                'route_name' => trim($validated['route_name'] ?? ''),
                'actions' => $cleanActions,
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'] ?? 'active',
            ]);

            $this->syncJsonCache();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sub-module updated successfully in database.',
                ]);
            }

            return redirect()->route('admin.modules.index')->with('success', 'Sub-module updated successfully.');
        } catch (\Throwable $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
            }
            return redirect()->route('admin.modules.index')->with('error', 'Error updating sub-module: ' . $e->getMessage());
        }
    }

    public function destroySubModule(Request $request, int $moduleId, int $subModuleId): JsonResponse|RedirectResponse
    {
        try {
            $subModule = ErpSubModule::where('erp_module_id', $moduleId)->where('id', $subModuleId)->firstOrFail();
            $name = $subModule->name;
            $subModule->delete();

            $this->syncJsonCache();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sub-module "' . $name . '" deleted from database.',
                ]);
            }

            return redirect()->route('admin.modules.index')->with('success', 'Sub-module deleted successfully.');
        } catch (\Throwable $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
            }
            return redirect()->route('admin.modules.index')->with('error', 'Error deleting sub-module.');
        }
    }

    public function toggleSubModuleStatus(Request $request, int $moduleId, int $subModuleId): JsonResponse
    {
        try {
            $subModule = ErpSubModule::where('erp_module_id', $moduleId)->where('id', $subModuleId)->firstOrFail();
            $subModule->status = $subModule->status === 'active' ? 'inactive' : 'active';
            $subModule->save();

            $this->syncJsonCache();

            return response()->json([
                'success' => true,
                'status' => $subModule->status,
                'message' => "Sub-module status updated to {$subModule->status}.",
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
