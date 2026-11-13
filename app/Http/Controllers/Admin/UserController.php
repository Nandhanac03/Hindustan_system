<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ErpModule;
use App\Models\ErpSubModule;
use App\Models\System;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');
        $statusFilter = $request->input('status');

        $baseQuery = User::withoutGlobalScopes();
        $totalUsers = (clone $baseQuery)->count();
        $activeUsers = (clone $baseQuery)->where('status', 'active')->count();
        $inactiveUsers = (clone $baseQuery)->where('status', '!=', 'active')->count();

        $roles = Role::all();
        $rolesCount = $roles->count();
        $systems = System::where('is_active', true)->get();

        $query = User::withoutGlobalScopes()->with(['roles', 'system']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        if (!empty($roleFilter) && $roleFilter !== 'all') {
            $query->whereHas('roles', function ($q) use ($roleFilter) {
                $q->where('name', $roleFilter);
            });
        }

        if (!empty($statusFilter) && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $query->orderBy('id', 'asc');

        $perPage = (int) $request->input('per_page', 5);
        $users = $query->paginate($perPage)->withQueryString();

        $allUsers = User::withoutGlobalScopes()->with(['roles', 'system'])->orderBy('id', 'asc')->get();
        $formattedUsers = $allUsers->map(function ($u) {
            $words = explode(' ', trim($u->name));
            $initials = count($words) >= 2 
                ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
                : strtoupper(substr($u->name, 0, 2));

            $avatarColors = [
                'bg-[#a38c29]', 'bg-purple-600', 'bg-slate-800', 'bg-[#09876B]',
                'bg-sky-600', 'bg-indigo-600', 'bg-amber-600', 'bg-rose-600'
            ];
            $avatarBg = $avatarColors[$u->id % count($avatarColors)];

            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'phone' => $u->phone ?? '',
                'employee_code' => $u->employee_code ?? '',
                'system_id' => $u->system_id,
                'system_name' => $u->system ? ($u->system->name . ' (' . $u->system->code . ')') : 'Global Admin',
                'status' => $u->status ?? 'active',
                'last_login_formatted' => $u->last_login_at ? $u->last_login_at->format('d M Y, h:i A') : 'Never',
                'initials' => $initials,
                'avatar_bg' => $avatarBg,
                'roles' => $u->roles->map(fn($r) => ['id' => $r->id, 'name' => $r->name])->values(),
                'role_names' => $u->roles->pluck('name')->toArray(),
            ];
        });

        $permissions = Permission::all();

        return view('admin.users.index', compact(
            'users',
            'formattedUsers',
            'roles',
            'systems',
            'permissions',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'rolesCount',
            'search',
            'roleFilter',
            'statusFilter'
        ));
    }

    public function create(): View
    {
        $roles = Role::all();
        $systems = System::where('is_active', true)->get();

        return view('admin.users.create', compact('roles', 'systems'));
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'username' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
            'employee_code' => ['nullable', 'string', 'max:50', 'unique:users,employee_code'],
            'system_id' => ['nullable'],
            'password' => ['required', 'string', 'min:6'],
            'roles' => ['nullable'],
            'role' => ['nullable'],
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended'])],
        ]);

        $defaultSys = System::where('is_active', true)->first();
        $systemId = $request->filled('system_id') ? (int) $request->system_id : ($defaultSys ? $defaultSys->id : null);
        $empCode = $request->filled('employee_code') ? $request->employee_code : ($request->filled('username') ? $request->username : null);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'employee_code' => $empCode,
            'system_id' => $systemId,
            'password' => $request->password,
            'status' => $request->status,
        ]);

        // Process roles
        $rolesToAssign = [];
        if ($request->has('roles') && is_array($request->roles)) {
            $rolesToAssign = $request->roles;
        } elseif ($request->filled('role')) {
            $rolesToAssign = [$request->role];
        }

        if (!empty($rolesToAssign)) {
            $user->syncRoles($rolesToAssign);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'user' => $user->load('roles'),
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User added successfully.');
    }

    public function edit(User $user): View
    {
        $roles = Role::all();
        $systems = System::where('is_active', true)->get();

        return view('admin.users.edit', compact('user', 'roles', 'systems'));
    }

    public function update(Request $request, User $user): JsonResponse|RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'username' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
            'employee_code' => ['nullable', 'string', 'max:50', Rule::unique('users', 'employee_code')->ignore($user->id)],
            'system_id' => ['nullable'],
            'roles' => ['nullable'],
            'role' => ['nullable'],
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended'])],
        ]);

        $defaultSys = System::where('is_active', true)->first();
        $systemId = $request->filled('system_id') ? (int) $request->system_id : ($user->system_id ?? ($defaultSys ? $defaultSys->id : null));
        $empCode = $request->filled('employee_code') ? $request->employee_code : ($request->filled('username') ? $request->username : $user->employee_code);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'employee_code' => $empCode,
            'system_id' => $systemId,
            'status' => $request->status,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'min:6'],
            ]);
            $user->update([
                'password' => $request->password,
            ]);
        }

        $rolesToAssign = [];
        if ($request->has('roles') && is_array($request->roles)) {
            $rolesToAssign = $request->roles;
        } elseif ($request->filled('role')) {
            $rolesToAssign = [$request->role];
        }

        if (!empty($rolesToAssign)) {
            $user->syncRoles($rolesToAssign);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'user' => $user->load('roles'),
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function toggleStatus(User $user): JsonResponse|RedirectResponse
    {
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => 'User status updated to ' . $newStatus,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User status updated successfully.');
    }

    public function destroy(User $user): JsonResponse|RedirectResponse
    {
        if (auth()->check() && auth()->id() === $user->id) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete currently active logged in user.'], 422);
            }
            return redirect()->route('admin.users.index')->with('error', 'Cannot delete currently active logged in user.');
        }

        $user->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function resetPassword(Request $request, User $user): JsonResponse|RedirectResponse
    {
        $newPass = $request->input('password', 'password');
        $user->update([
            'password' => Hash::make($newPass),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully for ' . $user->name,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Password reset successfully for ' . $user->name);
    }

    private function ensurePermissionsExist(array $permissions): array
    {
        $cleanedList = array_values(array_unique(array_filter(array_map(
            fn($p) => trim((string) $p),
            $permissions
        ))));

        if (!empty($cleanedList)) {
            $existing = Permission::whereIn('name', $cleanedList)->pluck('name')->toArray();
            $missing = array_diff($cleanedList, $existing);
            foreach ($missing as $permName) {
                Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
            }
        }

        return $cleanedList;
    }

    public function updateRolePermissions(Request $request, Role $role): JsonResponse|RedirectResponse
    {
        $permissions = $this->ensurePermissionsExist((array) $request->input('permissions', []));

        $role->syncPermissions($permissions);
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $role->load('permissions');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permissions successfully saved for role ' . $role->name,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ]);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role permissions saved successfully.');
    }

    public function updatePermissions(Request $request, User $user): JsonResponse|RedirectResponse
    {
        $permissions = $this->ensurePermissionsExist((array) $request->input('permissions', []));
        $roleName = $request->input('role');

        if ($roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->syncPermissions($permissions);
            }
            $user->syncRoles([$roleName]);
        } else {
            $user->syncPermissions($permissions);
        }
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permissions saved successfully for ' . $user->name,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Permissions saved successfully.');
    }

    public function rolesIndex(): View
    {
        $roles = Role::with('permissions')->get();
        $rolesCount = $roles->count();
        $permissions = Permission::all();
        $users = User::withoutGlobalScopes()->with(['roles', 'system'])->get();
        $totalUsers = User::withoutGlobalScopes()->count();
        $activeUsers = User::withoutGlobalScopes()->where('status', 'active')->count();

        $erpModules = ErpModule::with(['subModules' => function ($q) {
            $q->where('status', 'active')->orderBy('order', 'asc');
        }])->where('status', 'active')->orderBy('order', 'asc')->get();

        $roleDescriptions = [
            'Admin' => 'Handles day-to-day operations of the ERP with limited access to sensitive settings.',
            'Sales' => 'Manages sales, bookings, customers and related modules.',
            'Accountant' => 'Handles accounting, vouchers, ledger, bank and financial reports.',
            'Super Admin' => 'Full access to all modules except system-level user management.',
            'Main User' => 'Complete access to the entire ERP including system settings, user management and all modules.',
            'Owner' => 'Primary enterprise system owner with comprehensive organizational authority.',
        ];

        $roleThemes = [
            'Admin' => [
                'color' => 'blue',
                'icon' => 'user',
                'badge' => 'bg-blue-50 text-blue-700 border-blue-200',
                'btn' => 'border-blue-500 text-blue-600 hover:bg-blue-50',
                'icon_bg' => 'bg-blue-500',
                'border' => 'border-blue-200 hover:border-blue-400',
            ],
            'Sales' => [
                'color' => 'emerald',
                'icon' => 'trending-up',
                'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'btn' => 'border-emerald-500 text-emerald-600 hover:bg-emerald-50',
                'icon_bg' => 'bg-emerald-500',
                'border' => 'border-emerald-200 hover:border-emerald-400',
            ],
            'Accountant' => [
                'color' => 'amber',
                'icon' => 'calculator',
                'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
                'btn' => 'border-amber-500 text-amber-600 hover:bg-amber-50',
                'icon_bg' => 'bg-amber-500',
                'border' => 'border-amber-200 hover:border-amber-400',
            ],
            'Super Admin' => [
                'color' => 'purple',
                'icon' => 'shield-check',
                'badge' => 'bg-purple-50 text-purple-700 border-purple-200',
                'btn' => 'border-purple-500 text-purple-600 hover:bg-purple-50',
                'icon_bg' => 'bg-purple-600',
                'border' => 'border-purple-200 hover:border-purple-400',
            ],
            'Main User' => [
                'color' => 'teal',
                'icon' => 'crown',
                'badge' => 'bg-teal-50 text-teal-700 border-teal-200',
                'btn' => 'border-teal-500 text-teal-600 hover:bg-teal-50',
                'icon_bg' => 'bg-teal-600',
                'border' => 'border-teal-200 hover:border-teal-400',
            ],
            'Owner' => [
                'color' => 'gold',
                'icon' => 'key',
                'badge' => 'bg-[#a38c29]/10 text-[#a38c29] border-[#a38c29]/20',
                'btn' => 'border-[#a38c29] text-[#a38c29] hover:bg-[#a38c29]/10',
                'icon_bg' => 'bg-[#a38c29]',
                'border' => 'border-[#a38c29]/30 hover:border-[#a38c29]',
            ],
        ];

        // Desired display priority matching Image 2
        $priorityOrder = ['Admin' => 1, 'Sales' => 2, 'Accountant' => 3, 'Super Admin' => 4, 'Main User' => 5, 'Owner' => 6];

        $sortedRoles = $roles->sortBy(function ($r) use ($priorityOrder) {
            return $priorityOrder[$r->name] ?? 99;
        })->values();

        $roleCards = $sortedRoles->map(function ($r) use ($users, $roleDescriptions, $roleThemes) {
            $assignedUsers = $users->filter(fn($u) => $u->roles->contains('name', $r->name));
            $theme = $roleThemes[$r->name] ?? [
                'color' => 'slate',
                'icon' => 'shield',
                'badge' => 'bg-slate-100 text-slate-700 border-slate-200',
                'btn' => 'border-slate-400 text-slate-600 hover:bg-slate-50',
                'icon_bg' => 'bg-slate-600',
                'border' => 'border-slate-200 hover:border-slate-400',
            ];

            return [
                'id' => $r->id,
                'name' => $r->name,
                'description' => $roleDescriptions[$r->name] ?? 'Custom configured enterprise role for specialized team workflows.',
                'theme' => $theme,
                'user_count' => $assignedUsers->count(),
                'permissions' => $r->permissions->pluck('name')->toArray(),
                'is_system' => in_array($r->name, ['Owner', 'Main User', 'Super Admin', 'Admin', 'Sales', 'Accountant', 'Site']),
                'users' => $assignedUsers->map(fn($u) => [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'status' => $u->status ?? 'active',
                ])->values()->toArray(),
            ];
        });

        // Formatted module catalog for permission matrix
        $modulesCatalog = $erpModules->map(function ($m) {
            return [
                'id' => $m->id,
                'name' => $m->name,
                'code' => $m->code,
                'section' => $m->section,
                'icon' => $m->icon ?? 'folder',
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
                        'name' => $sm->name,
                        'code' => $sm->code,
                        'route_name' => $sm->route_name ?? '',
                        'actions' => $cleanActions,
                        'description' => $sm->description ?? '',
                    ];
                })->values()->toArray(),
            ];
        })->values()->toArray();

        return view('admin.roles.index', [
            'roles' => $sortedRoles,
            'rolesCount' => $rolesCount,
            'roleCards' => $roleCards,
            'permissions' => $permissions,
            'erpModules' => $erpModules,
            'modulesCatalog' => $modulesCatalog,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
        ]);
    }

    public function storeRole(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
        ]);

        $role = Role::create([
            'name' => trim($validated['name']),
            'guard_name' => 'web',
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Role created successfully: ' . $role->name,
                'role' => $role,
            ]);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function destroyRole(Request $request, Role $role): JsonResponse|RedirectResponse
    {
        $protectedRoles = ['Owner', 'Main User', 'Super Admin', 'Admin', 'Sales', 'Accountant'];
        if (in_array($role->name, $protectedRoles)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => "Cannot delete core system role '{$role->name}'."], 422);
            }
            return redirect()->route('admin.roles.index')->with('error', "Cannot delete core system role '{$role->name}'.");
        }

        $userCount = User::withoutGlobalScopes()->whereHas('roles', fn($q) => $q->where('id', $role->id))->count();
        if ($userCount > 0) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => "Cannot delete role '{$role->name}' because {$userCount} user(s) are currently assigned to it."], 422);
            }
            return redirect()->route('admin.roles.index')->with('error', "Cannot delete role because users are currently assigned to it.");
        }

        $role->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => "Role '{$role->name}' deleted successfully."]);
        }

        return redirect()->route('admin.roles.index')->with('success', "Role deleted successfully.");
    }
}
