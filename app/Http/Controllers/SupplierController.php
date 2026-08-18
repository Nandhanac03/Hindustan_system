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
            ->whereIn('type', ['Contractor', 'Supplier'])
            ->with('linkedAccount')
            ->orderBy('name')
            ->get();

        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $request->validate([
            'name' => 'required|string|max:191|unique:payees,name,NULL,id,system_id,' . $systemId,
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:191',
            'gstin' => 'nullable|string|size:15|alpha_num',
            'pan' => 'nullable|string|max:100',
            'address' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $systemId) {
            $baseCode = 'SUP-ACC-';
            $existingCodes = Account::where('system_id', $systemId)
                ->where('code', 'like', $baseCode . '%')
                ->pluck('code');

            $maxId = 0;
            foreach ($existingCodes as $code) {
                $idPart = (int) str_replace($baseCode, '', $code);
                if ($idPart > $maxId) {
                    $maxId = $idPart;
                }
            }
            $nextId = $maxId + 1;
            $accountCode = $baseCode . str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);

            // Create liability account for the contractor (Accounts Payable)
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
                'type' => 'Contractor',
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'gstin' => $request->gstin,
                'pan' => $request->pan,
                'address' => $request->address,
                'linked_account_id' => $account->id,
            ]);
        });

        return redirect()->route('contractors.index')->with('status', '✅ Contractor registered successfully and ledger account created.');
    }

    public function update(Request $request, int $id)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $payee = Payee::where('system_id', $systemId)
            ->whereIn('type', ['Contractor', 'Supplier'])
            ->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:191|unique:payees,name,' . $payee->id . ',id,system_id,' . $systemId,
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:191',
            'gstin' => 'nullable|string|size:15|alpha_num',
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

        return redirect()->route('contractors.index')->with('status', '✅ Contractor details updated successfully.');
    }

    public function destroy(int $id)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $payee = Payee::where('system_id', $systemId)
            ->whereIn('type', ['Contractor', 'Supplier'])
            ->findOrFail($id);

        DB::transaction(function () use ($payee) {
            // Delete linked liability account if no transactions exist
            $account = Account::find($payee->linked_account_id);
            if ($account) {
                $hasEntries = DB::table('ledger_entries')->where('account_id', $account->id)->exists();
                if (!$hasEntries) {
                    $account->delete();
                }
            }
            $payee->delete();
        });

        return redirect()->route('contractors.index')->with('status', '✅ Contractor removed successfully.');
    }
}
