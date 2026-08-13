<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ChartOfAccountController extends Controller
{
    public function index(Request $request): View
    {
        $query = ChartOfAccount::orderBy('account_code');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('account_code', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('account_type')) {
            $query->where('account_type', $request->input('account_type'));
        }

        $accounts = $query->paginate(20)->withQueryString();

        $all = ChartOfAccount::all();
        $totalAccounts   = $all->count();
        $assetCount      = $all->where('account_type', 'ASSET')->count();
        $liabilityCount  = $all->where('account_type', 'LIABILITY')->count();
        $revenueCount    = $all->where('account_type', 'REVENUE')->count();
        $expenseCount    = $all->where('account_type', 'EXPENSE')->count();

        return view('chart-of-accounts.index', compact(
            'accounts',
            'totalAccounts',
            'assetCount',
            'liabilityCount',
            'revenueCount',
            'expenseCount'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'account_code' => 'required|string|max:20|unique:chart_of_accounts,account_code',
            'account_name' => 'required|string|max:100',
            'account_type' => 'required|in:ASSET,LIABILITY,REVENUE,EXPENSE',
            'is_active'    => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        ChartOfAccount::create($validated);

        return redirect()->route('chart-of-accounts.index')
            ->with('success', 'Chart of Account created successfully.');
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount): RedirectResponse
    {
        $validated = $request->validate([
            'account_code' => 'required|string|max:20|unique:chart_of_accounts,account_code,' . $chartOfAccount->id,
            'account_name' => 'required|string|max:100',
            'account_type' => 'required|in:ASSET,LIABILITY,REVENUE,EXPENSE',
            'is_active'    => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : false;

        $chartOfAccount->update($validated);

        return redirect()->route('chart-of-accounts.index')
            ->with('success', 'Chart of Account updated successfully.');
    }

    public function destroy(ChartOfAccount $chartOfAccount): RedirectResponse
    {
        $name = $chartOfAccount->account_name;
        $chartOfAccount->delete();

        return redirect()->route('chart-of-accounts.index')
            ->with('success', "Chart of Account '{$name}' deleted successfully.");
    }

    public function toggleStatus(ChartOfAccount $chartOfAccount): RedirectResponse
    {
        $chartOfAccount->update(['is_active' => !$chartOfAccount->is_active]);

        $statusStr = $chartOfAccount->is_active ? 'activated' : 'deactivated';
        return redirect()->route('chart-of-accounts.index')
            ->with('success', "Account '{$chartOfAccount->account_name}' {$statusStr}.");
    }
}
