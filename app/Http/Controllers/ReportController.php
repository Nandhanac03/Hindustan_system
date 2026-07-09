<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\Floor;
use App\Models\UnitRateLog;
use App\Models\Sale;
use App\Models\Receipt;
use App\Models\PartnerAllocation;
use App\Models\Brokerage;
use App\Models\Broker;
use App\Models\Customer;
use App\Models\Payee;
use App\Models\Account;
use App\Models\Loan;
use App\Models\EmiSchedule;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Common lookup tables for filter dropdowns
        $projects = Project::where('is_active', true)->orderBy('name')->get();
        if (!$request->has('project_id') && !$request->filled('project_id') && $projects->isNotEmpty()) {
            $request->merge(['project_id' => (string)$projects->first()->id]);
        }
        $customers = Customer::orderBy('name')->get();
        $brokers = Broker::orderBy('name')->get();
        $partners = Payee::orderBy('name')->get();
        $unitTypes = UnitType::where('is_active', true)->orderBy('name')->get();
        $bankAccounts = Account::where('type', 'Asset')->where('name', 'like', '%bank%')->get();

        $activeTab = $request->query('report', 'availability');

        // Initialize variables for each tab to avoid undefined errors
        $inventorySummary = [];
        $inventoryGrid = collect();
        $salesList = collect();
        $emiCollectionsSummary = [];
        $ledgerEntries = collect();
        $cashBookEntries = collect();
        $bankReportEntries = collect();
        $partnerAllocations = collect();
        $supplierContractorEntries = collect();
        $salesReturns = collect();
        $exchangeEntries = collect();
        $pettyCashEntries = collect();
        $loanSchedules = collect();
        $trialBalanceEntries = collect();
        $profitLossEntries = [];
        $balanceSheetEntries = [];

        $selectedCustomer = null;
        $totalDebits = 0;
        $totalCredits = 0;
        $closingBalance = 0;

        // 1. AVAILABILITY REPORT
        if ($activeTab === 'availability') {
            $invQuery = Unit::with(['floor', 'unitType', 'project']);
            if ($request->filled('project_id')) {
                $invQuery->where('project_id', $request->project_id);
            }
            if ($request->filled('floor_id')) {
                $invQuery->where('floor_id', $request->floor_id);
            }
            if ($request->filled('unit_type_id')) {
                $invQuery->where('unit_type_id', $request->unit_type_id);
            }
            $inventoryGrid = $invQuery->orderBy('door_no')->paginate(50);

            $sumQuery = Unit::select('unit_type_id', 
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status="available" THEN 1 ELSE 0 END) as available'),
                DB::raw('SUM(CASE WHEN status="sold" OR status="booked" THEN 1 ELSE 0 END) as sold'),
                DB::raw('SUM(built_up_area) as total_built_up'),
                DB::raw('SUM(carpet_area) as total_carpet')
            )->groupBy('unit_type_id');

            if ($request->filled('project_id')) {
                $sumQuery->where('project_id', $request->project_id);
            }
            $inventorySummary = $sumQuery->with('unitType')->get();
        }

        // 2. SALES REPORT
        if ($activeTab === 'sales') {
            $salesQuery = Sale::with(['customer', 'unit.unitType', 'project'])->where('status', 'active');
            if ($request->filled('project_id')) {
                $salesQuery->where('project_id', $request->project_id);
            }
            if ($request->filled('category')) {
                $salesQuery->whereHas('unit.unitType', function ($q) use ($request) {
                    $q->where('category', $request->category);
                });
            }
            $salesList = $salesQuery->orderByDesc('sale_date')->paginate(50);
        }

        // 3. EMI & COLLECTION REPORTS
        if ($activeTab === 'emi_collections') {
            $emiCollectionsSummary = [
                'total_receivable' => Sale::where('status', 'active')->sum('total_amount'),
                'total_received'   => Receipt::sum('amount'),
                'outstanding'      => Sale::where('status', 'active')->sum('remaining_balance'),
                'mtd_collections'  => Receipt::whereMonth('receipt_date', now()->month)->whereYear('receipt_date', now()->year)->sum('amount'),
            ];
            $cashBookEntries = Receipt::with(['customer', 'sale.project', 'sale.unit'])->orderByDesc('receipt_date')->paginate(50);
        }

        // 4. CUSTOMER LEDGER / STATEMENT
        if ($activeTab === 'customer_ledger') {
            if ($request->filled('customer_id')) {
                $selectedCustomer = Customer::findOrFail($request->customer_id);
                $sale = Sale::with(['project', 'unit', 'receipts'])
                    ->where('customer_id', $request->customer_id)
                    ->where('status', 'active')
                    ->first();

                if ($sale) {
                    $ledgerQuery = collect();

                    $ledgerQuery->push([
                        'date' => Carbon::parse($sale->sale_date)->format('d M Y'),
                        'description' => 'Sale Agreement Registration',
                        'debit' => (float)$sale->total_amount,
                        'credit' => 0.0,
                        'payment_mode' => '—',
                        'ref_no' => $sale->sale_number,
                    ]);

                    foreach ($sale->receipts as $receipt) {
                        $ledgerQuery->push([
                            'date' => Carbon::parse($receipt->receipt_date)->format('d M Y'),
                            'description' => 'Payment Receipt' . ($receipt->remarks ? " — {$receipt->remarks}" : ""),
                            'debit' => 0.0,
                            'credit' => (float)$receipt->amount,
                            'payment_mode' => $receipt->payment_mode,
                            'ref_no' => $receipt->reference_no ?? 'REC-' . sprintf("%05d", $receipt->id),
                        ]);
                    }

                    $ledgerEntries = $ledgerQuery->sortBy(fn($r) => Carbon::parse($r['date']))->values();

                    $runningBalance = 0;
                    foreach ($ledgerEntries as &$entry) {
                        $runningBalance += $entry['debit'] - $entry['credit'];
                        $entry['balance'] = $runningBalance;
                    }
                    unset($entry);

                    $totalDebits = $ledgerEntries->sum('debit');
                    $totalCredits = $ledgerEntries->sum('credit');
                    $closingBalance = (float)$sale->remaining_balance;
                }
            }
        }

        // 5. CASH BOOK
        if ($activeTab === 'cash_book') {
            $cashQuery = Receipt::with(['customer', 'sale.project', 'sale.unit']);
            if ($request->filled('payment_mode')) {
                $cashQuery->where('payment_mode', $request->payment_mode);
            }
            if ($request->filled('date_from')) {
                $cashQuery->whereDate('receipt_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $cashQuery->whereDate('receipt_date', '<=', $request->date_to);
            }
            $cashBookEntries = $cashQuery->orderByDesc('receipt_date')->paginate(50);
        }

        // 6. BANK REPORTS
        if ($activeTab === 'bank_reports') {
            $bankQuery = Receipt::with(['customer', 'sale.project', 'sale.unit'])->whereIn('payment_mode', ['Bank Transfer', 'Cheque', 'Online']);
            if ($request->filled('bank_name')) {
                $bankQuery->where('bank_name', 'like', "%{$request->bank_name}%");
            }
            if ($request->filled('date_from')) {
                $bankQuery->whereDate('receipt_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $bankQuery->whereDate('receipt_date', '<=', $request->date_to);
            }
            $bankReportEntries = $bankQuery->orderByDesc('receipt_date')->paginate(50);
        }

        // 7. PARTNER STATEMENTS
        if ($activeTab === 'partner_statements') {
            $allocQuery = PartnerAllocation::with(['partner', 'project', 'payment.customer']);
            if ($request->filled('partner_id')) {
                $allocQuery->where('partner_id', $request->partner_id);
            }
            if ($request->filled('project_id')) {
                $allocQuery->where('project_id', $request->project_id);
            }
            $partnerAllocations = $allocQuery->orderByDesc('date')->paginate(50);
        }

        // 8. SUPPLIER & CONTRACTOR STATEMENTS
        if ($activeTab === 'supplier_contractor') {
            // Broker commissions act as suppliers/contractors payables
            $supplierQuery = Brokerage::with(['broker', 'sale.project', 'sale.customer']);
            if ($request->filled('broker_id')) {
                $supplierQuery->where('broker_id', $request->broker_id);
            }
            $supplierContractorEntries = $supplierQuery->paginate(50);
        }

        // 9. SALES RETURN REPORT
        if ($activeTab === 'sales_return') {
            $retQuery = Sale::with(['customer', 'unit.unitType', 'project'])->whereIn('status', ['cancelled', 'returned']);
            if ($request->filled('project_id')) {
                $retQuery->where('project_id', $request->project_id);
            }
            $salesReturns = $retQuery->orderByDesc('cancelled_at')->paginate(50);
        }

        // 10. EXCHANGE REPORT
        if ($activeTab === 'exchange_report') {
            $exQuery = Sale::with(['customer', 'unit', 'project'])->where('status', 'exchanged');
            if ($request->filled('project_id')) {
                $exQuery->where('project_id', $request->project_id);
            }
            $exchangeEntries = $exQuery->orderByDesc('sale_date')->paginate(50);
        }

        // 11. PETTY CASH BOOK
        if ($activeTab === 'petty_cash') {
            $pettyQuery = Receipt::with(['customer', 'sale.project'])->where('payment_mode', 'Cash');
            if ($request->filled('date_from')) {
                $pettyQuery->whereDate('receipt_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $pettyQuery->whereDate('receipt_date', '<=', $request->date_to);
            }
            $pettyCashEntries = $pettyQuery->orderByDesc('receipt_date')->paginate(50);
        }

        // 12. BANK LOAN EMI SCHEDULES
        if ($activeTab === 'loan_schedules') {
            $loanSchedules = EmiSchedule::with(['loan.project'])->orderBy('due_date')->paginate(50);
        }

        // 13. TRIAL BALANCE
        if ($activeTab === 'trial_balance') {
            // We group bank accounts, sales, and receivables to generate a clean TB statement
            $trialBalanceEntries = Account::orderBy('code')->get()->map(function($acc) {
                // Mock dynamic balance based on ledger summaries
                $debit = 0.0;
                $credit = 0.0;
                if ($acc->type === 'Asset') {
                    $debit = $acc->name === 'Cash' ? (float)Receipt::where('payment_mode', 'Cash')->sum('amount') : (float)Receipt::where('payment_mode', '!=', 'Cash')->sum('amount');
                } elseif ($acc->type === 'Revenue') {
                    $credit = (float)Sale::where('status', 'active')->sum('total_amount');
                } elseif ($acc->type === 'Expense') {
                    $debit = (float)Brokerage::sum('paid_amount');
                }
                return [
                    'code' => $acc->code,
                    'name' => $acc->name,
                    'type' => $acc->type,
                    'debit' => $debit,
                    'credit' => $credit,
                ];
            });
        }

        // 14. PROFIT & LOSS
        if ($activeTab === 'profit_loss') {
            $revenue = (float)Sale::where('status', 'active')->sum('total_amount');
            $brokeragePaid = (float)Brokerage::sum('paid_amount');
            $financingCosts = (float)EmiSchedule::where('status', 'Paid')->sum('interest_component');

            $profitLossEntries = [
                'revenue' => $revenue,
                'brokerage' => $brokeragePaid,
                'financing' => $financingCosts,
                'net_profit' => max(0, $revenue - ($brokeragePaid + $financingCosts)),
            ];
        }

        // 15. BALANCE SHEET SUMMARY
        if ($activeTab === 'balance_sheet') {
            $cashAssets = (float)Receipt::where('payment_mode', 'Cash')->sum('amount');
            $bankAssets = (float)Receipt::where('payment_mode', '!=', 'Cash')->sum('amount');
            $receivables = (float)Sale::where('status', 'active')->sum('remaining_balance');
            $loansPayable = (float)Loan::sum('principal_amount') - (float)EmiSchedule::where('status', 'Paid')->sum('principal_component');

            $balanceSheetEntries = [
                'assets' => [
                    'Cash in Hand' => $cashAssets,
                    'Cash at Bank' => $bankAssets,
                    'Trade Receivables' => $receivables,
                ],
                'liabilities' => [
                    'Bank Loans Payable' => $loansPayable,
                    'Partner Retained Capitals' => (float)PartnerAllocation::sum('allocated_amount'),
                ],
            ];
        }

        return view('reports.index', compact(
            'projects',
            'customers',
            'brokers',
            'partners',
            'unitTypes',
            'bankAccounts',
            'activeTab',
            'inventorySummary',
            'inventoryGrid',
            'salesList',
            'emiCollectionsSummary',
            'ledgerEntries',
            'selectedCustomer',
            'totalDebits',
            'totalCredits',
            'closingBalance',
            'cashBookEntries',
            'bankReportEntries',
            'partnerAllocations',
            'supplierContractorEntries',
            'salesReturns',
            'exchangeEntries',
            'pettyCashEntries',
            'loanSchedules',
            'trialBalanceEntries',
            'profitLossEntries',
            'balanceSheetEntries'
        ));
    }
}
