<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Account;
use App\Models\SiteExpense;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    /**
     * Display listing of all vendors with search and KPI metrics
     */
    public function index(Request $request): View
    {
        $systemId = Auth::user()->system_id ?? 1;

        $query = Vendor::where('system_id', $systemId)
            ->with(['linkedAccount'])
            ->withCount('siteExpenses')
            ->withSum('siteExpenses as total_billed', 'net_amount');

        if ($request->filled('search')) {
            $s = trim((string)$request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('vendor_code', 'like', "%{$s}%")
                    ->orWhere('contact_person', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%")
                    ->orWhere('gstin', 'like', "%{$s}%")
                    ->orWhere('pan', 'like', "%{$s}%");
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

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->sort_by === 'name_desc') {
            $query->orderByDesc('name');
        } elseif ($request->sort_by === 'newest') {
            $query->latest();
        } elseif ($request->sort_by === 'code') {
            $query->orderBy('vendor_code');
        } else {
            $query->orderBy('name');
        }

        $vendors = $query->get();

        // Compute KPI Summary Metrics
        $totalVendors = Vendor::where('system_id', $systemId)->count();
        $activeVendors = Vendor::where('system_id', $systemId)->where('is_active', true)->count();
        $gstinCount = Vendor::where('system_id', $systemId)->whereNotNull('gstin')->where('gstin', '!=', '')->count();
        $totalBilledAmount = SiteExpense::where('system_id', $systemId)->whereNotNull('vendor_id')->sum('net_amount');

        return view('vendors.index', compact(
            'vendors',
            'totalVendors',
            'activeVendors',
            'gstinCount',
            'totalBilledAmount'
        ));
    }

    /**
     * Store a newly created Vendor in master
     */
    public function store(Request $request): RedirectResponse
    {
        $systemId = Auth::user()->system_id ?? 1;

        $request->validate([
            'name'           => 'required|string|max:191|unique:vendors,name,NULL,id,system_id,' . $systemId,
            'contact_person' => 'nullable|string|max:191',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:191',
            'gstin'          => 'nullable|string|size:15|alpha_num',
            'pan'            => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'bank_name'      => 'nullable|string|max:150',
            'account_number' => 'nullable|string|max:50',
            'ifsc_code'      => 'nullable|string|max:20',
            'branch'         => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($request, $systemId) {
            // Generate sequential Vendor Code (e.g. VND-0001)
            $lastVendor = Vendor::where('system_id', $systemId)
                ->where('vendor_code', 'like', 'VND-%')
                ->orderByDesc('id')
                ->first();

            $nextNum = 1;
            if ($lastVendor && preg_match('/VND-(\d+)/', $lastVendor->vendor_code, $matches)) {
                $nextNum = (int)$matches[1] + 1;
            }
            $vendorCode = 'VND-' . str_pad((string)$nextNum, 4, '0', STR_PAD_LEFT);

            // Generate linked liability account in Chart of Accounts (e.g. VND-ACC-0001)
            $baseAccCode = 'VND-ACC-';
            $existingAccCodes = Account::where('system_id', $systemId)
                ->where('code', 'like', $baseAccCode . '%')
                ->pluck('code');

            $maxId = 0;
            foreach ($existingAccCodes as $code) {
                $idPart = (int) str_replace($baseAccCode, '', $code);
                if ($idPart > $maxId) {
                    $maxId = $idPart;
                }
            }
            $accountCode = $baseAccCode . str_pad((string)($maxId + 1), 4, '0', STR_PAD_LEFT);

            $linkedAccount = Account::create([
                'system_id' => $systemId,
                'code'      => $accountCode,
                'name'      => $request->name . ' (Payable)',
                'type'      => 'Liability',
                'is_active' => true,
            ]);

            Vendor::create([
                'system_id'         => $systemId,
                'vendor_code'       => $vendorCode,
                'name'              => $request->name,
                'contact_person'    => $request->contact_person,
                'phone'             => $request->phone,
                'email'             => $request->email,
                'gstin'             => $request->gstin ? strtoupper($request->gstin) : null,
                'pan'               => $request->pan ? strtoupper($request->pan) : null,
                'address'           => $request->address,
                'bank_name'         => $request->bank_name,
                'account_number'    => $request->account_number,
                'ifsc_code'         => $request->ifsc_code ? strtoupper($request->ifsc_code) : null,
                'branch'            => $request->branch,
                'linked_account_id' => $linkedAccount->id,
                'is_active'         => true,
            ]);
        });

        return redirect()->route('vendors.index')->with('success', '✅ Vendor registered successfully in master and ledger account generated.');
    }

    /**
     * Update an existing Vendor
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $systemId = Auth::user()->system_id ?? 1;

        $vendor = Vendor::where('system_id', $systemId)->findOrFail($id);

        $request->validate([
            'name'           => 'required|string|max:191|unique:vendors,name,' . $vendor->id . ',id,system_id,' . $systemId,
            'contact_person' => 'nullable|string|max:191',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:191',
            'gstin'          => 'nullable|string|size:15|alpha_num',
            'pan'            => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'bank_name'      => 'nullable|string|max:150',
            'account_number' => 'nullable|string|max:50',
            'ifsc_code'      => 'nullable|string|max:20',
            'branch'         => 'nullable|string|max:100',
            'is_active'      => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $vendor) {
            $vendor->update([
                'name'           => $request->name,
                'contact_person' => $request->contact_person,
                'phone'          => $request->phone,
                'email'          => $request->email,
                'gstin'          => $request->gstin ? strtoupper($request->gstin) : null,
                'pan'            => $request->pan ? strtoupper($request->pan) : null,
                'address'        => $request->address,
                'bank_name'      => $request->bank_name,
                'account_number' => $request->account_number,
                'ifsc_code'      => $request->ifsc_code ? strtoupper($request->ifsc_code) : null,
                'branch'         => $request->branch,
                'is_active'      => $request->has('is_active') ? (bool)$request->is_active : $vendor->is_active,
            ]);

            if ($vendor->linked_account_id) {
                $acc = Account::find($vendor->linked_account_id);
                if ($acc) {
                    $acc->update(['name' => $request->name . ' (Payable)']);
                }
            }
        });

        return redirect()->route('vendors.index')->with('success', '✅ Vendor details updated successfully.');
    }

    /**
     * Remove or deactivate a vendor
     */
    public function destroy(int $id): RedirectResponse
    {
        $systemId = Auth::user()->system_id ?? 1;

        $vendor = Vendor::where('system_id', $systemId)->findOrFail($id);

        $expensesCount = SiteExpense::where('vendor_id', $vendor->id)->count();
        if ($expensesCount > 0) {
            // If expenses are attached, toggle active status instead of hard delete
            $vendor->update(['is_active' => false]);
            return redirect()->route('vendors.index')->with('success', "ℹ️ Vendor has {$expensesCount} linked site expense(s). Vendor status set to Inactive to protect financial audit integrity.");
        }

        DB::transaction(function () use ($vendor) {
            if ($vendor->linked_account_id) {
                $acc = Account::find($vendor->linked_account_id);
                if ($acc) {
                    $hasEntries = DB::table('ledger_entries')->where('account_id', $acc->id)->exists();
                    if (!$hasEntries) {
                        $acc->delete();
                    }
                }
            }
            $vendor->delete();
        });

        return redirect()->route('vendors.index')->with('success', '✅ Vendor removed successfully.');
    }
}
