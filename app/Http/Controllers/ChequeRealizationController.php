<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\CompanyBankAccount;
use App\Models\PaymentMode;
use App\Services\ChequeRealizationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ChequeRealizationController extends Controller
{
    public function __construct(
        private readonly ChequeRealizationService $realizationService
    ) {}

    /**
     * Show all instruments awaiting bank clearance.
     */
    public function realizationQueue(Request $request): View
    {
        $query = Receipt::with(['companyBankAccount', 'customer', 'project', 'bank'])
            ->whereIn('realization_status', ['pending', 'cheque_in_hand', 'deposited', 'in_clearing', 'bounced', 'cancelled'])
            ->latest('receipt_date');

        if ($request->filled('company_bank_account_id')) {
            $query->where('company_bank_account_id', $request->input('company_bank_account_id'));
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->input('customer_id'));
        }

        $pendingReceipts = $query->paginate(25)->withQueryString();

        $companyBankAccounts = CompanyBankAccount::orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $totalPendingAmount = Receipt::whereIn('realization_status', ['pending', 'cheque_in_hand', 'deposited', 'in_clearing'])
            ->sum('amount');

        $customers = \App\Models\Customer::orderBy('name')->get(['id', 'name']);
        
        $chequeStatuses = \App\Models\ChequeStatus::where('is_active', 1)->get();

        return view('cheque-realization.queue', compact(
            'pendingReceipts',
            'companyBankAccounts',
            'totalPendingAmount',
            'customers',
            'chequeStatuses'
        ));
    }

    /**
     * Show realized instruments.
     */
    public function realizedReceipts(Request $request): View
    {
        $query = Receipt::with(['companyBankAccount', 'customer', 'project', 'bank'])
            ->where('realization_status', 'realized')
            ->latest('receipt_date');

        if ($request->filled('company_bank_account_id')) {
            $query->where('company_bank_account_id', $request->input('company_bank_account_id'));
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->input('customer_id'));
        }

        $pendingReceipts = $query->paginate(25)->withQueryString();

        $companyBankAccounts = CompanyBankAccount::orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $totalPendingAmount = Receipt::where('realization_status', 'realized')
            ->sum('amount');

        $customers = \App\Models\Customer::orderBy('name')->get(['id', 'name']);
        
        $chequeStatuses = \App\Models\ChequeStatus::where('is_active', 1)->get();

        return view('cheque-realization.queue', compact(
            'pendingReceipts',
            'companyBankAccounts',
            'totalPendingAmount',
            'customers',
            'chequeStatuses'
        ));
    }

    /**
     * Show realization process details screen.
     */
    public function showRealizationProcess(Request $request, int $id): View
    {
        $receipt = Receipt::with(['companyBankAccount', 'customer', 'project', 'bank'])
            ->findOrFail($id);

        $companyBankAccounts = CompanyBankAccount::orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $chequeStatuses = \App\Models\ChequeStatus::where('is_active', 1)->get();

        return view('cheque-realization.detail', compact('receipt', 'companyBankAccounts', 'chequeStatuses'));
    }

    /**
     * Mark as Realized
     */
    public function realize(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'company_bank_account_id' => ['required', 'exists:company_bank_accounts,id'],
            'realization_date' => ['required', 'date'],
            'bank_reference_no' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $receipt = Receipt::findOrFail($id);

        try {
            $this->realizationService->realize($receipt, [
                'realized_by' => auth()->id(),
                'remarks'     => $validated['remarks'] ?? null,
                'company_bank_account_id' => $validated['company_bank_account_id'],
                'realization_date' => $validated['realization_date'],
                'bank_reference_no' => $validated['bank_reference_no'] ?? null,
            ]);

            $bankName = $receipt->companyBankAccount?->bank_name ?? 'Company Bank Account';
            $amount   = number_format((float) $receipt->amount, 2);

            return redirect()->route('treasury.dashboard')->with(
                'success',
                "✅ Receipt #{$id} Realized! ₹{$amount} credited to {$bankName}. Treasury balance updated."
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark as Bounced
     */
    public function markBounced(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $receipt = Receipt::findOrFail($id);

        try {
            $this->realizationService->markBounced($receipt, [
                'changed_by' => auth()->id(),
                'remarks'    => $validated['remarks'] ?? 'Cheque dishonoured by bank.',
            ]);

            return redirect()->route('cheque-realization.queue')->with(
                'success',
                "❌ Receipt #{$id} marked as Bounced. No balance change. Audit log recorded."
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Advance Status (e.g. to deposited)
     */
    public function advanceStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'new_status' => ['required', 'in:pending,cheque_in_hand,deposited,in_clearing,cancelled'],
            'remarks'    => ['nullable', 'string', 'max:500'],
            'company_bank_account_id' => ['nullable', 'exists:company_bank_accounts,id'],
            'bank_reference_no' => ['nullable', 'string', 'max:255'],
        ]);

        $receipt = Receipt::findOrFail($id);

        try {
            $this->realizationService->advanceStatus($receipt, $validated['new_status'], [
                'changed_by' => auth()->id(),
                'remarks'    => $validated['remarks'] ?? null,
                'company_bank_account_id' => $validated['company_bank_account_id'] ?? null,
                'bank_reference_no' => $validated['bank_reference_no'] ?? null,
            ]);

            $label = Receipt::STATUSES[$validated['new_status']] ?? $validated['new_status'];

            return redirect()->route('cheque-realization.queue')->with('success', "Receipt #{$id} updated to: {$label}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
