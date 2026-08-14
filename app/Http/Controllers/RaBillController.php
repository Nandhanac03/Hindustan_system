<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CompanyBankAccount;
use App\Models\Payee;
use App\Models\Project;
use App\Models\RaBill;
use App\Models\RaBillPayment;
use App\Models\Unit;
use App\Services\ChequeRealizationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

use App\Models\Engineer;

class RaBillController extends Controller
{
    /**
     * Display the Contractor RA Progress Bills Directory (Excel-matched layout).
     */
    public function index(): View
    {
        $systemId = Auth::user()->system_id ?? 1;

        $raBills = RaBill::with(['contractor', 'project', 'unit', 'payments.companyBankAccount', 'payments.voucher'])
            ->where('system_id', $systemId)
            ->orderBy('id', 'desc')
            ->get();

        $contractors = Payee::where('system_id', $systemId)
            ->where('type', 'Contractor')
            ->orderBy('name')
            ->get(['id', 'name', 'type']);

        if ($contractors->isEmpty()) {
            $contractors = Payee::where('system_id', $systemId)
                ->whereIn('type', ['Contractor', 'Supplier'])
                ->orderBy('name')
                ->get(['id', 'name', 'type']);
        }

        $projects = Project::where('system_id', $systemId)
            ->orderBy('name')
            ->get(['id', 'name']);

        $units = Unit::orderBy('door_no')->get(['id', 'door_no', 'project_id']);

        $engineers = Engineer::where('is_active', true)->orderBy('name')->get();
        if ($engineers->isEmpty()) {
            Engineer::firstOrCreate(['engineer_code' => 'ENG-001'], ['name' => 'Rahul Accountant', 'designation' => 'Site Engineer', 'is_active' => true]);
            Engineer::firstOrCreate(['engineer_code' => 'ENG-002'], ['name' => 'Anand Kumar', 'designation' => 'Senior Project Engineer', 'is_active' => true]);
            Engineer::firstOrCreate(['engineer_code' => 'ENG-003'], ['name' => 'Suresh Nair', 'designation' => 'Civil Engineer', 'is_active' => true]);
            $engineers = Engineer::where('is_active', true)->orderBy('name')->get();
        }

        $companyBankAccounts = CompanyBankAccount::where('status', 'active')
            ->orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        // Calculate KPI summaries
        $totalGross = $raBills->sum('gross_amount');
        $totalCorrections = $raBills->sum('correction_amount');
        $totalNetApproved = $raBills->sum('net_approved_amount');
        $totalPaid = $raBills->sum('paid_amount');
        $totalBalance = $raBills->sum('balance_amount');

        return view('expenses.ra-bills.index', compact(
            'raBills',
            'contractors',
            'projects',
            'units',
            'engineers',
            'companyBankAccounts',
            'totalGross',
            'totalCorrections',
            'totalNetApproved',
            'totalPaid',
            'totalBalance'
        ));
    }

    /**
     * Step 2.1 / 3.1: Log new Contractor Inward RA Bill.
     */
    public function store(Request $request): RedirectResponse
    {
        $systemId = Auth::user()->system_id ?? 1;

        $validated = $request->validate([
            'ra_bill_number'    => ['required', 'string', 'unique:ra_bills,ra_bill_number'],
            'contractor_id'     => ['required', 'exists:payees,id'],
            'contractor_name'   => ['nullable', 'string', 'max:255'],
            'project_id'        => ['required', 'exists:projects,id'],
            'unit_id'           => ['required', 'exists:units,id'],
            'submit_date'       => ['required', 'date'],
            'gross_amount'      => ['required', 'numeric', 'min:0.01'],
            'verified_date'     => ['nullable', 'date'],
            'engineer_name'     => ['nullable', 'string', 'max:255'],
            'correction_amount' => ['nullable', 'numeric', 'min:0'],
            'due_date'          => ['nullable', 'date'],
            'remarks'           => ['nullable', 'string', 'max:500'],
        ], [
            'ra_bill_number.required' => 'The RA Bill Number field is required.',
            'contractor_id.required'  => 'The Contractor field is required.',
            'project_id.required'     => 'The Site Project field is required.',
            'unit_id.required'        => 'The Unit field is required.',
            'submit_date.required'    => 'The Submit Date field is required.',
            'gross_amount.required'   => 'The Gross Amount field is required.',
        ]);

        $gross = (float) $validated['gross_amount'];
        $correction = (float) ($validated['correction_amount'] ?? 0.00);
        $netApproved = max(0.00, $gross - $correction);

        $contractorName = $validated['contractor_name'] ?? null;
        if (!empty($validated['contractor_id']) && empty($contractorName)) {
            $contractorName = Payee::find($validated['contractor_id'])?->name;
        }

        $unitName = null;
        if (!empty($validated['unit_id'])) {
            $unitName = Unit::find($validated['unit_id'])?->door_no;
        }

        RaBill::create([
            'system_id'           => $systemId,
            'ra_bill_number'      => $validated['ra_bill_number'],
            'contractor_id'       => $validated['contractor_id'] ?? null,
            'contractor_name'     => $contractorName,
            'project_id'          => $validated['project_id'] ?? null,
            'unit_id'             => $validated['unit_id'] ?? null,
            'unit_name'           => $unitName,
            'submit_date'         => $validated['submit_date'],
            'gross_amount'        => $gross,
            'verified_date'       => $validated['verified_date'] ?? null,
            'engineer_name'       => $validated['engineer_name'] ?? null,
            'correction_amount'   => $correction,
            'net_approved_amount' => $netApproved,
            'due_date'            => $validated['due_date'] ?? null,
            'paid_amount'         => 0.00,
            'balance_amount'      => $netApproved,
            'status'              => !empty($validated['verified_date']) ? 'pending' : 'submitted',
            'remarks'             => $validated['remarks'] ?? null,
            'created_by'          => Auth::id(),
        ]);

        return redirect()->route('expenses.ra-bills.index')
            ->with('success', "✅ Contractor RA Bill #{$validated['ra_bill_number']} logged successfully!");
    }

    /**
     * Step 2.2 / 3.2: Site Engineer Verification & Corrections / Retentions Applied.
     */
    public function verify(Request $request, int $id): RedirectResponse
    {
        $raBill = RaBill::findOrFail($id);

        $validated = $request->validate([
            'verified_date'     => ['required', 'date'],
            'engineer_id'       => ['nullable', 'exists:engineers,id'],
            'engineer_name'     => ['nullable', 'string', 'max:255'],
            'correction_amount' => ['required', 'numeric', 'min:0'],
            'due_date'          => ['nullable', 'date'],
            'remarks'           => ['nullable', 'string', 'max:500'],
        ]);

        $engineerName = $validated['engineer_name'] ?? null;
        if (!empty($validated['engineer_id'])) {
            $eng = Engineer::find($validated['engineer_id']);
            if ($eng) {
                $engineerName = $eng->name . ($eng->designation ? " ({$eng->designation})" : '');
            }
        }
        if (empty($engineerName)) {
            $engineerName = 'Site Engineer';
        }

        DB::transaction(function () use ($raBill, $validated, $engineerName) {
            $gross = (float) $raBill->gross_amount;
            $correction = (float) $validated['correction_amount'];
            $netApproved = max(0.00, $gross - $correction);
            $paid = (float) $raBill->paid_amount;
            $balance = max(0.00, $netApproved - $paid);

            $status = 'pending';
            if ($paid >= $netApproved && $netApproved > 0) {
                $status = 'cleared';
            } elseif ($paid > 0) {
                $status = 'partially_paid';
            }

            $raBill->update([
                'verified_date'       => $validated['verified_date'],
                'engineer_name'       => $engineerName,
                'correction_amount'   => $correction,
                'net_approved_amount' => $netApproved,
                'due_date'            => $validated['due_date'] ?? $raBill->due_date,
                'balance_amount'      => $balance,
                'status'              => $status,
                'remarks'             => $validated['remarks'] ?? $raBill->remarks,
            ]);
        });

        return redirect()->route('expenses.ra-bills.index')
            ->with('success', "✅ Site Engineer Verification & Corrections applied for RA Bill #{$raBill->ra_bill_number}!");
    }

    /**
     * Step 2.4 & 3.5: Disburse Staggered Payment & Generate Payment Voucher.
     */
    public function disburse(Request $request, int $id): RedirectResponse
    {
        $raBill = RaBill::findOrFail($id);

        $validated = $request->validate([
            'payment_date'            => ['required', 'date'],
            'paid_amount'             => ['required', 'numeric', 'min:0.01', 'max:' . max(0.01, (float) $raBill->balance_amount)],
            'payment_mode'            => ['required', 'string'],
            'company_bank_account_id' => ['required', 'exists:company_bank_accounts,id'],
            'reference_no'            => ['nullable', 'string', 'max:100'],
            'remarks'                 => ['nullable', 'string', 'max:500'],
        ]);

        $service = app(ChequeRealizationService::class);

        try {
            $voucher = DB::transaction(function () use ($raBill, $validated, $service) {
                $contractorName = $raBill->contractor_name ?: ($raBill->contractor?->name ?? 'Contractor');

                // Step 2.5 & 5.6: Generate Payment Voucher and deduct corporate treasury balance
                $voucher = $service->recordPayment([
                    'company_bank_account_id' => (int) $validated['company_bank_account_id'],
                    'payee_id'                => $raBill->contractor_id,
                    'payee_name'              => $contractorName,
                    'amount'                  => (float) $validated['paid_amount'],
                    'payment_date'            => $validated['payment_date'],
                    'payment_mode'            => $validated['payment_mode'],
                    'reference_no'            => $validated['reference_no'] ?? null,
                    'purpose'                 => 'supplier_payment',
                    'narration'               => "Staggered RA Bill Disbursement for #{$raBill->ra_bill_number} ({$contractorName})",
                    'created_by'              => Auth::id(),
                    'system_id'               => Auth::user()->system_id ?? 1,
                ]);

                // Create RA Bill Payment Record
                RaBillPayment::create([
                    'system_id'               => Auth::user()->system_id ?? 1,
                    'ra_bill_id'              => $raBill->id,
                    'payment_date'            => $validated['payment_date'],
                    'paid_amount'             => (float) $validated['paid_amount'],
                    'payment_mode'            => $validated['payment_mode'],
                    'company_bank_account_id' => (int) $validated['company_bank_account_id'],
                    'reference_no'            => $validated['reference_no'] ?? null,
                    'voucher_id'              => $voucher->id,
                    'status'                  => 'paid',
                    'remarks'                 => $validated['remarks'] ?? null,
                    'created_by'              => Auth::id(),
                ]);

                // Recalculate RA Bill balances and status
                $raBill->recalculateBalances();

                return $voucher;
            });

            $bank = CompanyBankAccount::find($validated['company_bank_account_id'])?->bank_name ?? 'Bank';
            $formattedAmount = number_format((float) $validated['paid_amount'], 2);

            return redirect()->route('vouchers.payment-voucher-print', $voucher->id)
                ->with('success', "✅ Staggered Disbursement of ₹{$formattedAmount} released via {$bank}! Payment Voucher #{$voucher->voucher_number} generated.");
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Delete an RA bill record if in pending state.
     */
    public function destroy(int $id): RedirectResponse
    {
        $raBill = RaBill::findOrFail($id);

        if ($raBill->paid_amount > 0) {
            return redirect()->back()->with('error', 'Cannot delete an RA Bill with logged payment disbursements.');
        }

        $raBill->delete();

        return redirect()->route('expenses.ra-bills.index')
            ->with('success', 'RA Bill deleted successfully.');
    }
}
