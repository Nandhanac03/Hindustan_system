<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CompanyBankAccount;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyBankAccountController extends Controller
{
    public function index(): View
    {
        $accounts = CompanyBankAccount::orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();
        
        $totalAccounts  = $accounts->count();
        $activeAccounts = $accounts->where('status', 'active')->count();
        $totalBalance   = (float) $accounts->sum('current_balance');
        $totalOpeningBalance = (float) $accounts->sum('opening_balance');
        $defaultAccount = $accounts->firstWhere('is_default', true);

        return view('company-bank-accounts.index', compact(
            'accounts',
            'totalAccounts',
            'activeAccounts',
            'totalBalance',
            'totalOpeningBalance',
            'defaultAccount'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bank_name'       => 'required|string|max:255',
            'account_name'    => 'nullable|string|max:255',
            'account_number'  => 'nullable|string|max:100',
            'account_type'    => 'required|in:Current,Savings,Overdraft,Escrow,CC',
            'ifsc_code'       => 'required|string|max:50',
            'branch_name'     => 'nullable|string|max:255',
            'swift_code'      => 'nullable|string|max:50',
            'micr_code'       => 'nullable|string|max:50',
            'opening_balance' => 'nullable|numeric|min:0',
            'upi_id'          => 'nullable|string|max:255',
            'status'          => 'required|in:active,inactive',
            'is_default'      => 'nullable|boolean',
        ]);

        $validated['opening_balance'] = isset($validated['opening_balance']) ? (float)$validated['opening_balance'] : 0.00;
        $validated['current_balance'] = $validated['opening_balance'];
        $isDefault = $request->boolean('is_default');

        if (CompanyBankAccount::count() === 0) {
            $isDefault = true;
        }

        if ($isDefault) {
            CompanyBankAccount::where('is_default', true)->update(['is_default' => false]);
        }

        $validated['is_default'] = $isDefault;

        CompanyBankAccount::create($validated);

        return redirect()->route('company-bank-accounts.index')
            ->with('success', 'Company Bank Account added successfully.');
    }

    public function update(Request $request, CompanyBankAccount $companyBankAccount): RedirectResponse
    {
        $validated = $request->validate([
            'bank_name'       => 'required|string|max:255',
            'account_name'    => 'nullable|string|max:255',
            'account_number'  => 'nullable|string|max:100',
            'account_type'    => 'required|in:Current,Savings,Overdraft,Escrow,CC',
            'ifsc_code'       => 'required|string|max:50',
            'branch_name'     => 'nullable|string|max:255',
            'swift_code'      => 'nullable|string|max:50',
            'micr_code'       => 'nullable|string|max:50',
            'opening_balance' => 'nullable|numeric|min:0',
            'current_balance' => 'nullable|numeric',
            'upi_id'          => 'nullable|string|max:255',
            'status'          => 'required|in:active,inactive',
            'is_default'      => 'nullable|boolean',
        ]);

        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            CompanyBankAccount::where('id', '!=', $companyBankAccount->id)->where('is_default', true)->update(['is_default' => false]);
        }

        $validated['is_default'] = $isDefault;

        $companyBankAccount->update($validated);

        return redirect()->route('company-bank-accounts.index')
            ->with('success', 'Company Bank Account updated successfully.');
    }

    public function destroy(CompanyBankAccount $companyBankAccount): RedirectResponse
    {
        $accountName = $companyBankAccount->bank_name;
        $wasDefault = $companyBankAccount->is_default;

        $companyBankAccount->delete();

        if ($wasDefault) {
            $nextAccount = CompanyBankAccount::where('status', 'active')->first();
            if ($nextAccount) {
                $nextAccount->update(['is_default' => true]);
            }
        }

        return redirect()->route('company-bank-accounts.index')
            ->with('success', 'Company Bank Account "' . $accountName . '" deleted successfully.');
    }
}
