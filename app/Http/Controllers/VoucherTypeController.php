<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\VoucherType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VoucherTypeController extends Controller
{
    public function index(Request $request): View
    {
        $allVouchers = VoucherType::orderBy('id')->get();
        $totalCount  = $allVouchers->count();
        $activeCount = $allVouchers->where('is_active', true)->count();
        $inactiveCount = $allVouchers->where('is_active', false)->count();

        return view('voucher-types.index', compact(
            'allVouchers',
            'totalCount',
            'activeCount',
            'inactiveCount'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:50|unique:voucher_types,code',
            'name'        => 'required|string|max:100',
            'prefix'      => 'required|string|max:10',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['prefix'] = strtoupper(trim($validated['prefix']));
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        VoucherType::create($validated);

        return redirect()->route('voucher-types.index')
            ->with('success', 'Voucher Type created successfully.');
    }

    public function update(Request $request, VoucherType $voucherType): RedirectResponse
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:50|unique:voucher_types,code,' . $voucherType->id,
            'name'        => 'required|string|max:100',
            'prefix'      => 'required|string|max:10',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['prefix'] = strtoupper(trim($validated['prefix']));
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : false;

        $voucherType->update($validated);

        return redirect()->route('voucher-types.index')
            ->with('success', 'Voucher Type updated successfully.');
    }

    public function destroy(VoucherType $voucherType): RedirectResponse
    {
        $name = $voucherType->name;
        $voucherType->delete();

        return redirect()->route('voucher-types.index')
            ->with('success', "Voucher Type '{$name}' deleted successfully.");
    }

    public function toggleStatus(VoucherType $voucherType): RedirectResponse
    {
        $voucherType->update(['is_active' => !$voucherType->is_active]);

        $statusStr = $voucherType->is_active ? 'activated' : 'deactivated';
        return redirect()->route('voucher-types.index')
            ->with('success', "Voucher Type '{$voucherType->name}' {$statusStr}.");
    }
}
