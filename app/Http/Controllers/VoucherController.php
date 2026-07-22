<?php
 
declare(strict_types=1);
 
namespace App\Http\Controllers;
 
use App\Models\Account;
use App\Models\Customer;
use App\Models\Receipt;
use App\Models\Voucher;
use App\Models\VoucherLine;
use App\Models\LedgerEntry;
use App\Models\Project;
use App\Models\Payee;
use App\Models\Sale;
use App\Models\PartnerAllocation;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
 
class VoucherController extends Controller
{
    public function approvalsIndex()
    {
        $systemId = auth()->user()->system_id ?? 1;
        
        $pendingVouchers = Voucher::with(['lines.account', 'creator'])
            ->where('system_id', $systemId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('vouchers.approvals', compact('pendingVouchers'));
    }
    
    public function approveVoucher($id)
    {
        $systemId = auth()->user()->system_id ?? 1;
        $voucher = Voucher::with('lines')->where('system_id', $systemId)->findOrFail($id);
        
        if ($voucher->status === 'pending') {
            DB::transaction(function () use ($voucher, $systemId) {
                $voucher->update(['status' => 'approved']);
                
                foreach ($voucher->lines as $line) {
                    LedgerEntry::create([
                        'system_id' => $systemId,
                        'account_id' => $line->account_id,
                        'voucher_id' => $voucher->id,
                        'voucher_line_id' => $line->id,
                        'date' => $voucher->date,
                        'debit' => $line->debit,
                        'credit' => $line->credit,
                        'running_balance' => 0.00,
                    ]);
                }
            });
            return redirect()->route('vouchers.approvals')->with('status', "Voucher {$voucher->voucher_number} approved successfully.");
        }
        
        return redirect()->route('vouchers.approvals')->with('error', 'Voucher is already approved or rejected.');
    }
    public function fetchSourceDetails(Request $request)
    {
        $type = $request->query('source_type');
        $id = $request->query('source_id');
        $systemId = auth()->user()->system_id ?? 1;

        if (!$type || !$id) {
            return response()->json(['error' => 'Missing source parameters'], 400);
        }

        if ($type === 'bill') {
            $bill = DB::table('bills')->where('id', $id)->first();
            if (!$bill) return response()->json(['error' => 'Bill not found'], 404);
            
            // Debit: Accounts Payable (Liability)
            $debitAcc = Account::where('system_id', $systemId)
                               ->where('type', 'Liability')
                               ->where(function($q) {
                                   $q->where('name', 'LIKE', '%Payable%')
                                     ->orWhere('name', 'LIKE', '%Supplier%')
                                     ->orWhere('name', 'LIKE', '%Vendor%');
                               })
                               ->first();
            if (!$debitAcc) {
                $debitAcc = Account::where('system_id', $systemId)->where('type', 'Liability')->first();
            }
            
            return response()->json([
                'amount' => (float)($bill->final_amount ?? 0) - (float)($bill->paid_amount ?? 0),
                'narration' => "Payment against Vendor Bill #{$bill->bill_number}",
                'debit_account_id' => $debitAcc ? $debitAcc->id : null,
                'credit_account_id' => null, // Let user select bank
                'payee_id' => $bill->payee_id ?? null,
            ]);
        }
        
        if ($type === 'brokerage') {
            $brokerage = \App\Models\Brokerage::find($id);
            if (!$brokerage) return response()->json(['error' => 'Brokerage not found'], 404);
            
            $debitAcc = Account::where('system_id', $systemId)->where('type', 'Expense')->where('name', 'LIKE', '%Broker%')->first();
            if (!$debitAcc) {
                $debitAcc = Account::where('system_id', $systemId)->where('type', 'Expense')->first();
            }
            
            return response()->json([
                'amount' => $brokerage->commission_amount - ($brokerage->paid_amount ?? 0),
                'narration' => "Commission payout for Brokerage #{$brokerage->id}",
                'debit_account_id' => $debitAcc ? $debitAcc->id : null,
                'credit_account_id' => null,
                'payee_id' => $brokerage->broker_id ?? null,
            ]);
        }
        
        if ($type === 'emi') {
            $installment = \App\Models\CustomerInstallment::with('sale.customer')->find($id);
            if (!$installment) return response()->json(['error' => 'EMI not found'], 404);
            
            // Debit: Bank/Cash (Asset), Credit: Customer Receivable (Liability)
            $customerAcc = Account::firstOrCreate(
                ['system_id' => $systemId, 'code' => 'CUST-REC-' . $installment->sale->customer_id],
                ['name' => $installment->sale->customer->name . ' (Receivable)', 'type' => 'Liability', 'is_active' => true]
            );
            $debitAcc = Account::where('system_id', $systemId)->where('type', 'Asset')->first();
            
            return response()->json([
                'amount' => $installment->amount - ($installment->paid_amount ?? 0),
                'narration' => "EMI Collection for Sale #{$installment->sale->sale_number} - Installment {$installment->installment_no}",
                'debit_account_id' => $debitAcc ? $debitAcc->id : null,
                'credit_account_id' => $customerAcc->id,
            ]);
        }
        
        if ($type === 'loan') {
            $loan = \App\Models\Loan::find($id);
            if (!$loan) return response()->json(['error' => 'Loan not found'], 404);
            
            // Debit: Loan Payable (Liability), Credit: Bank (Asset)
            $loanAcc = Account::where('system_id', $systemId)->where('id', $loan->ledger_account_id)->first();
            $creditAcc = Account::where('system_id', $systemId)->where('type', 'Asset')->first();
            
            return response()->json([
                'amount' => $loan->base_emi,
                'narration' => "Bank Loan EMI Payment for {$loan->lender_name}",
                'debit_account_id' => $loanAcc ? $loanAcc->id : null,
                'credit_account_id' => $creditAcc ? $creditAcc->id : null,
            ]);
        }

        return response()->json(['error' => 'Invalid source type'], 400);
    }

    public function createReceipt()
    {
        $user = Auth::user();
        $systemId = $user->system_id;
        $this->ensureDefaultAccounts($systemId);
 
        $accounts = Account::where('system_id', $systemId)->where('is_active', true)->get();
        
        $assetAccounts = $accounts->filter(fn($acc) => strtolower($acc->type) === 'asset');
        
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
        $currentYear = date('Y');
        $lastVoucher = Voucher::where('system_id', $systemId)
            ->where('type', 'Receipt')
            ->where('voucher_number', 'LIKE', "RC-{$currentYear}-%")
            ->where('voucher_number', 'NOT LIKE', '%.%')
            ->where('voucher_number', 'NOT LIKE', '%E%')
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNum = 1;
        if ($lastVoucher) {
            $parts = explode('-', $lastVoucher->voucher_number);
            $lastSegment = end($parts);
            if (is_numeric($lastSegment)) {
                $nextNum = (int)$lastSegment + 1;
            }
        }
        $voucherNumber = 'RC-' . $currentYear . '-' . str_pad((string)$nextNum, 5, '0', STR_PAD_LEFT);
        $projects = Project::all();
 
        // 1. Fetch Partners
        $partners = Payee::where('system_id', $systemId)
            ->where('type', 'Partner')
            ->orderBy('name')
            ->get();
 
        // 2. Fetch Pending Supplier Bills
        $pendingBills = DB::table('bills')
            ->join('payees', 'bills.payee_id', '=', 'payees.id')
            ->where('bills.system_id', $systemId)
            ->whereIn('bills.status', ['approved_unpaid', 'partially_paid'])
            ->select('bills.id', 'bills.bill_number', 'bills.final_amount', 'payees.name as supplier_name')
            ->orderBy('bills.bill_number')
            ->get();
 
        // 3. Fetch Cancelled Sales with refund payable
        $cancelledSales = Sale::whereIn('project_id', Project::where('system_id', $systemId)->pluck('id'))
            ->where('status', 'cancelled')
            ->with(['customer', 'unit'])
            ->get()
            ->map(function ($sale) {
                $totalPaid = $sale->receipts()->sum('amount');
                $refundPayable = max(0.00, $totalPaid - (float)($sale->cancellation_fee ?? 0.00));
                $remainingRefund = max(0.00, $refundPayable - (float)($sale->refund_amount ?? 0.00));
                $sale->remaining_refund = $remainingRefund;
                return $sale;
            })
            ->filter(fn($sale) => $sale->remaining_refund > 0)
            ->values();

        // Load recent receipts (source transactions) — unallocated ones show first
        $allocatedReceiptIds = DB::table('vouchers')
            ->where('type', 'Receipt')
            ->whereNotNull('reference_no')
            ->get('reference_no')
            ->map(fn($v) => json_decode($v->reference_no, true)['source_receipt_id'] ?? null)
            ->filter()
            ->values();

        $customerAccountMap = $customers->pluck('ledger_account_id', 'id')->toArray();
        $cashAccountId = Account::where('system_id', $systemId)->where('code', 'CASH-HAND')->value('id');
        $bankAccountId = Account::where('system_id', $systemId)->where('code', 'BANK-KAR-213')->value('id');

        $recentReceipts = Receipt::with(['customer', 'sale.project', 'sale.unit'])
            ->whereNull('partner_id')  // raw intake receipts, not partner-split sub-receipts
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(100)
            ->get()
            ->map(function ($r) use ($allocatedReceiptIds, $customerAccountMap, $cashAccountId, $bankAccountId) {
                $isAllocated = $allocatedReceiptIds->contains($r->id);
                $ref = $r->reference_no ?? 'REC-' . str_pad((string)$r->id, 5, '0', STR_PAD_LEFT);
                $statusStr = $isAllocated ? '🟢 Allocated' : '🔴 Unallocated';
                return [
                    'id'                         => $r->id,
                    'label'                      => ($ref) . ' — ' . ($r->customer?->name ?? 'Unknown') . ' — ₹' . number_format((float)$r->amount, 2) . ' [' . $statusStr . ']',
                    'ref'                        => $ref,
                    'amount'                     => (float)$r->amount,
                    'date'                       => $r->receipt_date?->format('Y-m-d'),
                    'customer_name'              => $r->customer?->name ?? '—',
                    'customer_id'                => $r->customer_id,
                    'customer_ledger_account_id' => $customerAccountMap[$r->customer_id] ?? null,
                    'payment_mode'               => $r->payment_mode,
                    'project_id'                 => $r->project_id,
                    'project_name'               => $r->sale?->project?->name ?? '—',
                    'unit_id'                    => $r->unit_id,
                    'unit_name'                  => $r->sale?->unit?->door_no ?? '—',
                    'sale_number'                => $r->sale?->sale_number ?? '—',
                    'is_allocated'               => $isAllocated,
                    'resolved_destination_account_id' => (strtolower($r->payment_mode ?? '') === 'cash') ? $cashAccountId : $bankAccountId,
                ];
            });
 
        return view('vouchers.receipt', compact(
            'assetAccounts', 'creditAccounts', 'customers', 'voucherNumber', 
            'projects', 'partners', 'pendingBills', 'cancelledSales', 'recentReceipts'
        ));
    }
 
    public function getProjectUnits(int $projectId)
    {
        $units = Unit::where('project_id', $projectId)
            ->where('is_active', true)
            ->orderBy('door_no')
            ->get(['id', 'door_no', 'status']);
        return response()->json($units);
    }

    public function receiptTargets(Request $request)
    {
        $user     = Auth::user();
        $systemId = $user->system_id;
        $projectId = $request->project_id;

        // Fetch all partners directly from the payees table and format with account code
        $partners = Payee::where('system_id', $systemId)
            ->where('type', 'Partner')
            ->with(['linkedAccount:id,code'])
            ->orderBy('name')
            ->get()
            ->map(function ($p) {
                $code = $p->linkedAccount->code ?? 'N/A';
                return [
                    'id'   => $p->id,
                    'name' => $p->name . " (A/C: " . $code . ")",
                ];
            });

        $prefix = DB::getTablePrefix();
        $pendingBills = DB::table('bills')
            ->join('payees', 'bills.payee_id', '=', 'payees.id')
            ->where('bills.system_id', $systemId)
            ->whereIn('bills.status', ['approved_unpaid', 'partially_paid'])
            ->when($projectId, fn($q) => $q->where('bills.project_id', $projectId))
            ->select(
                'bills.id',
                'bills.bill_number',
                'bills.final_amount',
                'payees.name as supplier_name',
                DB::raw("{$prefix}bills.final_amount - COALESCE((SELECT SUM(bp.amount) FROM {$prefix}bill_payments bp WHERE bp.bill_id = {$prefix}bills.id), 0) as balance")
            )
            ->orderBy('bills.bill_number')
            ->get();

        // Filter cancelled sales by the selected project
        $cancelledSales = Sale::where('status', 'cancelled')
            ->when($projectId, fn($q) => $q->where('project_id', $projectId))
            ->with(['customer', 'unit'])
            ->get()
            ->map(function ($sale) {
                $totalPaid       = $sale->receipts()->sum('amount');
                $refundPayable   = max(0.00, $totalPaid - (float)($sale->cancellation_fee ?? 0.00));
                $remainingRefund = max(0.00, $refundPayable - (float)($sale->refund_amount ?? 0.00));
                return [
                    'id'             => $sale->id,
                    'label'          => ($sale->customer->name ?? 'N/A') . ' — ' . $sale->sale_number . ' (Bal: ₹' . number_format($remainingRefund, 2) . ')',
                    'remaining'      => $remainingRefund,
                ];
            })
            ->filter(fn($s) => $s['remaining'] > 0)
            ->values();

        $defaultShares = [];
        if ($projectId) {
            $defaultShares = DB::table('partner_shares')
                ->where('system_id', $systemId)
                ->where('project_id', $projectId)
                ->get(['partner_id', 'share_pct'])
                ->map(fn($sh) => [
                    'partner_id' => $sh->partner_id,
                    'share_pct'  => (float)$sh->share_pct,
                ]);
        }

        return response()->json([
            'partners'        => $partners,
            'pending_bills'   => $pendingBills,
            'cancelled_sales' => $cancelledSales,
            'default_shares'  => $defaultShares,
        ]);
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
            
            // Split details
            'split_active' => 'nullable',
            'allocations' => 'nullable|string',
            'source_receipt_id' => 'nullable|integer',
        ]);
 
        $voucher = DB::transaction(function () use ($request, $systemId, $user) {
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
                'split_active' => (bool)$request->split_active,
                'source_receipt_id' => $request->source_receipt_id ? (int)$request->source_receipt_id : null,
                'allocations' => $request->allocations ? json_decode($request->allocations, true) : [],
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
                    'line_narration' => 'Output CGST ' . ($gstPct / 2) . '%',
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
                    'line_narration' => 'Output SGST ' . ($gstPct / 2) . '%',
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
 
            // 4. Process split if active
            if ($request->split_active && $request->filled('allocations')) {
                $allocations = json_decode($request->input('allocations'), true) ?? [];
                
                $sumAllocations = 0.0;
                foreach ($allocations as $alloc) {
                    $sumAllocations += (float)($alloc['amount'] ?? 0.0);
                }
 
                // Check balance
                if (abs($sumAllocations - $totalAmount) > 0.01) {
                    throw new \Exception('Total allocated amount (₹' . number_format($sumAllocations, 2) . ') does not match the total receipt amount (₹' . number_format($totalAmount, 2) . ').');
                }
 
                foreach ($allocations as $alloc) {
                    $amount = (float)($alloc['amount'] ?? 0.0);
                    if ($amount <= 0) continue;
 
                    $type = $alloc['type'] ?? '';
                    $targetId = $alloc['target_id'] ?? null;
                    $remarks = $alloc['remarks'] ?? '';
 
                    if ($type === 'partner') {
                        $partner = Payee::where('type', 'Partner')->findOrFail($targetId);
 
                        // Insert partner allocation tracking record
                        PartnerAllocation::create([
                            'system_id' => $systemId,
                            'partner_id' => $partner->id,
                            'project_id' => $request->project_id ? (int)$request->project_id : 1,
                            'allocated_amount' => $amount,
                            'date' => $request->date,
                            'voucher_id' => $voucher->id,
                            'payment_id' => null,
                        ]);
 
                        // Ledger entries: Debit Partner's Account and Credit Cash/Bank
                        $partnerLine = VoucherLine::create([
                            'voucher_id' => $voucher->id,
                            'account_id' => $partner->linked_account_id,
                            'debit' => $amount,
                            'credit' => 0.00,
                            'line_narration' => 'Partner payout share drawings: ' . $partner->name . ($remarks ? ' (' . $remarks . ')' : ''),
                        ]);
 
                        LedgerEntry::create([
                            'system_id' => $systemId,
                            'account_id' => $partner->linked_account_id,
                            'voucher_id' => $voucher->id,
                            'voucher_line_id' => $partnerLine->id,
                            'date' => $request->date,
                            'debit' => $amount,
                            'credit' => 0.00,
                            'running_balance' => 0.00,
                        ]);
 
                        $bankCreditLine = VoucherLine::create([
                            'voucher_id' => $voucher->id,
                            'account_id' => $request->destination_account_id,
                            'debit' => 0.00,
                            'credit' => $amount,
                            'line_narration' => 'Credit bank for Partner share drawings allocation',
                        ]);
 
                        LedgerEntry::create([
                            'system_id' => $systemId,
                            'account_id' => $request->destination_account_id,
                            'voucher_id' => $voucher->id,
                            'voucher_line_id' => $bankCreditLine->id,
                            'date' => $request->date,
                            'debit' => 0.00,
                            'credit' => $amount,
                            'running_balance' => 0.00,
                        ]);
                    }
                    elseif ($type === 'supplier') {
                        $bill = DB::table('bills')->where('id', $targetId)->first();
                        if (!$bill) {
                            throw new \Exception('Selected bill not found.');
                        }
 
                        $supplierPayee = Payee::findOrFail($bill->payee_id);
 
                        // Insert bill payment record
                        DB::table('bill_payments')->insert([
                            'system_id' => $systemId,
                            'bill_id' => $bill->id,
                            'payee_id' => $bill->payee_id,
                            'amount' => $amount,
                            'date' => $request->date,
                            'voucher_id' => $voucher->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
 
                        // Calculate total paid on this bill so far
                        $totalBillPaid = DB::table('bill_payments')->where('bill_id', $bill->id)->sum('amount');
                        $newStatus = ($totalBillPaid >= $bill->final_amount) ? 'paid' : 'partially_paid';
 
                        DB::table('bills')->where('id', $bill->id)->update([
                            'status' => $newStatus,
                            'updated_at' => now(),
                        ]);
 
                        // Ledger entries: Debit Supplier's Account (reducing liability) and Credit Cash/Bank
                        $supplierLine = VoucherLine::create([
                            'voucher_id' => $voucher->id,
                            'account_id' => $supplierPayee->linked_account_id,
                            'debit' => $amount,
                            'credit' => 0.00,
                            'line_narration' => 'Debit Supplier ledger for bill #' . $bill->bill_number . ($remarks ? ' (' . $remarks . ')' : ''),
                        ]);
 
                        LedgerEntry::create([
                            'system_id' => $systemId,
                            'account_id' => $supplierPayee->linked_account_id,
                            'voucher_id' => $voucher->id,
                            'voucher_line_id' => $supplierLine->id,
                            'date' => $request->date,
                            'debit' => $amount,
                            'credit' => 0.00,
                            'running_balance' => 0.00,
                        ]);
 
                        $bankCreditLine = VoucherLine::create([
                            'voucher_id' => $voucher->id,
                            'account_id' => $request->destination_account_id,
                            'debit' => 0.00,
                            'credit' => $amount,
                            'line_narration' => 'Credit bank for supplier bill payment',
                        ]);
 
                        LedgerEntry::create([
                            'system_id' => $systemId,
                            'account_id' => $request->destination_account_id,
                            'voucher_id' => $voucher->id,
                            'voucher_line_id' => $bankCreditLine->id,
                            'date' => $request->date,
                            'debit' => 0.00,
                            'credit' => $amount,
                            'running_balance' => 0.00,
                        ]);
                    }
                    elseif ($type === 'refund') {
                        $sale = Sale::findOrFail($targetId);
                        $sale->increment('refund_amount', $amount);
 
                        $customerAccCode = 'CUST-REC-' . $sale->customer_id;
                        $customerAcc = Account::where('system_id', $systemId)->where('code', $customerAccCode)->first();
 
                        if ($customerAcc) {
                            // Ledger entries: Debit Customer's Account (reducing credit/receivable) and Credit Cash/Bank
                            $custRefundLine = VoucherLine::create([
                                'voucher_id' => $voucher->id,
                                'account_id' => $customerAcc->id,
                                'debit' => $amount,
                                'credit' => 0.00,
                                'line_narration' => 'Debit customer ledger for cancellation refund on unit ' . ($sale->unit?->door_no ?? '') . ($remarks ? ' (' . $remarks . ')' : ''),
                            ]);
 
                            LedgerEntry::create([
                                'system_id' => $systemId,
                                'account_id' => $customerAcc->id,
                                'voucher_id' => $voucher->id,
                                'voucher_line_id' => $custRefundLine->id,
                                'date' => $request->date,
                                'debit' => $amount,
                                'credit' => 0.00,
                                'running_balance' => 0.00,
                            ]);
 
                            $bankCreditLine = VoucherLine::create([
                                'voucher_id' => $voucher->id,
                                'account_id' => $request->destination_account_id,
                                'debit' => 0.00,
                                'credit' => $amount,
                                'line_narration' => 'Credit bank for customer cancellation refund',
                            ]);
 
                            LedgerEntry::create([
                                'system_id' => $systemId,
                                'account_id' => $request->destination_account_id,
                                'voucher_id' => $voucher->id,
                                'voucher_line_id' => $bankCreditLine->id,
                                'date' => $request->date,
                                'debit' => 0.00,
                                'credit' => $amount,
                                'running_balance' => 0.00,
                            ]);
                        }
                    }
                    elseif ($type === 'general') {
                        // Transfer to general fund (Debit General Fund Account, Credit Bank)
                        $gfAccount = Account::findOrFail($targetId);
 
                        $gfLine = VoucherLine::create([
                            'voucher_id' => $voucher->id,
                            'account_id' => $gfAccount->id,
                            'debit' => $amount,
                            'credit' => 0.00,
                            'line_narration' => 'Transfer to General Fund: ' . $gfAccount->name . ($remarks ? ' (' . $remarks . ')' : ''),
                        ]);
 
                        LedgerEntry::create([
                            'system_id' => $systemId,
                            'account_id' => $gfAccount->id,
                            'voucher_id' => $voucher->id,
                            'voucher_line_id' => $gfLine->id,
                            'date' => $request->date,
                            'debit' => $amount,
                            'credit' => 0.00,
                            'running_balance' => 0.00,
                        ]);
 
                        $bankCreditLine = VoucherLine::create([
                            'voucher_id' => $voucher->id,
                            'account_id' => $request->destination_account_id,
                            'debit' => 0.00,
                            'credit' => $amount,
                            'line_narration' => 'Credit bank for general fund transfer',
                        ]);
 
                        LedgerEntry::create([
                            'system_id' => $systemId,
                            'account_id' => $request->destination_account_id,
                            'voucher_id' => $voucher->id,
                            'voucher_line_id' => $bankCreditLine->id,
                            'date' => $request->date,
                            'debit' => 0.00,
                            'credit' => $amount,
                            'running_balance' => 0.00,
                        ]);
                    }
                }
            }
            return $voucher;
        });
 
        return redirect()->route('vouchers.receipt.posted', ['id' => $voucher->id]);
    }
 
    public function receiptPosted(int $id)
    {
        $user     = Auth::user();
        $systemId = $user->system_id;

        $voucher = Voucher::where('system_id', $systemId)
            ->where('type', 'Receipt')
            ->with(['lines' => function ($q) {
                $q->with('account');
            }])
            ->findOrFail($id);

        $meta = json_decode($voucher->reference_no ?? '{}', true) ?? [];

        $splitRows = $voucher->lines->map(function ($line) {
            return [
                'narration' => $line->line_narration,
                'debit'     => (float)$line->debit,
                'credit'    => (float)$line->credit,
                'account'   => $line->account?->name ?? 'N/A',
            ];
        });

        $totalIn  = $splitRows->sum('debit');
        $totalOut = $splitRows->sum('credit');

        return view('vouchers.receipt_posted', compact('voucher', 'meta', 'splitRows', 'totalIn', 'totalOut'));
    }

    public function createPayment()
    {
        $user = Auth::user();
        $systemId = $user->system_id;
        $this->ensureDefaultAccounts($systemId);

        $accounts = Account::where('system_id', $systemId)->where('is_active', true)->get();
        
        $debitAccounts = $accounts->filter(fn($acc) => in_array(strtolower($acc->type), ['expense', 'liability', 'asset']));
        $creditAccounts = $accounts->filter(fn($acc) => strtolower($acc->type) === 'asset' && $acc->code !== 'BANK-KAR-213');

        // Generate voucher number
        $currentYear = date('Y');
        $lastVoucher = Voucher::where('system_id', $systemId)
            ->where('type', 'Payment')
            ->where('voucher_number', 'LIKE', "PV-{$currentYear}-%")
            ->where('voucher_number', 'NOT LIKE', '%.%')
            ->where('voucher_number', 'NOT LIKE', '%E%')
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNum = 1;
        if ($lastVoucher) {
            $parts = explode('-', $lastVoucher->voucher_number);
            $lastSegment = end($parts);
            if (is_numeric($lastSegment)) {
                $nextNum = (int)$lastSegment + 1;
            }
        }
        $voucherNumber = 'PV-' . $currentYear . '-' . str_pad((string)$nextNum, 5, '0', STR_PAD_LEFT);
        $payees = Payee::all();
        $projects = Project::where('system_id', $systemId)->get();
        if ($projects->isEmpty()) {
            $projects = Project::all();
        }
        
        $pendingBills = DB::table('bills')
            ->join('payees', 'bills.payee_id', '=', 'payees.id')
            ->where('bills.system_id', $systemId)
            ->whereIn('bills.status', ['approved_unpaid', 'partially_paid'])
            ->select('bills.id', 'bills.bill_number', 'bills.final_amount', 'payees.name as supplier_name')
            ->orderBy('bills.bill_number')
            ->get();
            
        $pendingLoans = \App\Models\Loan::where('system_id', $systemId)
            ->where('status', 'active')
            ->get();
            
        $pendingBrokerages = \App\Models\Brokerage::with('broker')
            ->where('status', 'unpaid')
            ->get();

        return view('vouchers.payment', compact('projects', 'debitAccounts', 'creditAccounts', 'voucherNumber', 'payees', 'pendingBills', 'pendingLoans', 'pendingBrokerages'));
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
            
            // -----------------------------------------------------------------
            // APPROVAL WORKFLOW: High-Value Vouchers
            // -----------------------------------------------------------------
            $requiresApproval = $netPaid >= 50000;
            $status = $requiresApproval ? 'pending' : 'approved';

            // Create Voucher
            $voucher = Voucher::create([
                'system_id' => $systemId,
                'voucher_number' => $request->voucher_number,
                'type' => 'Payment',
                'date' => $request->date,
                'narration' => $request->narration,
                'created_by' => $user->id,
                'status' => $status,
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

            if (!$requiresApproval) {
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
            }

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
                if (!$requiresApproval) {
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
            }

            // 4. Credit the Source Account (Bank/Cash) (Net Paid Amount)
            $creditLine = VoucherLine::create([
                'voucher_id' => $voucher->id,
                'account_id' => $request->credit_account_id,
                'debit' => 0.00,
                'credit' => $netPaid,
                'line_narration' => 'Credit Bank/Cash',
            ]);

            if (!$requiresApproval) {
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
            }
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
        $currentYear = date('Y');
        $lastVoucher = Voucher::where('system_id', $systemId)
            ->where('type', 'Contra')
            ->where('voucher_number', 'LIKE', "CN-{$currentYear}-%")
            ->where('voucher_number', 'NOT LIKE', '%.%')
            ->where('voucher_number', 'NOT LIKE', '%E%')
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNum = 1;
        if ($lastVoucher) {
            $parts = explode('-', $lastVoucher->voucher_number);
            $lastSegment = end($parts);
            if (is_numeric($lastSegment)) {
                $nextNum = (int)$lastSegment + 1;
            }
        }
        $voucherNumber = 'CN-' . $currentYear . '-' . str_pad((string)$nextNum, 5, '0', STR_PAD_LEFT);

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
            
            // High-Value Approval Threshold
            $requiresApproval = $amount >= 50000;
            $status = $requiresApproval ? 'pending' : 'Posted';

            // Create Voucher
            $voucher = Voucher::create([
                'system_id' => $systemId,
                'voucher_number' => $request->voucher_number,
                'type' => 'Contra',
                'date' => $request->date,
                'narration' => $request->narration,
                'created_by' => $user->id,
                'status' => $status,
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

            // 2. Credit the Source Account
            $creditLine = VoucherLine::create([
                'voucher_id' => $voucher->id,
                'account_id' => $request->credit_account_id,
                'debit' => 0.00,
                'credit' => $amount,
                'line_narration' => 'Credit to Source Account',
            ]);

            if (!$requiresApproval) {
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
            }
        });

        return redirect()->route('vouchers.ledger.index')->with('status', 'Contra Voucher created successfully. Note: Vouchers >= ₹50,000 require approval.');
    }

    public function createJournal()
    {
        $user = Auth::user();
        $systemId = $user->system_id;
        $this->ensureDefaultAccounts($systemId);

        $accounts = Account::where('system_id', $systemId)->where('is_active', true)->get();

        // Generate voucher number
        $currentYear = date('Y');
        $lastVoucher = Voucher::where('system_id', $systemId)
            ->where('type', 'Journal')
            ->where('voucher_number', 'LIKE', "JV-{$currentYear}-%")
            ->where('voucher_number', 'NOT LIKE', '%.%')
            ->where('voucher_number', 'NOT LIKE', '%E%')
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNum = 1;
        if ($lastVoucher) {
            $parts = explode('-', $lastVoucher->voucher_number);
            $lastSegment = end($parts);
            if (is_numeric($lastSegment)) {
                $nextNum = (int)$lastSegment + 1;
            }
        }
        $voucherNumber = 'JV-' . $currentYear . '-' . str_pad((string)$nextNum, 5, '0', STR_PAD_LEFT);

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

            // High-Value Approval Threshold
            $requiresApproval = $totalDebit >= 50000;
            $status = $requiresApproval ? 'pending' : 'Posted';

            // Create Voucher
            $voucher = Voucher::create([
                'system_id' => $systemId,
                'voucher_number' => $request->voucher_number,
                'type' => 'Journal',
                'date' => $request->date,
                'narration' => $request->narration,
                'created_by' => $user->id,
                'status' => $status,
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

                if (!$requiresApproval) {
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
            }
        });

        return redirect()->route('vouchers.ledger.index')->with('status', 'Journal Voucher created successfully. Note: Vouchers >= ₹50,000 require approval.');
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

        // Generate unique sales & purchase voucher numbers
        $currentYear = date('Y');
        
        $getUniqueVoucherNumber = function($prefix) use ($systemId, $currentYear) {
            $maxNum = 0;
            $vouchers = Voucher::where('system_id', $systemId)
                ->where('voucher_number', 'LIKE', "{$prefix}%")
                ->get();

            foreach ($vouchers as $v) {
                $parts = explode('-', $v->voucher_number);
                $last = end($parts);
                if (is_numeric($last) && (int)$last > $maxNum) {
                    $maxNum = (int)$last;
                }
            }
            return $prefix . '-' . $currentYear . '-' . str_pad((string)($maxNum + 1), 5, '0', STR_PAD_LEFT);
        };

        $voucherNumber = $getUniqueVoucherNumber('SL');
        $purchaseVoucherNumber = $getUniqueVoucherNumber('PR');

        return view('vouchers.sales_purchase', compact('accounts', 'customers', 'voucherNumber', 'purchaseVoucherNumber'));
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
            'gst_behavior' => 'nullable|in:inclusive,exclusive',
            'gst_rate' => 'nullable|numeric',
            'narration' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $systemId, $user) {
            $baseAmount = (float)$request->amount;
            $gstBehavior = $request->gst_behavior ?? 'inclusive';
            $gstPct = (float)($request->gst_rate ?? 5.0);
            $type = ucfirst($request->transaction_type);

            $cgst = 0.0;
            $sgst = 0.0;
            $totalAmount = $baseAmount;

            if ($gstBehavior === 'inclusive' && $gstPct > 0) {
                $base = $baseAmount / (1 + ($gstPct / 100.0));
                $cgst = round(($baseAmount - $base) / 2.0, 2);
                $sgst = round(($baseAmount - $base) / 2.0, 2);
                $debitVal = $baseAmount;
                $creditVal = round($base, 2);
            } elseif ($gstBehavior === 'exclusive' && $gstPct > 0) {
                $totalTax = round($baseAmount * ($gstPct / 100.0), 2);
                $cgst = round($totalTax / 2.0, 2);
                $sgst = round($totalTax / 2.0, 2);
                $totalAmount = $baseAmount + $cgst + $sgst;
                $debitVal = $totalAmount;
                $creditVal = $baseAmount;
            } else {
                $debitVal = $baseAmount;
                $creditVal = $baseAmount;
            }

            // High-Value Approval Threshold
            $requiresApproval = $baseAmount >= 50000;
            $status = $requiresApproval ? 'pending' : 'Posted';

            // Auto-resolve duplicate voucher number collisions
            $finalVoucherNumber = $request->voucher_number;
            $exists = Voucher::where('system_id', $systemId)
                ->where('voucher_number', $finalVoucherNumber)
                ->exists();

            if ($exists) {
                $prefix = ($request->transaction_type === 'sales') ? 'SL' : 'PR';
                $currentYear = date('Y');
                $maxNum = 0;
                $existingVouchers = Voucher::where('system_id', $systemId)
                    ->where('voucher_number', 'LIKE', "{$prefix}%")
                    ->get();
                foreach ($existingVouchers as $v) {
                    $parts = explode('-', $v->voucher_number);
                    $last = end($parts);
                    if (is_numeric($last) && (int)$last > $maxNum) {
                        $maxNum = (int)$last;
                    }
                }
                $finalVoucherNumber = $prefix . '-' . $currentYear . '-' . str_pad((string)($maxNum + 1), 5, '0', STR_PAD_LEFT);
            }

            // Create Voucher
            $voucher = Voucher::create([
                'system_id' => $systemId,
                'voucher_number' => $finalVoucherNumber,
                'type' => $type,
                'date' => $request->date,
                'narration' => $request->narration,
                'created_by' => $user->id,
                'status' => $status,
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

            if (!$requiresApproval) {
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
            }

            // 2. Credit Line
            $creditLine = VoucherLine::create([
                'voucher_id' => $voucher->id,
                'account_id' => $request->credit_account_id,
                'debit' => 0.00,
                'credit' => $creditVal,
                'line_narration' => 'Credit Account',
            ]);

            if (!$requiresApproval) {
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
            }

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

                if (!$requiresApproval) {
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
                }

                $sgstLine = VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $sgstAccount->id,
                    'debit' => $sgstDeb,
                    'credit' => $sgstCred,
                    'line_narration' => $taxNamePrefix . ' SGST 2.5%',
                ]);

                if (!$requiresApproval) {
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
            }
        });

        return redirect()->route('vouchers.ledger.index')->with('status', ($request->transaction_type === 'sales' ? 'Sales Invoice' : 'Purchase Voucher') . ' posted successfully. Note: Vouchers >= ₹50,000 require approval.');
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
        $selectedVoucherType = $request->query('voucher_type');

        $prefix = DB::getTablePrefix();
        $ledgerTable = (new LedgerEntry)->getTable();
        $accountTable = (new Account)->getTable();

        $query = LedgerEntry::with(['voucher', 'account', 'voucherLine'])->where("{$ledgerTable}.system_id", $systemId);

        if ($selectedAccount) {
            $query->where("{$ledgerTable}.account_id", $selectedAccount);
        }
        if ($startDate) {
            $query->where("{$ledgerTable}.date", '>=', $startDate);
        }
        if ($endDate) {
            $query->where("{$ledgerTable}.date", '<=', $endDate);
        }
        if ($selectedVoucherType) {
            $query->whereHas('voucher', function($q) use ($selectedVoucherType) {
                $q->where('type', $selectedVoucherType);
            });
        }

        $totalQuery = clone $query;
        $grandTotalDebit = (float)$totalQuery->sum("{$ledgerTable}.debit");
        $grandTotalCredit = (float)$totalQuery->sum("{$ledgerTable}.credit");

        $entries = $query->orderBy("{$ledgerTable}.date", 'asc')->orderBy("{$ledgerTable}.id", 'asc')->paginate(50)->withQueryString();

        $balance = 0.00;
        if ($entries->currentPage() > 1) {
            $firstEntry = $entries->first();
            if ($firstEntry) {
                $priorQuery = clone $totalQuery;
                $priorQuery->where(function($q) use ($firstEntry, $ledgerTable) {
                    $q->where("{$ledgerTable}.date", '<', $firstEntry->date)
                      ->orWhere(function($sub) use ($firstEntry, $ledgerTable) {
                          $sub->where("{$ledgerTable}.date", '=', $firstEntry->date)
                              ->where("{$ledgerTable}.id", '<', $firstEntry->id);
                      });
                });

                $priorSums = $priorQuery
                    ->leftJoin($accountTable, "{$ledgerTable}.account_id", '=', "{$accountTable}.id")
                    ->select("{$accountTable}.type", DB::raw("SUM({$prefix}{$ledgerTable}.debit) as total_debit"), DB::raw("SUM({$prefix}{$ledgerTable}.credit) as total_credit"))
                    ->groupBy("{$accountTable}.type")
                    ->get();

                foreach ($priorSums as $sum) {
                    $type = strtolower($sum->type ?? 'asset');
                    if (in_array($type, ['asset', 'expense'])) {
                        $balance += (float)$sum->total_debit - (float)$sum->total_credit;
                    } else {
                        $balance += (float)$sum->total_credit - (float)$sum->total_debit;
                    }
                }
            }
        }

        foreach ($entries as $entry) {
            $type = strtolower($entry->account->type ?? 'asset');
            if (in_array($type, ['asset', 'expense'])) {
                $balance += (float)$entry->debit - (float)$entry->credit;
            } else {
                $balance += (float)$entry->credit - (float)$entry->debit;
            }
            $entry->running_balance = $balance;
        }

        // Fetch real data: latest voucher numbers for the Workflow Guide table
        $latestReceipt = Voucher::where('system_id', $systemId)->where('type', 'Receipt')->latest('id')->value('voucher_number') ?? 'RV-XXXX-XXXX';
        $latestPurchase = Voucher::where('system_id', $systemId)->where('type', 'Purchase')->latest('id')->value('voucher_number') ?? 'PV-XXXX-XXXX';
        $latestPayment = Voucher::where('system_id', $systemId)->where('type', 'Payment')->latest('id')->value('voucher_number') ?? 'PV-XXXX-XXXX';
        $latestJournal = Voucher::where('system_id', $systemId)->where('type', 'Journal')->latest('id')->value('voucher_number') ?? 'JV-XXXX-XXXX';
        
        // Calculate category totals (Total Debit for each voucher type)
        $categoryTotalsQuery = DB::table('voucher_lines')
            ->join('vouchers', 'voucher_lines.voucher_id', '=', 'vouchers.id')
            ->where('vouchers.system_id', $systemId)
            ->where('vouchers.status', '!=', 'pending')
            ->groupBy('vouchers.type')
            ->select('vouchers.type', DB::raw('SUM(debit) as total'))
            ->get();
            
        $categoryTotals = [];
        foreach ($categoryTotalsQuery as $row) {
            $categoryTotals[$row->type] = $row->total;
        }

        return view('vouchers.ledger_directory', compact(
            'accounts', 'entries', 'selectedAccount', 'startDate', 'endDate', 'selectedVoucherType', 'categoryTotals',
            'latestReceipt', 'latestPurchase', 'latestPayment', 'latestJournal', 'grandTotalDebit', 'grandTotalCredit'
        ));
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

        // Calculate dynamic opening balance for entries before start_date
        $openingBalance = 0.00;
        if ($startDate && $cashAccount) {
            $prevDebit = (float)LedgerEntry::where('system_id', $systemId)
                ->where('account_id', $cashAccount->id)
                ->where('date', '<', $startDate)
                ->sum('debit');

            $prevCredit = (float)LedgerEntry::where('system_id', $systemId)
                ->where('account_id', $cashAccount->id)
                ->where('date', '<', $startDate)
                ->sum('credit');

            $openingBalance = $prevDebit - $prevCredit;
        }

        $query = LedgerEntry::with(['voucher', 'account', 'voucherLine'])
            ->where('system_id', $systemId)
            ->when($cashAccount, fn($q) => $q->where('account_id', $cashAccount->id))
            ->when($startDate,  fn($q) => $q->where('date', '>=', $startDate))
            ->when($endDate,    fn($q) => $q->where('date', '<=', $endDate));

        $entries = $query->orderBy('date', 'asc')->orderBy('id', 'asc')->get();

        $balance = $openingBalance;
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
            'totalDebit', 'totalCredit', 'balance', 'openingBalance'
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

        // Calculate dynamic opening balance for entries before start_date
        $openingBalance = 0.00;
        if ($startDate && $selectedBankId) {
            $prevDebit = (float)LedgerEntry::where('system_id', $systemId)
                ->where('account_id', $selectedBankId)
                ->where('date', '<', $startDate)
                ->sum('debit');

            $prevCredit = (float)LedgerEntry::where('system_id', $systemId)
                ->where('account_id', $selectedBankId)
                ->where('date', '<', $startDate)
                ->sum('credit');

            $openingBalance = $prevDebit - $prevCredit;
        }

        $query = LedgerEntry::with(['voucher', 'account', 'voucherLine.account'])
            ->where('system_id', $systemId)
            ->when($selectedBankId, fn($q) => $q->where('account_id', $selectedBankId))
            ->when($startDate,      fn($q) => $q->where('date', '>=', $startDate))
            ->when($endDate,        fn($q) => $q->where('date', '<=', $endDate));

        $entries = $query->orderBy('date', 'asc')->orderBy('id', 'asc')->get();

        $balance     = $openingBalance;
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
            'startDate', 'endDate', 'totalDebit', 'totalCredit', 'balance', 'openingBalance'
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
        $openingBalance = 0.00;
        $balance = 0.00;
        $totalDebit  = 0.00;
        $totalCredit = 0.00;

        if ($selectedId) {
            // Calculate dynamic opening balance for entries before start_date
            if ($startDate && $selectedAccount) {
                $prevDebit = (float)LedgerEntry::where('system_id', $systemId)
                    ->where('account_id', $selectedId)
                    ->where('date', '<', $startDate)
                    ->sum('debit');

                $prevCredit = (float)LedgerEntry::where('system_id', $systemId)
                    ->where('account_id', $selectedId)
                    ->where('date', '<', $startDate)
                    ->sum('credit');

                $type = strtolower($selectedAccount->type ?? 'asset');
                if (in_array($type, ['asset', 'expense'])) {
                    $openingBalance = $prevDebit - $prevCredit;
                } else {
                    $openingBalance = $prevCredit - $prevDebit;
                }
            }

            $query = LedgerEntry::with(['voucher', 'account', 'voucherLine'])
                ->where('system_id', $systemId)
                ->where('account_id', $selectedId)
                ->when($startDate, fn($q) => $q->where('date', '>=', $startDate))
                ->when($endDate,   fn($q) => $q->where('date', '<=', $endDate));

            $entries = $query->orderBy('date', 'asc')->orderBy('id', 'asc')->get();

            $balance = $openingBalance;
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
            'totalDebit', 'totalCredit', 'balance', 'openingBalance'
        ));
    }
}
