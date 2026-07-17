<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Payee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function createBill()
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        // 1. Fetch Suppliers (type = 'Supplier')
        $suppliers = Payee::where('system_id', $systemId)
            ->where('type', 'Supplier')
            ->orderBy('name')
            ->get()
            ->map(function ($supplier) {
                // Calculate outstanding amount: sum(final_amount) - sum(payments)
                $totalBills = (float)DB::table('bills')
                    ->where('payee_id', $supplier->id)
                    ->sum('final_amount');
                
                $totalPayments = (float)DB::table('bill_payments')
                    ->where('payee_id', $supplier->id)
                    ->sum('amount');
                
                $supplier->outstanding_balance = max(0.00, $totalBills - $totalPayments);
                return $supplier;
            });

        // 2. Fetch Projects
        $projects = Project::all();

        // 3. Generate a system-generated ERP Bill Reference ID matching INV-25-26-7001
        $lastBill = DB::table('bills')->orderBy('id', 'desc')->first();
        $nextId = $lastBill ? ($lastBill->id + 7001) : 7001;
        $year = (int)date('Y');
        $nextYear = ($year + 1) % 100;
        $prevYear = $year % 100;
        $systemBillRef = sprintf("INV-%02d-%02d-%d", $prevYear, $nextYear, $nextId);

        return view('expenses.bills.create', compact('suppliers', 'projects', 'systemBillRef'));
    }

    public function storeBill(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $request->validate([
            'payee_id' => 'required|exists:payees,id',
            'project_id' => 'required|exists:projects,id',
            'bill_number' => 'required|string|max:100',
            'bill_amount' => 'required|numeric|min:0.01',
            'final_amount' => 'required|numeric|min:0.01',
            'bill_date' => 'required|date',
            'bill_type' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:50',
            'place_of_supply' => 'nullable|string|max:100',
            'expense_head' => 'required|string|max:100',
            'bill_file' => 'required|file|mimes:pdf,jpeg,png,jpg|max:10240',
            'allocations' => 'nullable|string',
        ]);

        $billFilePath = null;
        if ($request->hasFile('bill_file')) {
            $billFilePath = $request->file('bill_file')->store('bills', 'public');
        }

        // Decode allocations if present
        $allocations = [];
        if ($request->filled('allocations')) {
            $allocations = json_decode($request->allocations, true);
        }

        if (empty($allocations) || count($allocations) <= 1) {
            // Standard single project bill
            DB::table('bills')->insert([
                'system_id' => $systemId,
                'payee_id' => $request->payee_id,
                'project_id' => $request->project_id,
                'bill_number' => $request->bill_number,
                'bill_type' => $request->bill_type,
                'payment_terms' => $request->payment_terms,
                'place_of_supply' => $request->place_of_supply,
                'expense_head' => $request->expense_head,
                'bill_file' => $billFilePath,
                'bill_amount' => $request->bill_amount,
                'final_amount' => $request->final_amount,
                'status' => 'approved_unpaid', // Immediately available in receipt split target
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // Split the bill across multiple projects
            foreach ($allocations as $idx => $alloc) {
                $pct = (float)($alloc['allocation_pct'] ?? 100) / 100;
                $project = Project::find($alloc['project_id']);
                $projCode = $project ? ($project->code ?: 'PROJ-' . $project->id) : 'P' . ($idx + 1);

                // Calculate proportional amounts
                $billAmount = round((float)$request->bill_amount * $pct, 2);
                $finalAmount = round((float)$request->final_amount * $pct, 2);

                // Append suffix to avoid duplicate bill number violation
                $billNum = $request->bill_number . '/' . $projCode;

                DB::table('bills')->insert([
                    'system_id' => $systemId,
                    'payee_id' => $request->payee_id,
                    'project_id' => $alloc['project_id'],
                    'bill_number' => $billNum,
                    'bill_type' => $request->bill_type,
                    'payment_terms' => $request->payment_terms,
                    'place_of_supply' => $request->place_of_supply,
                    'expense_head' => $request->expense_head,
                    'bill_file' => $billFilePath,
                    'bill_amount' => $billAmount,
                    'final_amount' => $finalAmount,
                    'status' => 'approved_unpaid',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('expenses.bills.index')->with('status', 'Supplier Bill registered and liability created successfully.');
    }

    public function listBills()
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $bills = DB::table('bills')
            ->join('payees', 'bills.payee_id', '=', 'payees.id')
            ->join('projects', 'bills.project_id', '=', 'projects.id')
            ->where('bills.system_id', $systemId)
            ->select('bills.*', 'payees.name as supplier_name', 'projects.name as project_name')
            ->orderBy('bills.id', 'desc')
            ->get();

        return view('expenses.bills.index', compact('bills'));
    }

    public function expenseLedger()
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $payments = DB::table('bill_payments')
            ->join('bills', 'bill_payments.bill_id', '=', 'bills.id')
            ->join('payees', 'bill_payments.payee_id', '=', 'payees.id')
            ->where('bill_payments.system_id', $systemId)
            ->select('bill_payments.*', 'bills.bill_number', 'payees.name as supplier_name')
            ->orderBy('bill_payments.id', 'desc')
            ->get();

        return view('expenses.bills.ledger', compact('payments'));
    }

    public function projectMetrics(Request $request, $projectId)
    {
        $projectId = (int)$projectId;

        // Fetch customer receipts for this project (excluding partner shares receipts where partner_id is not null)
        $prefix = DB::getTablePrefix();
        $receiptsGrouped = DB::table('receipts')
            ->join('customers', 'receipts.customer_id', '=', 'customers.id')
            ->leftJoin('sales', 'receipts.sale_id', '=', 'sales.id')
            ->leftJoin('hindustan_units', 'sales.unit_id', '=', 'hindustan_units.id')
            ->where('receipts.project_id', $projectId)
            ->whereNull('receipts.partner_id')
            ->select(
                'receipts.customer_id',
                'customers.name as customer_name',
                'hindustan_units.door_no',
                DB::raw("SUM({$prefix}receipts.amount) as total_amount")
            )
            ->groupBy('receipts.customer_id', 'customers.name', 'hindustan_units.door_no')
            ->get();

        // Group by customer to consolidate unit names if a customer has multiple units
        $customersData = [];
        $totalReceipts = 0.0;

        foreach ($receiptsGrouped as $row) {
            $totalReceipts += (float)$row->total_amount;
            $customerId = $row->customer_id;

            if (!isset($customersData[$customerId])) {
                $customersData[$customerId] = [
                    'name' => $row->customer_name,
                    'units' => [],
                    'amount' => 0.0,
                ];
            }

            $customersData[$customerId]['amount'] += (float)$row->total_amount;
            if ($row->door_no) {
                $customersData[$customerId]['units'][] = $row->door_no;
            }
        }

        // Fetch outstanding balance from active sales
        $totalOutstanding = (float)DB::table('sales')
            ->where('project_id', $projectId)
            ->where('status', 'active')
            ->sum('remaining_balance');

        $totalValue = $totalReceipts + $totalOutstanding;

        $realizedPct = 0.0;
        $pendingPct = 0.0;
        $customersList = [];

        if ($totalValue > 0) {
            $realizedPct = round(($totalReceipts / $totalValue) * 100, 2);
            $pendingPct = round(($totalOutstanding / $totalValue) * 100, 2);

            foreach ($customersData as $cId => $data) {
                $pct = round(($data['amount'] / $totalValue) * 100, 2);
                $unitsStr = count($data['units']) > 0 ? implode(', ', array_unique($data['units'])) : 'N/A';
                $customersList[] = [
                    'name' => $data['name'],
                    'units' => $unitsStr,
                    'amount' => $data['amount'],
                    'percentage' => $pct,
                ];
            }
        } else {
            $pendingPct = 100.0;
        }

        return response()->json([
            'total_receipts' => $totalReceipts,
            'total_outstanding' => $totalOutstanding,
            'total_value' => $totalValue,
            'realized_pct' => $realizedPct,
            'pending_pct' => $pendingPct,
            'customers' => $customersList,
        ]);
    }
}
