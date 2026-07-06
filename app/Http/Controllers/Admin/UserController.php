<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\System;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        // SystemScope global scope automatically filters queries to the user's system 
        // unless they are an Owner.
        $users = User::with('roles', 'system')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::all();
        $systems = System::where('is_active', true)->get();

        return view('admin.users.create', compact('roles', 'systems'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'employee_code' => ['nullable', 'string', 'max:50', 'unique:users'],
            'system_id' => ['required', 'exists:systems,id'],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended'])],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'employee_code' => $request->employee_code,
            'system_id' => $request->system_id,
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')
            ->with('status', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        // Check access authorization
        if (auth()->user()->system_id !== null && !auth()->user()->hasMultiSystemAccess() && auth()->user()->system_id !== $user->system_id) {
            abort(403, 'Unauthorized access to user from another system.');
        }

        $roles = Role::all();
        $systems = System::where('is_active', true)->get();

        return view('admin.users.edit', compact('user', 'roles', 'systems'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if (auth()->user()->system_id !== null && !auth()->user()->hasMultiSystemAccess() && auth()->user()->system_id !== $user->system_id) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'employee_code' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'system_id' => ['required', 'exists:systems,id'],
            'role' => ['required', 'exists:roles,name'],
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended'])],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'employee_code' => $request->employee_code,
            'system_id' => $request->system_id,
            'status' => $request->status,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => Rules\Password::defaults(),
            ]);
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')
            ->with('status', 'User updated successfully.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        if (auth()->user()->system_id !== null && !auth()->user()->hasMultiSystemAccess() && auth()->user()->system_id !== $user->system_id) {
            abort(403);
        }

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        return redirect()->route('admin.users.index')
            ->with('status', 'User status updated successfully.');
    }
}
