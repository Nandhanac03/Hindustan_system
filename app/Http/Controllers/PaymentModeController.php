<?php

namespace App\Http\Controllers;

use App\Models\PaymentMode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentModeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id ?? 1;

        $query = PaymentMode::where('system_id', $systemId);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $paymentModes = $query->orderBy('id', 'asc')->get();

        $totalCount = PaymentMode::where('system_id', $systemId)->count();
        $activeCount = PaymentMode::where('system_id', $systemId)->where('status', 'active')->count();
        $inactiveCount = PaymentMode::where('system_id', $systemId)->where('status', 'inactive')->count();

        return view('payment-modes.index', compact('paymentModes', 'totalCount', 'activeCount', 'inactiveCount'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id ?? 1;

        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'requires_reference' => 'nullable|boolean',
            'requires_bank' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $code = $request->code ? Str::upper(Str::slug($request->code, '_')) : Str::upper(Str::slug($request->name, '_'));

        // Check code uniqueness within system
        $exists = PaymentMode::where('system_id', $systemId)->where('code', $code)->exists();
        if ($exists) {
            return back()->withInput()->withErrors(['code' => 'A payment mode with code "' . $code . '" already exists.']);
        }

        PaymentMode::create([
            'system_id' => $systemId,
            'name' => $request->name,
            'code' => $code,
            'description' => $request->description,
            'requires_reference' => $request->has('requires_reference') || $request->boolean('requires_reference'),
            'requires_bank' => $request->has('requires_bank') || $request->boolean('requires_bank'),
            'status' => $request->status,
        ]);

        return redirect()->route('payment-modes.index')->with('success', 'Payment Mode "' . $request->name . '" created successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $systemId = $user->system_id ?? 1;

        $paymentMode = PaymentMode::where('system_id', $systemId)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'requires_reference' => 'nullable|boolean',
            'requires_bank' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $code = $request->code ? Str::upper(Str::slug($request->code, '_')) : Str::upper(Str::slug($request->name, '_'));

        // Check code uniqueness excluding current record
        $exists = PaymentMode::where('system_id', $systemId)
            ->where('code', $code)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['code' => 'Another payment mode with code "' . $code . '" already exists.']);
        }

        $paymentMode->update([
            'name' => $request->name,
            'code' => $code,
            'description' => $request->description,
            'requires_reference' => $request->has('requires_reference') || $request->boolean('requires_reference'),
            'requires_bank' => $request->has('requires_bank') || $request->boolean('requires_bank'),
            'status' => $request->status,
        ]);

        return redirect()->route('payment-modes.index')->with('success', 'Payment Mode "' . $paymentMode->name . '" updated successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $systemId = $user->system_id ?? 1;

        $paymentMode = PaymentMode::where('system_id', $systemId)->findOrFail($id);
        $name = $paymentMode->name;

        $paymentMode->delete();

        return redirect()->route('payment-modes.index')->with('success', 'Payment Mode "' . $name . '" deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $user = Auth::user();
        $systemId = $user->system_id ?? 1;

        $paymentMode = PaymentMode::where('system_id', $systemId)->findOrFail($id);
        $newStatus = $paymentMode->status === 'active' ? 'inactive' : 'active';
        $paymentMode->update(['status' => $newStatus]);

        return redirect()->route('payment-modes.index')->with('success', 'Payment Mode "' . $paymentMode->name . '" status changed to ' . ucfirst($newStatus) . '.');
    }
}
