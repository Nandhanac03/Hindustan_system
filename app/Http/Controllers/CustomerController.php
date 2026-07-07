<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Floor;
use App\Models\Project;
use App\Models\Customer;
use App\Models\System;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\UnitRateLog;
use App\Models\UnitStatusLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
class CustomerController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        // SystemScope auto-scopes by logged-in user unless Owner
       $query = Customer::query();
 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }
 
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }
 
        $customers = $query->orderBy('name')->get();
    
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['customers' => $customers]);
        }
 
        return view('customers.index');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:191'],
            'email'           => ['required', 'email', 'max:191', Rule::unique('customers', 'email')],
            'phone'           => ['nullable', 'string', 'max:20'],
            'address'         => ['nullable', 'string'],
            // 'id_proof_type'   => ['nullable', 'string', 'max:50'],
            // 'id_proof_number' => ['nullable', 'string', 'max:50'],
            // 'system'          => ['required', Rule::in(['india', 'uae'])],
            // 'is_active'       => ['nullable', 'boolean'],
        ]);
    
        // Generate simple 2-letter avatar initials from the name, matching existing seeded data style
        // $nameParts = explode(' ', trim($validated['name']));
        // $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
    
        $customer = Customer::create([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'phone'           => $validated['phone'] ?? null,
            'address'         => $validated['address'] ?? null,
            // 'id_proof_type'   => $validated['id_proof_type'] ?? null,
            // 'id_proof_number' => $validated['id_proof_number'] ?? null,
            // 'system'          => $validated['system'],
            'is_active'       =>  1,
            // 'avatar_url'      => $initials,
        ]);
    
        return response()->json(['customer' => $customer], 201);
    }
    public function edit(Request $request, Customer $customer)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'customer' => $customer,
            ]);
        }
 
        return view('customers.edit', compact('customer'));
    }
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => [
                'nullable', 'email', 'max:255',
                Rule::unique('customers', 'email')->ignore($customer->id),
            ],
            'phone'     => ['nullable', 'string', 'max:30'],
            'address'   => ['nullable', 'string', 'max:1000'],
            'is_active' => ['required', 'boolean'],
        ]);
 
        $customer->update($validated);
 
        return response()->json([
            'message'  => 'Customer updated successfully.',
            'customer' => $customer,
        ]);
    }

    /**
     * Remove the specified customer.
    */
    public function destroy(Customer $customer)
    {
        $customer->delete();
 
        return response()->json([
            'message' => 'Customer deleted successfully.',
        ]);
    }

}
