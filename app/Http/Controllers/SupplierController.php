<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Payee;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $suppliers = Payee::where('system_id', $systemId)
            ->where('type', 'Supplier')
            ->orderBy('name')
            ->get();

        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $request->validate([
            'name' => 'required|string|max:191|unique:payees,name,NULL,id,system_id,' . $systemId . ',type,Supplier',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:191',
            'gstin' => 'nullable|string|max:100',
            'pan' => 'nullable|string|max:100',
            'address' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $systemId) {
            // Find next counter for unique account code
            $lastPayee = Payee::orderBy('id', 'desc')->first();
            $nextId = $lastPayee ? ($lastPayee->id + 1) : 1;
            $accountCode = 'SUP-ACC-' . str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);

            // Create liability account for the supplier (Accounts Payable)
            $account = Account::create([
                'system_id' => $systemId,
                'code' => $accountCode,
                'name' => $request->name . ' (Payable)',
                'type' => 'Liability',
                'is_active' => true,
            ]);

            // Create payee entry
            Payee::create([
                'system_id' => $systemId,
                'type' => 'Supplier',
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'gstin' => $request->gstin,
                'pan' => $request->pan,
                'address' => $request->address,
                'linked_account_id' => $account->id,
            ]);
        });

        return redirect()->route('suppliers.index')->with('status', 'Supplier registered successfully and ledger account created.');
    }

    public function update(Request $request, int $id)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $payee = Payee::where('system_id', $systemId)->where('type', 'Supplier')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:191|unique:payees,name,' . $payee->id . ',id,system_id,' . $systemId . ',type,Supplier',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:191',
            'gstin' => 'nullable|string|max:100',
            'pan' => 'nullable|string|max:100',
            'address' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $payee) {
            $payee->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'gstin' => $request->gstin,
                'pan' => $request->pan,
                'address' => $request->address,
            ]);

            // Update linked account name
            $account = Account::find($payee->linked_account_id);
            if ($account) {
                $account->update(['name' => $request->name . ' (Payable)']);
            }
        });

        return redirect()->route('suppliers.index')->with('status', 'Supplier details updated successfully.');
    }

    public function destroy(int $id)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $payee = Payee::where('system_id', $systemId)->where('type', 'Supplier')->findOrFail($id);

        DB::transaction(function () use ($payee) {
            // Delete linked liability account if no transactions exist
            $account = Account::find($payee->linked_account_id);
            if ($account) {
                // Check if has ledger entries
                $hasEntries = DB::table('ledger_entries')->where('account_id', $account->id)->exists();
                if (!$hasEntries) {
                    $account->delete();
                }
            }
            $payee->delete();
        });

        return redirect()->route('suppliers.index')->with('status', 'Supplier removed successfully.');
    }
}
