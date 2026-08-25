<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Loan;
use App\Models\LoanDisbursal;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanDisbursalController extends Controller
{
    public function index(Request $request): View
    {
        $systemId = Auth::user()->system_id ?? 1;

        $loans = Loan::with('project')->where('system_id', $systemId)->orderBy('lender_name')->get();
        $banks = Bank::orderBy('bank_name')->get();

        $query = LoanDisbursal::with(['loan.project', 'creator', 'poster', 'canceller'])
            ->where('system_id', $systemId);

        // Apply filters
        if ($request->filled('loan_id')) {
            $query->where('loan_id', $request->loan_id);
        }
        if ($request->filled('bank_name')) {
            $query->whereHas('loan', function ($q) use ($request) {
                $q->where('lender_name', $request->bank_name);
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('disbursal_date')) {
            $query->whereDate('disbursal_date', $request->disbursal_date);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('disbursal_no', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%")
                  ->orWhere('transaction_no', 'like', "%{$search}%")
                  ->orWhere('remarks', 'like', "%{$search}%")
                  ->orWhereHas('loan', function ($ql) use ($search) {
                      $ql->where('lender_name', 'like', "%{$search}%")
                         ->orWhere('loan_account_no', 'like', "%{$search}%");
                  });
            });
        }

        $disbursals = $query->latest()->paginate(50)->withQueryString();

        // Calculate KPIs
        $kpiQuery = LoanDisbursal::where('system_id', $systemId);
        $totalDisbursed = (float) (clone $kpiQuery)->where('status', 'POSTED')->sum('amount');
        $totalDrafts = (float) (clone $kpiQuery)->where('status', 'DRAFT')->sum('amount');
        $totalCancelled = (float) (clone $kpiQuery)->where('status', 'CANCELLED')->sum('amount');

        return view('loans.disbursals.index', compact(
            'loans',
            'banks',
            'disbursals',
            'totalDisbursed',
            'totalDrafts',
            'totalCancelled'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'loan_id'         => ['required', 'exists:loans,id'],
            'disbursal_date'  => ['required', 'date'],
            'amount'          => ['required', 'numeric', 'min:0.01'],
            'disbursal_type'  => ['required', 'string', 'max:50'],
            'reference_no'    => ['nullable', 'string', 'max:191'],
            'transaction_no'  => ['nullable', 'string', 'max:191'],
            'remarks'         => ['nullable', 'string'],
        ]);

        $loan = Loan::findOrFail($validated['loan_id']);
        $amount = (float) $validated['amount'];

        // Validation: Sum of POSTED disbursals + current amount must not exceed loan principal_amount
        $existingDisbursed = (float) LoanDisbursal::where('loan_id', $loan->id)
            ->where('status', 'POSTED')
            ->sum('amount');

        $remainingPrincipal = (float) $loan->principal_amount - $existingDisbursed;

        if ($amount > $remainingPrincipal) {
            return back()->withInput()->with('error', 'Disbursal amount (₹' . number_format($amount, 2) . ') exceeds remaining undisbursed loan principal (₹' . number_format($remainingPrincipal, 2) . ').');
        }

        // Generate unique disbursal number
        $disbursalNo = 'DISB-LN-' . date('Ymd') . '-' . rand(1000, 9999);

        LoanDisbursal::create(array_merge($validated, [
            'disbursal_no' => $disbursalNo,
            'status'       => 'DRAFT',
            'created_by'   => Auth::id(),
        ]));

        return redirect()->route('loan-disbursals.index')
            ->with('success', 'Loan disbursal entry created successfully in DRAFT mode.');
    }

    public function update(Request $request, LoanDisbursal $disbursal): RedirectResponse
    {
        if ($disbursal->status !== 'DRAFT') {
            return redirect()->route('loan-disbursals.index')
                ->with('error', 'Only DRAFT disbursals can be updated.');
        }

        $validated = $request->validate([
            'loan_id'         => ['required', 'exists:loans,id'],
            'disbursal_date'  => ['required', 'date'],
            'amount'          => ['required', 'numeric', 'min:0.01'],
            'disbursal_type'  => ['required', 'string', 'max:50'],
            'reference_no'    => ['nullable', 'string', 'max:191'],
            'transaction_no'  => ['nullable', 'string', 'max:191'],
            'remarks'         => ['nullable', 'string'],
        ]);

        $loan = Loan::findOrFail($validated['loan_id']);
        $amount = (float) $validated['amount'];

        // Validation: Sum of POSTED disbursals (excluding current) + current amount must not exceed principal_amount
        $existingDisbursed = (float) LoanDisbursal::where('loan_id', $loan->id)
            ->where('id', '!=', $disbursal->id)
            ->where('status', 'POSTED')
            ->sum('amount');

        $remainingPrincipal = (float) $loan->principal_amount - $existingDisbursed;

        if ($amount > $remainingPrincipal) {
            return back()->withInput()->with('error', 'Disbursal amount (₹' . number_format($amount, 2) . ') exceeds remaining undisbursed loan principal (₹' . number_format($remainingPrincipal, 2) . ').');
        }

        $disbursal->update($validated);

        return redirect()->route('loan-disbursals.index')
            ->with('success', 'Loan disbursal entry updated successfully.');
    }

    public function destroy(LoanDisbursal $disbursal): RedirectResponse
    {
        if ($disbursal->status !== 'DRAFT') {
            return redirect()->route('loan-disbursals.index')
                ->with('error', 'Only DRAFT disbursals can be deleted.');
        }

        $disbursal->delete();

        return redirect()->route('loan-disbursals.index')
            ->with('success', 'Loan disbursal entry deleted successfully.');
    }

    public function post(LoanDisbursal $disbursal): RedirectResponse
    {
        if ($disbursal->status !== 'DRAFT') {
            return redirect()->route('loan-disbursals.index')
                ->with('error', 'Only DRAFT disbursals can be posted.');
        }

        $loan = $disbursal->loan;
        $amount = (float) $disbursal->amount;

        // Re-validate that amount does not exceed remaining principal upon posting
        $existingDisbursed = (float) LoanDisbursal::where('loan_id', $loan->id)
            ->where('id', '!=', $disbursal->id)
            ->where('status', 'POSTED')
            ->sum('amount');

        $remainingPrincipal = (float) $loan->principal_amount - $existingDisbursed;

        if ($amount > $remainingPrincipal) {
            return redirect()->route('loan-disbursals.index')
                ->with('error', 'Cannot post. Disbursal amount exceeds remaining undisbursed loan principal (₹' . number_format($remainingPrincipal, 2) . ').');
        }

        $disbursal->update([
            'status'    => 'POSTED',
            'posted_by' => Auth::id(),
        ]);

        return redirect()->route('loan-disbursals.index')
            ->with('success', 'Loan disbursal entry has been POSTED successfully.');
    }

    public function cancel(Request $request, LoanDisbursal $disbursal): RedirectResponse
    {
        if ($disbursal->status !== 'POSTED') {
            return redirect()->route('loan-disbursals.index')
                ->with('error', 'Only POSTED disbursals can be cancelled.');
        }

        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string', 'min:3'],
        ]);

        $disbursal->update([
            'status'              => 'CANCELLED',
            'cancelled_by'        => Auth::id(),
            'cancelled_at'        => now(),
            'cancellation_reason' => $validated['cancellation_reason'],
        ]);

        return redirect()->route('loan-disbursals.index')
            ->with('success', 'Loan disbursal entry has been CANCELLED.');
    }
}
