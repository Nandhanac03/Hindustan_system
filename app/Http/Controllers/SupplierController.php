<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Payee;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\RaBill;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $query = Payee::where('system_id', $systemId)
            ->whereIn('type', ['Contractor', 'Supplier'])
            ->with('linkedAccount')
            ->withCount('raBills')
            ->withSum('raBills as total_billed', 'net_approved_amount');

        if ($request->filled('search_code')) {
            $code = trim((string)$request->search_code);
            $query->whereHas('linkedAccount', function ($aq) use ($code) {
                $aq->where('code', 'like', "%{$code}%");
            });
        }

        if ($request->filled('search_name')) {
            $name = trim((string)$request->search_name);
            $query->where('name', 'like', "%{$name}%");
        }

        if ($request->filled('search')) {
            $s = trim((string)$request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('gstin', 'like', "%{$s}%")
                    ->orWhereHas('linkedAccount', function ($aq) use ($s) {
                        $aq->where('code', 'like', "%{$s}%");
                    });
            });
        }

        if ($request->filled('gst_status')) {
            if ($request->gst_status === 'with_gst') {
                $query->whereNotNull('gstin')->where('gstin', '!=', '');
            } elseif ($request->gst_status === 'without_gst') {
                $query->where(function ($q) {
                    $q->whereNull('gstin')->orWhere('gstin', '');
                });
            }
        }

        if ($request->sort_by === 'name_desc') {
            $query->orderByDesc('name');
        } elseif ($request->sort_by === 'newest') {
            $query->latest();
        } else {
            $query->orderBy('name');
        }

        $suppliers = $query->get();

        // Ensure any RA bills matched by contractor name or ID are linked and reflected accurately
        foreach ($suppliers as $sup) {
            if (!$sup->ra_bills_count || $sup->ra_bills_count == 0) {
                $matchedBills = RaBill::where('system_id', $systemId)
                    ->where(function($q) use ($sup) {
                        $q->where('contractor_id', $sup->id)
                          ->orWhere('contractor_name', $sup->name);
                    })->get();
                
                if ($matchedBills->isNotEmpty()) {
                    RaBill::where('system_id', $systemId)
                        ->where('contractor_name', $sup->name)
                        ->where(function($q) {
                            $q->whereNull('contractor_id')->orWhere('contractor_id', 0);
                        })
                        ->update(['contractor_id' => $sup->id]);

                    $sup->ra_bills_count = $matchedBills->count();
                    $sup->total_billed = $matchedBills->sum('net_approved_amount');
                }
            }
        }

        $totalContractors = Payee::where('system_id', $systemId)->whereIn('type', ['Contractor', 'Supplier'])->count();
        $gstinCount = Payee::where('system_id', $systemId)->whereIn('type', ['Contractor', 'Supplier'])->whereNotNull('gstin')->where('gstin', '!=', '')->count();
        $totalBillsAmount = RaBill::where('system_id', $systemId)->sum('net_approved_amount');
        $activeWithBills = RaBill::where('system_id', $systemId)->distinct('contractor_id')->count('contractor_id');

        return view('suppliers.index', compact('suppliers', 'totalContractors', 'gstinCount', 'totalBillsAmount', 'activeWithBills'));
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
