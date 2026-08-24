<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Account;
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
use Illuminate\Validation\Rule;
use Illuminate\View\View;

use App\Models\Engineer;
use App\Models\PaymentMode;

class RaBillController extends Controller
{
    /**
     * Index redirect to main verification page.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('expenses.ra-bills.verification');
    }

    /**
     * Page 2: Dedicated RA Bill Verification & Inward Claims Page.
     */
    public function verification(): View
    {
        $data = $this->getCommonData();
        return view('expenses.ra-bills.verification', $data);
    }

    /**
     * Page 3: Dedicated Contractor Payment Release Page.
     */
    public function paymentRelease(): View
    {
        $data = $this->getCommonData();
        return view('expenses.ra-bills.payment-release', $data);
    }

    /**
     * Page 4: Dedicated Contractor Ledger View Page.
     */
    public function ledger(): View
    {
        $data = $this->getCommonData();
        return view('expenses.ra-bills.ledger', $data);
    }

    /**
     * Helper method to retrieve common data models and aggregations.
     */
    private function getCommonData(): array
    {
        $systemId = Auth::user()->system_id ?? 1;

        $raBills = RaBill::with([
            'contractor',
            'project',
            'unit',
            'payments' => function ($query) {
                $query->orderBy('payment_date', 'asc')->orderBy('id', 'asc')->with(['companyBankAccount', 'voucher']);
            }
        ])
            ->where('system_id', $systemId)
            ->orderBy('id', 'desc')
            ->get();

        $contractors = Payee::where('system_id', $systemId)
            ->where('type', 'Contractor')
            ->with('linkedAccount')
            ->orderBy('name')
            ->get();

        if ($contractors->isEmpty()) {
            $contractors = Payee::where('system_id', $systemId)
                ->whereIn('type', ['Contractor', 'Supplier'])
                ->with('linkedAccount')
                ->orderBy('name')
                ->get();
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

        // Payment Modes from PaymentMode Master
        $paymentModes = PaymentMode::where('system_id', $systemId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($paymentModes->isEmpty()) {
            $paymentModes = PaymentMode::where('status', 'active')->orderBy('name')->get();
        }

        if ($paymentModes->isEmpty()) {
            $paymentModes = collect([
                (object) ['code' => 'NEFT', 'name' => 'NEFT Transfer'],
                (object) ['code' => 'RTGS', 'name' => 'RTGS Transfer'],
                (object) ['code' => 'Cheque', 'name' => 'Cheque'],
                (object) ['code' => 'UPI', 'name' => 'UPI / Net Banking'],
                (object) ['code' => 'Cash', 'name' => 'Cash'],
            ]);
        }

        // Calculate KPI summaries
        $totalGross = $raBills->sum('gross_amount');
        $totalCorrections = $raBills->sum('correction_amount');
        $totalNetApproved = $raBills->sum('net_approved_amount');
        $totalPaid = $raBills->sum('paid_amount');
        $totalBalance = $raBills->sum('balance_amount');

        // Section 2: Contractor Master & Ledger Aggregations
        $contractorLedgerSummaries = $contractors->map(function ($c) use ($raBills) {
            $cBills = $raBills->filter(fn($b) => $b->contractor_id == $c->id);
            return [
                'id'                 => $c->id,
                'name'               => $c->name,
                'type'               => $c->type,
                'phone'              => $c->phone,
                'email'              => $c->email,
                'gstin'              => $c->gstin,
                'pan'                => $c->pan,
                'address'            => $c->address,
                'account_code'       => $c->linkedAccount?->code ?? 'N/A',
                'total_gross'        => (float) $cBills->sum('gross_amount'),
                'total_corrections'  => (float) $cBills->sum('correction_amount'),
                'total_net_approved' => (float) $cBills->sum('net_approved_amount'),
                'total_paid'         => (float) $cBills->sum('paid_amount'),
                'total_balance'      => (float) $cBills->sum('balance_amount'),
                'bills_count'        => $cBills->count(),
            ];
        });

        // Detailed Ledger Statement Entries (Claims & Payment Releases)
        $allLedgerEntries = collect();
        foreach ($raBills as $bill) {
            $cName = $bill->contractor_name ?: ($bill->contractor?->name ?? 'Contractor');
            $pName = $bill->project?->name ?? 'Site Project';
            $uName = $bill->unit_name ?: ($bill->unit?->door_no ?? '');

            // 1. Verified RA Bill Claim (Accrued Credit)
            $allLedgerEntries->push([
                'type'              => 'CLAIM',
                'date'              => $bill->verified_date ? $bill->verified_date->format('Y-m-d') : ($bill->submit_date ? $bill->submit_date->format('Y-m-d') : null),
                'date_formatted'    => $bill->verified_date ? $bill->verified_date->format('d/m/Y') : ($bill->submit_date ? $bill->submit_date->format('d/m/Y') : '—'),
                'contractor_id'     => $bill->contractor_id,
                'contractor_name'   => $cName,
                'project_name'      => $pName,
                'unit_name'         => $uName,
                'ra_bill_id'        => $bill->id,
                'ra_bill_number'    => $bill->ra_bill_number,
                'particulars'       => "RA Bill #{$bill->ra_bill_number} Verification Sign-off" . ($bill->engineer_name ? " (By: {$bill->engineer_name})" : ''),
                'gross_amount'      => (float) $bill->gross_amount,
                'correction_amount' => (float) $bill->correction_amount,
                'net_approved'      => (float) $bill->net_approved_amount,
                'paid_amount'       => 0.00,
                'status'            => $bill->verified_date ? 'Verified' : 'Submitted',
                'voucher_id'        => null,
                'ref_no'            => "RA-{$bill->ra_bill_number}",
            ]);

            // 2. Disbursed Payment Release (Payment Debit)
            foreach ($bill->payments as $pay) {
                $bankName = $pay->companyBankAccount?->bank_name ?? 'Bank';
                $allLedgerEntries->push([
                    'type'              => 'DISBURSEMENT',
                    'date'              => $pay->payment_date ? $pay->payment_date->format('Y-m-d') : null,
                    'date_formatted'    => $pay->payment_date ? $pay->payment_date->format('d/m/Y') : '—',
                    'contractor_id'     => $bill->contractor_id,
                    'contractor_name'   => $cName,
                    'project_name'      => $pName,
                    'unit_name'         => $uName,
                    'ra_bill_id'        => $bill->id,
                    'ra_bill_number'    => $bill->ra_bill_number,
                    'particulars'       => "Payment Released for RA Bill #{$bill->ra_bill_number} via {$bankName} ({$pay->payment_mode})",
                    'gross_amount'      => 0.00,
                    'correction_amount' => 0.00,
                    'net_approved'      => 0.00,
                    'paid_amount'       => (float) $pay->paid_amount,
                    'status'            => 'Disbursed',
                    'voucher_id'        => $pay->voucher_id,
                    'ref_no'            => $pay->reference_no ?: "VCH-{$pay->voucher_id}",
                ]);
            }
        }

        // Sort ledger entries chronologically
        $allLedgerEntries = $allLedgerEntries->sortBy('date')->values();

        return compact(
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
            'totalBalance',
            'contractorLedgerSummaries',
            'allLedgerEntries',
            'paymentModes'
        );
    }

    /**
     * Step 2.1 / 3.1: Log new Contractor Inward RA Bill.
     */
    public function store(Request $request): RedirectResponse
    {
        $systemId = Auth::user()->system_id ?? 1;

        $validated = $request->validate([
            'ra_bill_number' => [
                'required',
                'string',
                Rule::unique('ra_bills', 'ra_bill_number')->where('system_id', $systemId),
            ],
            'contractor_id'     => ['required', 'exists:payees,id'],
            'contractor_name'   => ['nullable', 'string', 'max:255'],
            'project_id'        => ['required', 'exists:projects,id'],
            'unit_id'           => ['nullable', 'exists:hindustan_units,id'],
            'submit_date'       => ['required', 'date'],
            'gross_amount'      => ['required', 'numeric', 'min:0.01'],
            'additional_amount' => ['nullable', 'numeric', 'min:0'],
            'verified_date'     => ['nullable', 'date'],
            'engineer_name'     => ['nullable', 'string', 'max:255'],
            'correction_amount' => ['nullable', 'numeric', 'min:0'],
            'due_date'          => ['nullable', 'date'],
            'remarks'           => ['nullable', 'string', 'max:500'],
        ], [
            'ra_bill_number.required' => 'The RA Bill Number field is required.',
            'ra_bill_number.unique'   => 'This RA Bill Number already exists for your company.',
            'contractor_id.required'  => 'The Contractor field is required.',
            'project_id.required'     => 'The Site Project field is required.',
            'unit_id.required'        => 'The Unit field is required.',
            'submit_date.required'    => 'The Submit Date field is required.',
            'gross_amount.required'   => 'The Gross Amount field is required.',
        ]);

        $gross = (float) $validated['gross_amount'];
        $additional = (float) ($validated['additional_amount'] ?? 0.00);

        // If user entered a percentage number (e.g. 12 for 12% or 20 for 20%), convert to rupee amount
        if ($additional > 0 && $additional <= 100 && $gross >= 1000) {
            $additional = round(($gross * $additional) / 100, 2);
        }

        $correction = (float) ($validated['correction_amount'] ?? 0.00);
        $netApproved = max(0.00, $gross + $additional - $correction);

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
            'additional_amount'   => $additional,
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

        return redirect()->back()
            ->with('success', "✅ Contractor RA Bill #{$validated['ra_bill_number']} logged successfully!");
    }

    /**
     * Step 2.2 / 3.2: Site Engineer Verification & Corrections / Retentions Applied.
     */
    public function verify(Request $request, int $id): RedirectResponse
    {
        $raBill = RaBill::findOrFail($id);

        $maxAllowedCorrection = (float) $raBill->gross_amount + (float) $raBill->additional_amount;

        $validated = $request->validate([
            'verified_date'     => ['required', 'date'],
            'engineer_id'       => ['nullable', 'exists:engineers,id'],
            'engineer_name'     => ['nullable', 'string', 'max:255'],
            'correction_amount' => ['required', 'numeric', 'min:0', 'max:' . $maxAllowedCorrection],
            'due_date'          => ['nullable', 'date'],
            'remarks'           => ['nullable', 'string', 'max:500'],
        ], [
            'correction_amount.max' => 'Correction cannot exceed the Total Bill Amount (Gross + Additional) of ₹'
                . number_format($maxAllowedCorrection, 2) . '.',
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
            $additional = (float) $raBill->additional_amount;
            $correction = (float) $validated['correction_amount'];
            $netApproved = max(0.00, $gross + $additional - $correction);
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

        return redirect()->back()
            ->with('success', "✅ Site Engineer Verification & Corrections applied for RA Bill #{$raBill->ra_bill_number}!");
    }

    /**
     * Step 2.4 & 3.5: Disburse Staggered Payment & Generate Payment Voucher.
     */
    public function disburse(Request $request, int $id): RedirectResponse
    {
        $raBill = RaBill::findOrFail($id);

        if (!$raBill->verified_date) {
            return redirect()->back()->with('error', '⚠️ Payment disbursement can only be processed after Site Engineer Verification Sign-Off.');
        }

        if ((float) $raBill->balance_amount <= 0.00) {
            return redirect()->back()->with('error', '⚠️ This RA Bill is already fully cleared. No further disbursement is allowed.');
        }

        $validated = $request->validate([
            'payment_date'            => ['required', 'date'],
            'paid_amount'             => ['required', 'numeric', 'min:0.01', 'max:' . (float) $raBill->balance_amount],
            'payment_mode'            => ['required', 'string'],
            'company_bank_account_id' => ['required', 'exists:company_bank_accounts,id'],
            'reference_no'            => ['nullable', 'string', 'max:100'],
            'remarks'                 => ['nullable', 'string', 'max:500'],
        ]);

        $service = app(ChequeRealizationService::class);

        try {
            $voucher = DB::transaction(function () use ($raBill, $validated, $service) {
                $contractorName = $raBill->contractor_name ?: ($raBill->contractor?->name ?? 'Contractor');

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

        return redirect()->back()
            ->with('success', 'RA Bill deleted successfully.');
    }

    /**
     * Store a new Contractor Master record with auto-linked liability ledger account.
     */
    public function storeContractor(Request $request): RedirectResponse
    {
        $systemId = Auth::user()->system_id ?? 1;

        $request->validate([
            'name'    => 'required|string|max:191|unique:payees,name,NULL,id,system_id,' . $systemId . ',type,Contractor',
            'phone'   => 'nullable|string|max:50',
            'email'   => 'nullable|email|max:191',
            'gstin'   => 'nullable|string|size:15|alpha_num',
            'pan'     => 'nullable|string|max:100',
            'address' => 'nullable|string',
        ], [
            'name.required' => 'Contractor Name is required.',
            'name.unique'   => 'A Contractor or Payee with this name already exists.',
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

            $account = Account::create([
                'system_id' => $systemId,
                'code'      => $accountCode,
                'name'      => $request->name . ' (Payable)',
                'type'      => 'Liability',
                'is_active' => true,
            ]);

            Payee::create([
                'system_id'         => $systemId,
                'type'              => 'Contractor',
                'name'              => $request->name,
                'phone'             => $request->phone,
                'email'             => $request->email,
                'gstin'             => $request->gstin,
                'pan'               => $request->pan,
                'address'           => $request->address,
                'linked_account_id' => $account->id,
            ]);
        });

        return redirect()->back()
            ->with('success', '✅ Contractor registered successfully and ledger account created!');
    }
}