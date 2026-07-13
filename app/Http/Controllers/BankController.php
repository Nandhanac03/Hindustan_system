<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BankController extends Controller
{
    public function index(): View
    {
        $banks = Bank::orderBy('bank_name')->get();
        return view('bank.index', compact('banks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:50',
            'status'    => 'required|in:active,inactive',
        ]);

        Bank::create($validated);

        return redirect()->route('bank.index')
            ->with('success', 'Bank account added successfully.');
    }

    public function update(Request $request, Bank $bank): RedirectResponse
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:50',
            'status'    => 'required|in:active,inactive',
        ]);

        $bank->update($validated);

        return redirect()->route('bank.index')
            ->with('success', 'Bank account updated successfully.');
    }

    public function destroy(Bank $bank): RedirectResponse
    {
        $bankName = $bank->bank_name;
        $bank->delete();

        return redirect()->route('bank.index')
            ->with('success', 'Bank account "' . $bankName . '" deleted successfully.');
    }
}
