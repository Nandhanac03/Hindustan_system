<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Voucher;
use App\Models\VoucherLine;
use App\Models\LedgerEntry;
use App\Models\Project;
use App\Models\Payee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    public function createReceipt()
    {
        $user = Auth::user();
        $systemId = $user->system_id;
        $this->ensureDefaultAccounts($systemId);

        $accounts = Account::where('system_id', $systemId)->where('is_active', true)->get();
        
        $assetAccounts = $accounts->filter(fn($acc) => strtolower($acc->type) === 'asset' && $acc->code !== 'BANK-KAR-213');
        
        // Load customer profiles and map to ledgers
        $customers = Customer::all();
        foreach ($customers as $customer) {
            $customerAcc = Account::firstOrCreate(
                ['system_id' => $systemId, 'code' => 'CUST-REC-' . $customer->id],
                ['name' => $customer->name . ' (Receivable)', 'type' => 'Liability', 'is_active' => true]
            );
            $customer->ledger_account_id = $customerAcc->id;
        }
        
        $creditAccounts = $accounts->filter(fn($acc) => in_array(strtolower($acc->type), ['liability', 'income', 'equity']));

        // Generate voucher number
        $lastVoucher = Voucher::where('system_id', $systemId)
            ->where('type', 'Receipt')
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNum = $lastVoucher ? ((int) preg_replace('/[^0-9]/', '', $lastVoucher->voucher_number) + 1) : 1;
        $voucherNumber = 'RC-' . date('Y') . '-' . str_pad((string)$nextNum, 5, '0', STR_PAD_LEFT);
        $projects = Project::all();

        return view('vouchers.receipt', compact('assetAccounts', 'creditAccounts', 'customers', 'voucherNumber', 'projects'));
    }

    public function storeReceipt(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $request->validate([
            'voucher_number' => 'required|string',
            'date' => 'required|date',
            'destination_account_id' => 'required|exists:accounts,id',
            'credit_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'gst_behavior' => 'required|in:inclusive,exclusive',
            'narration' => 'nullable|string',
            'project_id' => 'nullable|integer',
            'payment_mode' => 'nullable|string',
            'gst_rate' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($request, $systemId, $user) {
            $baseAmount = (float)$request->amount;
            $gstBehavior = $request->gst_behavior;
            $gstPct = (float)($request->gst_rate ?? 5.0);
            $gstRate = $gstPct / 100.0;
            $halfGstRate = $gstRate / 2.0;
            
            // Calculate output CGST and SGST split
            $cgst = 0.0;
            $sgst = 0.0;
            $totalAmount = $baseAmount;

            if ($gstBehavior === 'inclusive') {
                $base = round($baseAmount / (1 + $gstRate), 2);
                $cgst = round($base * $halfGstRate, 2);
                $sgst = round($base * $halfGstRate, 2);
                $partyCredit = $totalAmount - ($cgst + $sgst);
            } else {
                $cgst = round($baseAmount * $halfGstRate, 2);
                $sgst = round($baseAmount * $halfGstRate, 2);
                $totalAmount = $baseAmount + $cgst + $sgst;
                $partyCredit = $baseAmount;
            }

            // Create reference metadata json
            $referenceNo = json_encode([
                'project_id' => $request->project_id,
                'payment_mode' => $request->payment_mode,
                'gst_rate' => $gstPct,
            ]);

            // Create Voucher
            $voucher = Voucher::create([
                'system_id' => $systemId,
                'voucher_number' => $request->voucher_number,
                'type' => 'Receipt',
                'date' => $request->date,
                'narration' => $request->narration,
                'created_by' => $user->id,
                'status' => 'Posted',
                'reference_no' => $referenceNo,
            ]);

            // 1. Debit the Destination Account (Bank/Cash)
            $debitLine = VoucherLine::create([
                'voucher_id' => $voucher->id,
                'account_id' => $request->destination_account_id,
                'debit' => $totalAmount,
                'credit' => 0.00,
                'line_narration' => 'Debit to Destination Account',
            ]);

            LedgerEntry::create([
                'system_id' => $systemId,
                'account_id' => $request->destination_account_id,
                'voucher_id' => $voucher->id,
                'voucher_line_id' => $debitLine->id,
                'date' => $request->date,
                'debit' => $totalAmount,
                'credit' => 0.00,
                'running_balance' => 0.00,
            ]);

            // 2. Credit the Party Account (Customer)
            $creditLine = VoucherLine::create([
                'voucher_id' => $voucher->id,
                'account_id' => $request->credit_account_id,
                'debit' => 0.00,
                'credit' => $partyCredit,
                'line_narration' => 'Credit to Customer Ledger',
            ]);

            LedgerEntry::create([
                'system_id' => $systemId,
                'account_id' => $request->credit_account_id,
                'voucher_id' => $voucher->id,
                'voucher_line_id' => $creditLine->id,
                'date' => $request->date,
                'debit' => 0.00,
                'credit' => $partyCredit,
                'running_balance' => 0.00,
            ]);

            // 3. Tax Credits (Output CGST and Output SGST)
            if ($cgst > 0 || $sgst > 0) {
                $cgstAccount = Account::firstOrCreate(
                    ['system_id' => $systemId, 'code' => 'TAX-CGST-OUT'],
                    ['name' => 'Output CGST Account', 'type' => 'Liability', 'is_active' => true]
                );
                $sgstAccount = Account::firstOrCreate(
                    ['system_id' => $systemId, 'code' => 'TAX-SGST-OUT'],
                    ['name' => 'Output SGST Account', 'type' => 'Liability', 'is_active' => true]
                );

                $cgstLine = VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $cgstAccount->id,
                    'debit' => 0.00,
                    'credit' => $cgst,
                    'line_narration' => 'Output CGST 2.5%',
                ]);

                LedgerEntry::create([
                    'system_id' => $systemId,
                    'account_id' => $cgstAccount->id,
                    'voucher_id' => $voucher->id,
                    'voucher_line_id' => $cgstLine->id,
                    'date' => $request->date,
                    'debit' => 0.00,
                    'credit' => $cgst,
                    'running_balance' => 0.00,
                ]);

                $sgstLine = VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $sgstAccount->id,
                    'debit' => 0.00,
                    'credit' => $sgst,
                    'line_narration' => 'Output SGST 2.5%',
                ]);

                LedgerEntry::create([
                    'system_id' => $systemId,
                    'account_id' => $sgstAccount->id,
                    'voucher_id' => $voucher->id,
                    'voucher_line_id' => $sgstLine->id,
                    'date' => $request->date,
                    'debit' => 0.00,
                    'credit' => $sgst,
                    'running_balance' => 0.00,
                ]);
            }
        });

        return redirect()->route('vouchers.ledger.index')->with('status', 'Receipt Voucher created successfully.');
    }

    public function createPayment()
    {
        $user = Auth::user();
        $systemId = $user->system_id;
        $this->ensureDefaultAccounts($systemId);

        $accounts = Account::where('system_id', $systemId)->where('is_active', true)->get();
        
        $expenseAccounts = $accounts->filter(fn($acc) => strtolower($acc->type) === 'expense');
        $creditAccounts = $accounts->filter(fn($acc) => strtolower($acc->type) === 'asset' && $acc->code !== 'BANK-KAR-213');

        // Generate voucher number
        $lastVoucher = Voucher::where('system_id', $systemId)
            ->where('type', 'Payment')
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNum = $lastVoucher ? ((int) preg_replace('/[^0-9]/', '', $lastVoucher->voucher_number) + 1) : 1;
        $voucherNumber = 'PV-' . date('Y') . '-' . str_pad((string)$nextNum, 5, '0', STR_PAD_LEFT);
        $payees = Payee::all();

        return view('vouchers.payment', compact('expenseAccounts', 'creditAccounts', 'voucherNumber', 'payees'));
    }

    public function storePayment(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $request->validate([
            'voucher_number' => 'required|string',
            'date' => 'required|date',
            'debit_account_id' => 'required|exists:accounts,id',
            'credit_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'narration' => 'nullable|string',
            'payee_id' => 'nullable|integer',
            'gst_rate' => 'nullable|numeric',
            'tds_rate' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($request, $systemId, $user) {
            $baseAmount = (float)$request->amount;
            $gstPct = (float)($request->gst_rate ?? 0.0);
            $tdsPct = (float)($request->tds_rate ?? 0.0);

            $gstAmount = round($baseAmount * ($gstPct / 100.0), 2);
            $tdsAmount = round($baseAmount * ($tdsPct / 100.0), 2);
            $cgstVal = round($gstAmount / 2.0, 2);
            $sgstVal = round($gstAmount / 2.0, 2);
            $gstAmount = $cgstVal + $sgstVal;
            $netPaid = $baseAmount + $gstAmount - $tdsAmount;

            $meta = [
                'payee_id' => $request->payee_id,
                'gst_rate' => $gstPct,
                'tds_rate' => $tdsPct,
            ];
            $referenceNo = json_encode($meta);

            // Create Voucher
            $voucher = Voucher::create([
                'system_id' => $systemId,
                'voucher_number' => $request->voucher_number,
                'type' => 'Payment',
                'date' => $request->date,
                'narration' => $request->narration,
                'created_by' => $user->id,
                'status' => 'Posted',
                'reference_no' => $referenceNo,
            ]);

            // 1. Debit the Expense Account (Base Amount)
            $debitLine = VoucherLine::create([
                'voucher_id' => $voucher->id,
                'account_id' => $request->debit_account_id,
                'debit' => $baseAmount,
                'credit' => 0.00,
                'line_narration' => 'Debit Expense Ledger',
            ]);

            LedgerEntry::create([
                'system_id' => $systemId,
                'account_id' => $request->debit_account_id,
                'voucher_id' => $voucher->id,
                'voucher_line_id' => $debitLine->id,
                'date' => $request->date,
                'debit' => $baseAmount,
                'credit' => 0.00,
                'running_balance' => 0.00,
            ]);

            // 2. Debit Input GST (if applicable)
            if ($gstAmount > 0) {
                $cgstAccount = Account::firstOrCreate(
                    ['system_id' => $systemId, 'code' => 'TAX-CGST-IN'],
                    ['name' => 'Input CGST Account', 'type' => 'Asset', 'is_active' => true]
                );
                $sgstAccount = Account::firstOrCreate(
                    ['system_id' => $systemId, 'code' => 'TAX-SGST-IN'],
                    ['name' => 'Input SGST Account', 'type' => 'Asset', 'is_active' => true]
                );

                $cgstLine = VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $cgstAccount->id,
                    'debit' => $cgstVal,
                    'credit' => 0.00,
                    'line_narration' => 'Input CGST Split',
                ]);
                LedgerEntry::create([
                    'system_id' => $systemId,
                    'account_id' => $cgstAccount->id,
                    'voucher_id' => $voucher->id,
                    'voucher_line_id' => $cgstLine->id,
                    'date' => $request->date,
                    'debit' => $cgstVal,
                    'credit' => 0.00,
                    'running_balance' => 0.00,
                ]);

                $sgstLine = VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $sgstAccount->id,
                    'debit' => $sgstVal,
                    'credit' => 0.00,
                    'line_narration' => 'Input SGST Split',
                ]);
                LedgerEntry::create([
                    'system_id' => $systemId,
                    'account_id' => $sgstAccount->id,
                    'voucher_id' => $voucher->id,
                    'voucher_line_id' => $sgstLine->id,
                    'date' => $request->date,
                    'debit' => $sgstVal,
                    'credit' => 0.00,
                    'running_balance' => 0.00,
                ]);
            }

            // 3. Credit TDS Payable (if applicable)
            if ($tdsAmount > 0) {
                $tdsAccount = Account::firstOrCreate(
                    ['system_id' => $systemId, 'code' => 'TAX-TDS-PAY'],
                    ['name' => 'TDS Payable Account', 'type' => 'Liability', 'is_active' => true]
                );

                $tdsLine = VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $tdsAccount->id,
                    'debit' => 0.00,
                    'credit' => $tdsAmount,
                    'line_narration' => 'TDS Deduction Credit',
                ]);
                LedgerEntry::create([
                    'system_id' => $systemId,
                    'account_id' => $tdsAccount->id,
                    'voucher_id' => $voucher->id,
                    'voucher_line_id' => $tdsLine->id,
                    'date' => $request->date,
                    'debit' => 0.00,
                    'credit' => $tdsAmount,
                    'running_balance' => 0.00,
                ]);
            }

            // 4. Credit the Source Account (Bank/Cash) (Net Paid Amount)
            $creditLine = VoucherLine::create([
                'voucher_id' => $voucher->id,
                'account_id' => $request->credit_account_id,
                'debit' => 0.00,
                'credit' => $netPaid,
                'line_narration' => 'Credit Bank/Cash',
            ]);

            LedgerEntry::create([
                'system_id' => $systemId,
                'account_id' => $request->credit_account_id,
                'voucher_id' => $voucher->id,
                'voucher_line_id' => $creditLine->id,
                'date' => $request->date,
                'debit' => 0.00,
                'credit' => $netPaid,
                'running_balance' => 0.00,
            ]);
        });

        return redirect()->route('vouchers.ledger.index')->with('status', 'Payment Voucher created successfully.');
    }

    public function createContra()
    {
        $user = Auth::user();
        $systemId = $user->system_id;
        $this->ensureDefaultAccounts($systemId);

        $accounts = Account::where('system_id', $systemId)->where('is_active', true)->get();
        $assetAccounts = $accounts->filter(fn($acc) => strtolower($acc->type) === 'asset' && $acc->code !== 'BANK-KAR-213');

        // Generate voucher number
        $lastVoucher = Voucher::where('system_id', $systemId)
            ->where('type', 'Contra')
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNum = $lastVoucher ? ((int) preg_replace('/[^0-9]/', '', $lastVoucher->voucher_number) + 1) : 1;
        $voucherNumber = 'CN-' . date('Y') . '-' . str_pad((string)$nextNum, 5, '0', STR_PAD_LEFT);

        return view('vouchers.contra', compact('assetAccounts', 'voucherNumber'));
    }

    public function storeContra(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $request->validate([
            'voucher_number' => 'required|string',
            'date' => 'required|date',
            'destination_account_id' => 'required|exists:accounts,id',
            'credit_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'narration' => 'nullable|string',
        ]);

        $destAcc = Account::findOrFail($request->destination_account_id);
        $creditAcc = Account::findOrFail($request->credit_account_id);

        if (strtolower($destAcc->type) !== 'asset' || strtolower($creditAcc->type) !== 'asset') {
            return back()->withErrors(['accounts' => 'Contra transactions must involve only cash or bank asset accounts.']);
        }

        DB::transaction(function () use ($request, $systemId, $user) {
            $amount = (float)$request->amount;

            // Create Voucher
            $voucher = Voucher::create([
                'system_id' => $systemId,
                'voucher_number' => $request->voucher_number,
                'type' => 'Contra',
                'date' => $request->date,
                'narration' => $request->narration,
                'created_by' => $user->id,
                'status' => 'Posted',
                'reference_no' => $request->reference_no ?? null,
            ]);

            // 1. Debit the Destination Account
            $debitLine = VoucherLine::create([
                'voucher_id' => $voucher->id,
                'account_id' => $request->destination_account_id,
                'debit' => $amount,
                'credit' => 0.00,
                'line_narration' => 'Debit to Destination Account',
            ]);

            LedgerEntry::create([
                'system_id' => $systemId,
                'account_id' => $request->destination_account_id,
                'voucher_id' => $voucher->id,
                'voucher_line_id' => $debitLine->id,
                'date' => $request->date,
                'debit' => $amount,
                'credit' => 0.00,
                'running_balance' => 0.00,
            ]);

            // 2. Credit the Source Account
            $creditLine = VoucherLine::create([
                'voucher_id' => $voucher->id,
                'account_id' => $request->credit_account_id,
                'debit' => 0.00,
                'credit' => $amount,
                'line_narration' => 'Credit to Source Account',
            ]);

            LedgerEntry::create([
                'system_id' => $systemId,
                'account_id' => $request->credit_account_id,
                'voucher_id' => $voucher->id,
                'voucher_line_id' => $creditLine->id,
                'date' => $request->date,
                'debit' => 0.00,
                'credit' => $amount,
                'running_balance' => 0.00,
            ]);
        });

        return redirect()->route('vouchers.ledger.index')->with('status', 'Contra Voucher created successfully.');
    }

    public function createJournal()
    {
        $user = Auth::user();
        $systemId = $user->system_id;
        $this->ensureDefaultAccounts($systemId);

        $accounts = Account::where('system_id', $systemId)->where('is_active', true)->get();

        // Generate voucher number
        $lastVoucher = Voucher::where('system_id', $systemId)
            ->where('type', 'Journal')
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNum = $lastVoucher ? ((int) preg_replace('/[^0-9]/', '', $lastVoucher->voucher_number) + 1) : 1;
        $voucherNumber = 'JV-' . date('Y') . '-' . str_pad((string)$nextNum, 5, '0', STR_PAD_LEFT);

        return view('vouchers.journal', compact('accounts', 'voucherNumber'));
    }

    public function storeJournal(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $request->validate([
            'voucher_number' => 'required|string',
            'date' => 'required|date',
            'narration' => 'nullable|string',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit' => 'nullable|numeric|min:0',
            'lines.*.credit' => 'nullable|numeric|min:0',
            'lines.*.line_narration' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $systemId, $user) {
            // Validate debits and credits match
            $totalDebit = 0.0;
            $totalCredit = 0.0;
            foreach ($request->lines as $line) {
                $totalDebit += (float)($line['debit'] ?? 0.0);
                $totalCredit += (float)($line['credit'] ?? 0.0);
            }

            if (abs($totalDebit - $totalCredit) > 0.001) {
                throw new \Exception('Journal entries must be balanced (Total Debits must equal Total Credits).');
            }

            // Create Voucher
            $voucher = Voucher::create([
                'system_id' => $systemId,
                'voucher_number' => $request->voucher_number,
                'type' => 'Journal',
                'date' => $request->date,
                'narration' => $request->narration,
                'created_by' => $user->id,
                'status' => 'Posted',
            ]);

            foreach ($request->lines as $line) {
                $debit = (float)($line['debit'] ?? 0.0);
                $credit = (float)($line['credit'] ?? 0.0);
                
                if ($debit == 0.0 && $credit == 0.0) {
                    continue; // Skip empty lines
                }

                $vl = VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $line['account_id'],
                    'debit' => $debit,
                    'credit' => $credit,
                    'line_narration' => $line['line_narration'] ?? 'Journal Line Item',
                ]);

                LedgerEntry::create([
                    'system_id' => $systemId,
                    'account_id' => $line['account_id'],
                    'voucher_id' => $voucher->id,
                    'voucher_line_id' => $vl->id,
                    'date' => $request->date,
                    'debit' => $debit,
                    'credit' => $credit,
                    'running_balance' => 0.00,
                ]);
            }
        });

        return redirect()->route('vouchers.ledger.index')->with('status', 'Journal Voucher created successfully.');
    }

    public function createSalesPurchase()
    {
        $user = Auth::user();
        $systemId = $user->system_id;
        $this->ensureDefaultAccounts($systemId);

        $accounts = Account::where('system_id', $systemId)->where('is_active', true)->get();
        
        // Ensure customers have ledger accounts
        $customers = Customer::all();
        foreach ($customers as $customer) {
            $customerAcc = Account::firstOrCreate(
                ['system_id' => $systemId, 'code' => 'CUST-REC-' . $customer->id],
                ['name' => $customer->name . ' (Receivable)', 'type' => 'Liability', 'is_active' => true]
            );
            $customer->ledger_account_id = $customerAcc->id;
        }

        // Generate voucher number
        $lastVoucher = Voucher::where('system_id', $systemId)
            ->whereIn('type', ['Sales', 'Purchase'])
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNum = $lastVoucher ? ((int) preg_replace('/[^0-9]/', '', $lastVoucher->voucher_number) + 1) : 1;
        $voucherNumber = 'SP-' . date('Y') . '-' . str_pad((string)$nextNum, 5, '0', STR_PAD_LEFT);

        return view('vouchers.sales_purchase', compact('accounts', 'customers', 'voucherNumber'));
    }

    public function storeSalesPurchase(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $request->validate([
            'voucher_number' => 'required|string',
            'date' => 'required|date',
            'transaction_type' => 'required|in:sales,purchase',
            'debit_account_id' => 'required|exists:accounts,id',
            'credit_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'gst_behavior' => 'required|in:inclusive,exclusive',
            'narration' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $systemId, $user) {
            $baseAmount = (float)$request->amount;
            $gstBehavior = $request->gst_behavior;
            $type = ucfirst($request->transaction_type);

            $cgst = 0.0;
            $sgst = 0.0;
            $totalAmount = $baseAmount;

            if ($gstBehavior === 'inclusive') {
                $base = $baseAmount / 1.05;
                $cgst = $base * 0.025;
                $sgst = $base * 0.025;
                $debitVal = $baseAmount;
                $creditVal = $base;
            } else {
                $cgst = $baseAmount * 0.025;
                $sgst = $baseAmount * 0.025;
                $totalAmount = $baseAmount + $cgst + $sgst;
                $debitVal = $totalAmount;
                $creditVal = $baseAmount;
            }

            // Create Voucher
            $voucher = Voucher::create([
                'system_id' => $systemId,
                'voucher_number' => $request->voucher_number,
                'type' => $type,
                'date' => $request->date,
                'narration' => $request->narration,
                'created_by' => $user->id,
                'status' => 'Posted',
                'reference_no' => $request->reference_no ?? null,
            ]);

            // 1. Debit Line
            $debitLine = VoucherLine::create([
                'voucher_id' => $voucher->id,
                'account_id' => $request->debit_account_id,
                'debit' => $debitVal,
                'credit' => 0.00,
                'line_narration' => 'Debit Account',
            ]);

            LedgerEntry::create([
                'system_id' => $systemId,
                'account_id' => $request->debit_account_id,
                'voucher_id' => $voucher->id,
                'voucher_line_id' => $debitLine->id,
                'date' => $request->date,
                'debit' => $debitVal,
                'credit' => 0.00,
                'running_balance' => 0.00,
            ]);

            // 2. Credit Line
            $creditLine = VoucherLine::create([
                'voucher_id' => $voucher->id,
                'account_id' => $request->credit_account_id,
                'debit' => 0.00,
                'credit' => $creditVal,
                'line_narration' => 'Credit Account',
            ]);

            LedgerEntry::create([
                'system_id' => $systemId,
                'account_id' => $request->credit_account_id,
                'voucher_id' => $voucher->id,
                'voucher_line_id' => $creditLine->id,
                'date' => $request->date,
                'debit' => 0.00,
                'credit' => $creditVal,
                'running_balance' => 0.00,
            ]);

            // 3. Taxes
            if ($cgst > 0 || $sgst > 0) {
                $taxCodePrefix = ($type === 'Sales') ? 'TAX-CGST-OUT' : 'TAX-CGST-IN';
                $taxNamePrefix = ($type === 'Sales') ? 'Output' : 'Input';
                
                $cgstAccount = Account::firstOrCreate(
                    ['system_id' => $systemId, 'code' => $taxCodePrefix],
                    ['name' => $taxNamePrefix . ' CGST Account', 'type' => ($type === 'Sales') ? 'Liability' : 'Asset', 'is_active' => true]
                );
                
                $sgstAccount = Account::firstOrCreate(
                    ['system_id' => $systemId, 'code' => str_replace('CGST', 'SGST', $taxCodePrefix)],
                    ['name' => $taxNamePrefix . ' SGST Account', 'type' => ($type === 'Sales') ? 'Liability' : 'Asset', 'is_active' => true]
                );

                $cgstDeb = ($type === 'Sales') ? 0.00 : $cgst;
                $cgstCred = ($type === 'Sales') ? $cgst : 0.00;
                $sgstDeb = ($type === 'Sales') ? 0.00 : $sgst;
                $sgstCred = ($type === 'Sales') ? $sgst : 0.00;

                $cgstLine = VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $cgstAccount->id,
                    'debit' => $cgstDeb,
                    'credit' => $cgstCred,
                    'line_narration' => $taxNamePrefix . ' CGST 2.5%',
                ]);

                LedgerEntry::create([
                    'system_id' => $systemId,
                    'account_id' => $cgstAccount->id,
                    'voucher_id' => $voucher->id,
                    'voucher_line_id' => $cgstLine->id,
                    'date' => $request->date,
                    'debit' => $cgstDeb,
                    'credit' => $cgstCred,
                    'running_balance' => 0.00,
                ]);

                $sgstLine = VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $sgstAccount->id,
                    'debit' => $sgstDeb,
                    'credit' => $sgstCred,
                    'line_narration' => $taxNamePrefix . ' SGST 2.5%',
                ]);

                LedgerEntry::create([
                    'system_id' => $systemId,
                    'account_id' => $sgstAccount->id,
                    'voucher_id' => $voucher->id,
                    'voucher_line_id' => $sgstLine->id,
                    'date' => $request->date,
                    'debit' => $sgstDeb,
                    'credit' => $sgstCred,
                    'running_balance' => 0.00,
                ]);
            }
        });

        return redirect()->route('vouchers.ledger.index')->with('status', $request->transaction_type === 'sales' ? 'Sales Invoice posted successfully.' : 'Purchase Voucher posted successfully.');
    }

    public function ledgerIndex(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id;
        $this->ensureDefaultAccounts($systemId);

        $accounts = Account::where('system_id', $systemId)->where('is_active', true)->get();
        $selectedAccount = $request->query('account_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = LedgerEntry::with(['voucher', 'account', 'voucherLine'])->where('system_id', $systemId);

        if ($selectedAccount) {
            $query->where('account_id', $selectedAccount);
        }
        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $entries = $query->orderBy('date', 'asc')->orderBy('id', 'asc')->get();

        $balance = 0.00;
        foreach ($entries as $entry) {
            $type = strtolower($entry->account->type ?? 'asset');
            if (in_array($type, ['asset', 'expense'])) {
                $balance += (float)$entry->debit - (float)$entry->credit;
            } else {
                $balance += (float)$entry->credit - (float)$entry->debit;
            }
            $entry->running_balance = $balance;
        }

        return view('vouchers.ledger_directory', compact('accounts', 'entries', 'selectedAccount', 'startDate', 'endDate'));
    }

    protected function ensureDefaultAccounts($systemId)
    {
        $defaults = [
            [
                'code' => 'BANK-KAR-213',
                'name' => 'Karnataka Bank 213 Account',
                'type' => 'Asset',
            ],
            [
                'code' => 'CASH-HAND',
                'name' => 'Cash-in-Hand',
                'type' => 'Asset',
            ],
            [
                'code' => 'EXP-ADV',
                'name' => 'Advertisement Expense Payable',
                'type' => 'Expense',
            ],
            [
                'code' => 'EXP-SITE',
                'name' => 'Site Expenses',
                'type' => 'Expense',
            ],
            [
                'code' => 'EXP-SAL',
                'name' => 'Salary Payable',
                'type' => 'Expense',
            ],
            [
                'code' => 'INC-SALES',
                'name' => 'Flat Sales Revenue',
                'type' => 'Income',
            ],
        ];

        foreach ($defaults as $item) {
            Account::firstOrCreate(
                ['system_id' => $systemId, 'code' => $item['code']],
                ['name' => $item['name'], 'type' => $item['type'], 'is_active' => true]
            );
        }

        // Seed corporate banks if empty
        if (\App\Models\Bank::count() === 0) {
            \App\Models\Bank::create(['bank_name' => 'Federal', 'ifsc_code' => 'FB53767668676', 'status' => 'active']);
            \App\Models\Bank::create(['bank_name' => 'ICICI Bank', 'ifsc_code' => 'ICICI2333444', 'status' => 'active']);
            \App\Models\Bank::create(['bank_name' => 'SBI', 'ifsc_code' => 'SBIN0001234', 'status' => 'active']);
        }

        // Synchronize active corporate banks as asset ledger accounts
        $corporateBanks = \App\Models\Bank::where('status', 'active')->get();
        foreach ($corporateBanks as $cb) {
            $code = 'BANK-' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $cb->bank_name), 0, 8)) . '-' . $cb->id;
            Account::firstOrCreate(
                ['system_id' => $systemId, 'code' => $code],
                ['name' => $cb->bank_name . ' Account', 'type' => 'Asset', 'is_active' => true]
            );
        }
    }

    // ─── CASH BOOK ────────────────────────────────────────────────────────────
    public function cashBook(Request $request)
    {
        $user      = Auth::user();
        $systemId  = $user->system_id;
        $this->ensureDefaultAccounts($systemId);

        // Resolve the Cash-in-Hand account
        $cashAccount = Account::where('system_id', $systemId)
            ->where('code', 'CASH-HAND')
            ->first();

        $startDate = $request->query('start_date');
        $endDate   = $request->query('end_date');

        $query = LedgerEntry::with(['voucher', 'account', 'voucherLine'])
            ->where('system_id', $systemId)
            ->when($cashAccount, fn($q) => $q->where('account_id', $cashAccount->id))
            ->when($startDate,  fn($q) => $q->where('date', '>=', $startDate))
            ->when($endDate,    fn($q) => $q->where('date', '<=', $endDate));

        $entries = $query->orderBy('date', 'asc')->orderBy('id', 'asc')->get();

        $balance = 0.00;
        $totalDebit  = 0.00;
        $totalCredit = 0.00;
        foreach ($entries as $entry) {
            $totalDebit  += (float)$entry->debit;
            $totalCredit += (float)$entry->credit;
            $balance     += (float)$entry->debit - (float)$entry->credit;
            $entry->running_balance = $balance;
        }

        return view('vouchers.cash_book', compact(
            'entries', 'cashAccount', 'startDate', 'endDate',
            'totalDebit', 'totalCredit', 'balance'
        ));
    }

    // ─── BANK BOOK ────────────────────────────────────────────────────────────
    public function bankBook(Request $request)
    {
        $user     = Auth::user();
        $systemId = $user->system_id;
        $this->ensureDefaultAccounts($systemId);

        // All bank-type asset accounts (code starts with BANK-)
        $bankAccounts = Account::where('system_id', $systemId)
            ->where('is_active', true)
            ->where('type', 'Asset')
            ->where('code', 'like', 'BANK-%')
            ->get();

        $selectedBankId = $request->query('bank_account_id', optional($bankAccounts->first())->id);
        $selectedBank   = $bankAccounts->firstWhere('id', $selectedBankId);
        $startDate      = $request->query('start_date');
        $endDate        = $request->query('end_date');

        $query = LedgerEntry::with(['voucher', 'account', 'voucherLine.account'])
            ->where('system_id', $systemId)
            ->when($selectedBankId, fn($q) => $q->where('account_id', $selectedBankId))
            ->when($startDate,      fn($q) => $q->where('date', '>=', $startDate))
            ->when($endDate,        fn($q) => $q->where('date', '<=', $endDate));

        $entries = $query->orderBy('date', 'asc')->orderBy('id', 'asc')->get();

        $balance     = 0.00;
        $totalDebit  = 0.00;
        $totalCredit = 0.00;
        foreach ($entries as $entry) {
            $totalDebit  += (float)$entry->debit;
            $totalCredit += (float)$entry->credit;
            $balance     += (float)$entry->debit - (float)$entry->credit;
            $entry->running_balance = $balance;

            // Attach all sibling lines from the same voucher for expandable breakdown
            $entry->siblingLines = $entry->voucher
                ? $entry->voucher->lines()->with('account')->get()
                : collect();
        }

        return view('vouchers.bank_book', compact(
            'entries', 'bankAccounts', 'selectedBank', 'selectedBankId',
            'startDate', 'endDate', 'totalDebit', 'totalCredit', 'balance'
        ));
    }

    // ─── ENTITY SUB-LEDGER ────────────────────────────────────────────────────
    public function entityLedger(Request $request)
    {
        $user     = Auth::user();
        $systemId = $user->system_id;
        $this->ensureDefaultAccounts($systemId);

        $accounts       = Account::where('system_id', $systemId)->where('is_active', true)->get();
        $selectedId     = $request->query('account_id');
        $selectedAccount = $selectedId ? $accounts->firstWhere('id', $selectedId) : null;
        $startDate      = $request->query('start_date');
        $endDate        = $request->query('end_date');

        $entries = collect();
        $balance = 0.00;
        $totalDebit  = 0.00;
        $totalCredit = 0.00;

        if ($selectedId) {
            $query = LedgerEntry::with(['voucher', 'account', 'voucherLine'])
                ->where('system_id', $systemId)
                ->where('account_id', $selectedId)
                ->when($startDate, fn($q) => $q->where('date', '>=', $startDate))
                ->when($endDate,   fn($q) => $q->where('date', '<=', $endDate));

            $entries = $query->orderBy('date', 'asc')->orderBy('id', 'asc')->get();

            foreach ($entries as $entry) {
                $totalDebit  += (float)$entry->debit;
                $totalCredit += (float)$entry->credit;
                $type = strtolower($entry->account->type ?? 'asset');
                if (in_array($type, ['asset', 'expense'])) {
                    $balance += (float)$entry->debit - (float)$entry->credit;
                } else {
                    $balance += (float)$entry->credit - (float)$entry->debit;
                }
                $entry->running_balance = $balance;
            }
        }

        return view('vouchers.entity_ledger', compact(
            'accounts', 'selectedAccount', 'selectedId',
            'entries', 'startDate', 'endDate',
            'totalDebit', 'totalCredit', 'balance'
        ));
    }
}
