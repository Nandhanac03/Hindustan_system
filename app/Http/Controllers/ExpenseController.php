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
            ->get();

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
        ]);

        DB::table('bills')->insert([
            'system_id' => $systemId,
            'payee_id' => $request->payee_id,
            'project_id' => $request->project_id,
            'bill_number' => $request->bill_number,
            'bill_amount' => $request->bill_amount,
            'final_amount' => $request->final_amount,
            'status' => 'approved_unpaid', // Immediately available in receipt split target
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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
}
