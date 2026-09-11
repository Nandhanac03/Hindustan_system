<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Floor;
use App\Models\Project;
use App\Models\Customer;
use App\Models\System;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\UnitRateLog;
use App\Models\UnitStatusLog;
use App\Models\Sale;
use App\Models\Receipt;
use App\Models\SaleExtraWork;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
class CustomerController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        // SystemScope auto-scopes by logged-in user unless Owner
       $query = Customer::query();
 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('customer_id')) {
            if (is_array($request->customer_id)) {
                $query->whereIn('id', $request->customer_id);
            } else {
                $query->where('id', $request->customer_id);
            }
        }
 
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }
 
        $customers = $query->withCount('sales')
            ->withSum('sales as total_purchase', 'total_amount')
            ->withSum(['receipts as total_paid' => function($q) {
                $q->whereNull('partner_id');
            }], 'amount')
            ->orderBy('name')
            ->get();
    
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['customers' => $customers]);
        }
 
        $allCustomers = Customer::orderBy('name')->select('id', 'name', 'phone', 'email')->get();

        return view('customers.index', compact('allCustomers'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:191'],
            'email'           => ['required', 'email', 'max:191', Rule::unique('customers', 'email')],
            'phone'           => ['nullable', 'string', 'max:20'],
            'address'         => ['nullable', 'string'],
            // 'id_proof_type'   => ['nullable', 'string', 'max:50'],
            // 'id_proof_number' => ['nullable', 'string', 'max:50'],
            // 'system'          => ['required', Rule::in(['india', 'uae'])],
            // 'is_active'       => ['nullable', 'boolean'],
        ]);
    
        // Generate simple 2-letter avatar initials from the name, matching existing seeded data style
        // $nameParts = explode(' ', trim($validated['name']));
        // $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
    
        $customer = Customer::create([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'phone'           => $validated['phone'] ?? null,
            'address'         => $validated['address'] ?? null,
            // 'id_proof_type'   => $validated['id_proof_type'] ?? null,
            // 'id_proof_number' => $validated['id_proof_number'] ?? null,
            // 'system'          => $validated['system'],
            'is_active'       =>  1,
            // 'avatar_url'      => $initials,
        ]);
    
        return response()->json(['customer' => $customer], 201);
    }
    public function edit(Request $request, Customer $customer)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'customer' => $customer,
            ]);
        }
 
        return view('customers.edit', compact('customer'));
    }
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => [
                'nullable', 'email', 'max:255',
                Rule::unique('customers', 'email')->ignore($customer->id),
            ],
            'phone'     => ['nullable', 'string', 'max:30'],
            'address'   => ['nullable', 'string', 'max:1000'],
            'is_active' => ['required', 'boolean'],
        ]);
 
        $customer->update($validated);
 
        return response()->json([
            'message'  => 'Customer updated successfully.',
            'customer' => $customer,
        ]);
    }

    /**
     * Remove the specified customer.
    */
    public function destroy(Customer $customer)
    {
        if ($customer->sales()->count() > 0) {
            return response()->json([
                'error' => 'Cannot delete customer with associated properties.',
            ], 422);
        }

        $customer->delete();
 
        return response()->json([
            'message' => 'Customer deleted successfully.',
        ]);
    }

    public function statementData(Customer $customer): JsonResponse
    {
        $customer->load([
            'sales.project',
            'sales.unit.floor',
            'sales.unit.unitType',
            'sales.saleUnits.unit.floor',
            'sales.saleUnits.unit.unitType',
            'sales.customerInstallments',
            'receipts.sale',
            'receipts.unit',
        ]);

        $projectNames = $customer->sales->pluck('project.name')->filter()->unique()->values();
        $companyOrProject = $projectNames->count() > 0 ? $projectNames->implode(', ') : 'Tabasco Hindustan Infra Developers Pvt. Ltd';

        // 1. Client Master Properties List
        $properties = $customer->sales->map(function ($sale) {
            $uName = $sale->unit ? $sale->unit->formatted_name : '';
            if (!$uName && $sale->saleUnits && $sale->saleUnits->count() > 0) {
                $uName = $sale->saleUnits->map(fn($su) => $su->unit?->formatted_name)->filter()->implode(', ');
            }
            return [
                'sale_number' => $sale->sale_number ?? ('SL-' . $sale->id),
                'project_name' => $sale->project?->name ?? 'Tabasco Hindustan Infra Developers',
                'unit_name' => $uName ?: 'N/A',
                'floor' => $sale->unit?->floor?->name ?? 'N/A',
                'unit_type' => $sale->unit?->unitType?->name ?? 'N/A',
                'agreement_date' => $sale->agreement_date ? $sale->agreement_date->format('d/m/Y') : ($sale->sale_date ? $sale->sale_date->format('d/m/Y') : 'N/A'),
                'sale_amount' => (float)($sale->sale_amount ?? 0),
                'gst_amount' => (float)($sale->gst_amount ?? 0),
                'total_amount' => (float)($sale->total_amount ?? 0),
                'status' => strtoupper($sale->status ?? 'ACTIVE'),
            ];
        });

        // 2. Client Master Installment Schedule
        $installments = [];
        foreach ($customer->sales as $sale) {
            $uName = $sale->unit ? $sale->unit->formatted_name : '';
            if (!$uName && $sale->saleUnits && $sale->saleUnits->count() > 0) {
                $uName = $sale->saleUnits->map(fn($su) => $su->unit?->formatted_name)->filter()->implode(', ');
            }
            foreach ($sale->customerInstallments as $ci) {
                $dueDate = $ci->due_date;
                $label = $ci->label;
                if (!$label) {
                    $label = ($ci->installment_no == 0) ? 'Down Payment' : "EMI - {$ci->installment_no}";
                } elseif (preg_match('/^EMI\s*(\d+)$/i', $label, $m)) {
                    $label = "EMI - {$m[1]}";
                }

                // Only include paid/partially paid installments (omit unpaid pending)
                $paidAmt = (float)($ci->paid_amount ?? 0);
                $ciStatus = strtolower($ci->status ?? 'pending');
                if ($paidAmt <= 0 && $ciStatus !== 'paid') {
                    continue;
                }

                $installments[] = [
                    'sale_id' => $sale->id,
                    'sale_number' => $sale->sale_number ?? ('SL-' . $sale->id),
                    'unit_name' => $uName ?: 'N/A',
                    'installment_no' => $ci->installment_no,
                    'label' => $label,
                    'due_date' => $dueDate ? $dueDate->format('d/m/Y') : 'N/A',
                    'due_date_raw' => $dueDate ? $dueDate->format('n/j/Y') : 'N/A',
                    'timestamp' => $dueDate ? $dueDate->timestamp : 0,
                    'amount' => (float)($ci->amount ?? 0),
                    'paid_amount' => $paidAmt,
                    'balance_amount' => max(0, (float)($ci->amount ?? 0) - $paidAmt),
                    'status' => strtoupper($ci->status ?? 'PAID'),
                ];
            }
        }

        // 3. All Transactions (Ledger)
        $transactions = [];

        foreach ($customer->sales as $sale) {
            $uName = $sale->unit ? $sale->unit->formatted_name : '';
            $saleDate = $sale->sale_date ?? $sale->agreement_date ?? $sale->created_at;
            $transactions[] = [
                'date' => $saleDate ? $saleDate->format('n/j/Y') : 'N/A',
                'date_dmy' => $saleDate ? $saleDate->format('d/m/Y') : 'N/A',
                'timestamp' => $saleDate ? $saleDate->timestamp : 0,
                'v_no' => $sale->sale_number ?? ('SL-' . $sale->id),
                'description' => 'Sales' . ($uName ? " ({$uName})" : ''),
                'payment_type' => 'Cheque',
                'debit' => (float)($sale->total_amount ?? $sale->sale_amount ?? 0),
                'credit' => 0,
                'is_cash' => false,
            ];

            // Extra works for this sale
            $extraWorks = SaleExtraWork::where('sale_id', $sale->id)->get();
            foreach ($extraWorks as $work) {
                $transactions[] = [
                    'date' => $work->created_at ? $work->created_at->format('n/j/Y') : 'N/A',
                    'date_dmy' => $work->created_at ? $work->created_at->format('d/m/Y') : 'N/A',
                    'timestamp' => $work->created_at ? $work->created_at->timestamp : 0,
                    'v_no' => 'EW-' . $work->id,
                    'description' => 'Additional Work: ' . ($work->description ?? 'Extra work'),
                    'payment_type' => 'Cheque',
                    'debit' => (float)($work->line_total ?? $work->amount ?? 0),
                    'credit' => 0,
                    'is_cash' => false,
                ];
            }

            // If cancelled sale, add Sales Return credit
            if ($sale->status === 'cancelled') {
                $cancelDate = $sale->updated_at ?? $saleDate;
                $refundVal = (float)($sale->refund_amount > 0 ? $sale->refund_amount : ($sale->total_amount ?? 0));
                $transactions[] = [
                    'date' => $cancelDate ? $cancelDate->format('n/j/Y') : 'N/A',
                    'date_dmy' => $cancelDate ? $cancelDate->format('d/m/Y') : 'N/A',
                    'timestamp' => $cancelDate ? $cancelDate->timestamp : 0,
                    'v_no' => 'SR-' . $sale->id,
                    'description' => 'Sales Return' . ($uName ? " ({$uName})" : ''),
                    'payment_type' => 'Cheque',
                    'debit' => 0,
                    'credit' => $refundVal,
                    'is_cash' => false,
                ];
            }
        }

        // Receipts
        $receipts = $customer->receipts()->whereNull('partner_id')->orderBy('receipt_date')->get();
        foreach ($receipts as $idx => $r) {
            $rDate = $r->receipt_date ?? $r->created_at;
            $mode = $r->payment_mode ?? 'Cheque';
            $isCash = (strcasecmp($mode, 'cash') === 0);
            
            $ordinal = ($idx + 1) . 'th Installment';
            if ($idx === 0) $ordinal = '1st Installment';
            elseif ($idx === 1) $ordinal = '2nd Installment';
            elseif ($idx === 2) $ordinal = '3rd Installment';
            elseif ($idx === 3) $ordinal = '4th Installment';
            elseif ($idx === 4) $ordinal = '5th Installment';

            $desc = $r->remarks ?: $ordinal;

            $transactions[] = [
                'date' => $rDate ? $rDate->format('n/j/Y') : 'N/A',
                'date_dmy' => $rDate ? $rDate->format('d/m/Y') : 'N/A',
                'timestamp' => $rDate ? $rDate->timestamp : 0,
                'v_no' => $r->reference_no ?: ('RCT-' . $r->id),
                'description' => $desc,
                'payment_type' => $mode ?: 'Cheque',
                'debit' => 0,
                'credit' => (float)($r->amount ?? 0),
                'is_cash' => $isCash,
            ];
        }

        // Sort chronologically
        usort($transactions, fn($a, $b) => $a['timestamp'] <=> $b['timestamp']);

        return response()->json([
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email ?? 'N/A',
                'phone' => $customer->phone ?? 'N/A',
                'address' => $customer->address ?? 'N/A',
                'is_active' => (bool)$customer->is_active,
            ],
            'project_title' => $companyOrProject,
            'properties' => $properties,
            'installments' => $installments,
            'transactions' => $transactions,
        ]);
    }
}
