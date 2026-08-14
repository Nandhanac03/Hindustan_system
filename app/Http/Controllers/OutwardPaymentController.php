<?php

namespace App\Http\Controllers;

use App\Models\CompanyBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OutwardPaymentController extends Controller
{
    /**
     * Show the Make Payment form (Screen 5.5).
     */
    public function create(Request $request): View
    {
        $companyBankAccounts = CompanyBankAccount::orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        // Fetch Payees grouped by their Type (e.g., Supplier, Partner, Contractor, etc.)
        $payeesByType = \App\Models\Payee::orderBy('name')
            ->get()
            ->groupBy('type');

        // Extract the unique types for the Payment Type dropdown
        $paymentTypes = $payeesByType->keys()->toArray();

        return view('outward-payments.create', compact('companyBankAccounts', 'paymentTypes', 'payeesByType'));
    }

    /**
     * Store the payment and generate voucher.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_type' => 'required|string',
            'payee' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'payment_mode' => 'required|string',
            'company_bank_account_id' => 'required|exists:company_bank_accounts,id',
            'payment_date' => 'required|date',
            'remarks' => 'nullable|string',
            'reference_no' => 'nullable|string',
            'utr_no' => 'nullable|string',
        ]);

        $bankAccount = CompanyBankAccount::findOrFail($validated['company_bank_account_id']);

        if ($bankAccount->current_balance < $validated['amount']) {
            return back()->withInput()->withErrors(['amount' => 'Insufficient balance in the selected account.']);
        }

        // Deduct from bank account balance
        $bankAccount->current_balance -= $validated['amount'];
        $bankAccount->save();

        // Normally you would save this to an OutwardPayment or Voucher model, 
        // but for now we will pass the data directly to the session for the voucher view
        // to render Screen 5.6
        $paymentData = $validated;
        $paymentData['payment_no'] = 'PV/' . date('Y-y', strtotime('+1 year')) . '/' . str_pad(rand(100, 999), 6, '0', STR_PAD_LEFT);
        $paymentData['bank_name'] = $bankAccount->bank_name;
        $paymentData['account_number'] = $bankAccount->account_number;
        $paymentData['status'] = 'Paid';

        return redirect()->route('outward-payments.voucher')->with('paymentData', $paymentData);
    }

    /**
     * Show the generated Payment Voucher (Screen 5.6).
     */
    public function voucher(Request $request)
    {
        $paymentData = session('paymentData');

        if (!$paymentData) {
            return redirect()->route('outward-payments.create');
        }

        return view('outward-payments.voucher', compact('paymentData'));
    }
}
