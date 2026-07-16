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
use App\Models\ActivityLog;
use App\Models\Approval;
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
        $partners = Payee::where('type', 'Partner')->orderBy('name')->get();
        $suppliers = Payee::whereIn('type', ['Supplier', 'Contractor'])->orderBy('name')->get();
        if ($suppliers->isEmpty()) {
            $suppliers = Payee::orderBy('name')->get();
        }
        $unitTypes = UnitType::where('is_active', true)->orderBy('name')->get();
        $bankAccounts = Account::where('type', 'Asset')->where('name', 'like', '%bank%')->get();

        $activeTab = $request->query('report', 'dashboard');

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
        
        $shops = collect();
        $flats = collect();
        $parkings = collect();
        $others = collect();
        $groupedSummary = collect();

        $dashboardData = [];
        $auditTrailEntries = collect();
        $approvalReportEntries = collect();
        $cashBookStats = [];
        $cashBookChartData = [];

        $selectedCustomer = null;
        $totalDebits = 0;
        $totalCredits = 0;
        $closingBalance = 0;

        // 1. AVAILABILITY REPORT
        if ($activeTab === 'availability') {
            $invQuery = Unit::with(['floor', 'unitType', 'project', 'sale.customer']);
            if ($request->filled('project_id')) {
                $invQuery->where('project_id', $request->project_id);
            }
            if ($request->filled('floor_id')) {
                $invQuery->where('floor_id', $request->floor_id);
            }
            if ($request->filled('unit_type_id')) {
                $invQuery->where('unit_type_id', $request->unit_type_id);
            }
            
            $allUnits = $invQuery->orderBy('door_no')->get();
            $inventoryGrid = $allUnits;

            // Only available units for the summary and lists
            $availableUnits = $allUnits->where('status', 'available');

            // Compute summary from available units
            $groupedSummary = $availableUnits->groupBy(function($unit) {
                $name = strtolower($unit->unitType?->name ?? 'other');
                if (str_contains($name, 'shop') || $unit->unitType?->category === 'commercial') {
                    return 'SHOP';
                } elseif (str_contains($name, 'flat') || str_contains($name, 'bhk') || str_contains($name, 'villa') || $unit->unitType?->category === 'residential') {
                    return 'FLAT';
                } elseif (str_contains($name, 'parking') || $unit->unitType?->category === 'parking') {
                    return 'PARKING';
                } elseif (str_contains($name, 'counter')) {
                    return 'COUNTER';
                } else {
                    return 'OTHER';
                }
            })->map(function($units, $key) {
                return (object)[
                    'type'          => $key,
                    'nos'           => $units->count(),
                    'built_up_area' => $units->sum('built_up_area'),
                    'carpet_area'   => $units->sum('carpet_area'),
                ];
            })->values();

            // Split into sub-collections for the view (available units only)
            $shops = $availableUnits->filter(fn($u) => str_contains(strtolower($u->unitType?->name ?? ''), 'shop') || $u->unitType?->category === 'commercial')->values();
            $flats = $availableUnits->filter(fn($u) => str_contains(strtolower($u->unitType?->name ?? ''), 'flat') || str_contains(strtolower($u->unitType?->name ?? ''), 'bhk') || str_contains(strtolower($u->unitType?->name ?? ''), 'villa') || $u->unitType?->category === 'residential')->values();
            $parkings = $availableUnits->filter(fn($u) => str_contains(strtolower($u->unitType?->name ?? ''), 'parking') || $u->unitType?->category === 'parking')->values();
            $others = $availableUnits->filter(fn($u) => !$shops->contains('id', $u->id) && !$flats->contains('id', $u->id) && !$parkings->contains('id', $u->id))->values();

            $inventorySummary = $groupedSummary;
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

        // 5. CASH BOOK — Partner Analytics Dashboard
        if ($activeTab === 'cash_book') {
            $cashQuery = Receipt::with(['customer', 'sale.project', 'sale.unit', 'partner']);

            // Partner filter
            $selectedPartnerId = $request->filled('partner_id') ? (int)$request->partner_id : null;
            if ($selectedPartnerId) {
                $cashQuery->where('partner_id', $selectedPartnerId);
            }

            // Payment mode filter
            if ($request->filled('payment_mode')) {
                $cashQuery->where('payment_mode', $request->payment_mode);
            }
            if ($request->filled('date_from')) {
                $cashQuery->whereDate('receipt_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $cashQuery->whereDate('receipt_date', '<=', $request->date_to);
            }
            if ($request->filled('project_id')) {
                $cashQuery->where('project_id', $request->project_id);
            }

            $cashBookEntries = $cashQuery->orderByDesc('receipt_date')->paginate(25);

            // --- Summary Stats ---
            $statsBaseQuery = Receipt::query();
            if ($selectedPartnerId) {
                $statsBaseQuery->where('partner_id', $selectedPartnerId);
            }
            if ($request->filled('date_from')) {
                $statsBaseQuery->whereDate('receipt_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $statsBaseQuery->whereDate('receipt_date', '<=', $request->date_to);
            }
            if ($request->filled('project_id')) {
                $statsBaseQuery->where('project_id', $request->project_id);
            }

            $totalReceived = (float)$statsBaseQuery->sum('amount');
            $cashReceived  = (float)(clone $statsBaseQuery)->where('payment_mode', 'Cash')->sum('amount');
            $bankReceived  = (float)(clone $statsBaseQuery)->whereIn('payment_mode', ['Bank Transfer', 'Cheque', 'Online', 'UPI'])->sum('amount');
            // Pending = outstanding balance of active sales for partner's customers
            $pendingQuery  = Sale::where('status', 'active')->where('remaining_balance', '>', 0);
            if ($selectedPartnerId) {
                $pendingQuery->whereHas('receipts', fn($q) => $q->where('partner_id', $selectedPartnerId));
            }
            $pendingBalance = (float)$pendingQuery->sum('remaining_balance');

            $cashBookStats = [
                'total_received'  => $totalReceived,
                'cash_received'   => $cashReceived,
                'bank_received'   => $bankReceived,
                'pending_balance' => $pendingBalance,
                'selected_partner_id' => $selectedPartnerId,
            ];

            // --- Monthly trend (last 12 months) ---
            $monthlyData = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $monthQ = Receipt::query()
                    ->whereYear('receipt_date', $month->year)
                    ->whereMonth('receipt_date', $month->month);
                if ($selectedPartnerId) {
                    $monthQ->where('partner_id', $selectedPartnerId);
                }
                if ($request->filled('project_id')) {
                    $monthQ->where('project_id', $request->project_id);
                }
                $monthlyData[] = [
                    'label'  => $month->format('M Y'),
                    'amount' => (float)$monthQ->sum('amount'),
                ];
            }

            // --- Payment mode distribution ---
            $paymentModes = Receipt::query()
                ->selectRaw('payment_mode, SUM(amount) as total')
                ->when($selectedPartnerId, fn($q) => $q->where('partner_id', $selectedPartnerId))
                ->when($request->filled('date_from'), fn($q) => $q->whereDate('receipt_date', '>=', $request->date_from))
                ->when($request->filled('date_to'),   fn($q) => $q->whereDate('receipt_date', '<=', $request->date_to))
                ->when($request->filled('project_id'), fn($q) => $q->where('project_id', $request->project_id))
                ->groupBy('payment_mode')
                ->orderByDesc('total')
                ->get();

            // --- Partner-wise breakdown ---
            $partnerWise = Receipt::query()
                ->selectRaw('partner_id, SUM(amount) as total')
                ->with('partner')
                ->when($request->filled('date_from'), fn($q) => $q->whereDate('receipt_date', '>=', $request->date_from))
                ->when($request->filled('date_to'),   fn($q) => $q->whereDate('receipt_date', '<=', $request->date_to))
                ->when($request->filled('project_id'), fn($q) => $q->where('project_id', $request->project_id))
                ->groupBy('partner_id')
                ->orderByDesc('total')
                ->get();

            // --- Daily trend (last 30 days) ---
            $dailyData = [];
            for ($i = 29; $i >= 0; $i--) {
                $day = Carbon::now()->subDays($i);
                $dayQ = Receipt::query()->whereDate('receipt_date', $day->toDateString());
                if ($selectedPartnerId) {
                    $dayQ->where('partner_id', $selectedPartnerId);
                }
                if ($request->filled('project_id')) {
                    $dayQ->where('project_id', $request->project_id);
                }
                $dailyData[] = [
                    'label'  => $day->format('d M'),
                    'amount' => (float)$dayQ->sum('amount'),
                ];
            }

            $cashBookChartData = [
                'monthly'      => $monthlyData,
                'daily'        => $dailyData,
                'payment_modes' => $paymentModes,
                'partner_wise'  => $partnerWise,
            ];
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
            if ($request->filled('category')) {
                $cat = strtolower($request->category);
                if (in_array($cat, ['apartment', 'apartments', 'residential'])) {
                    $cat = 'residential';
                }
                $retQuery->whereHas('unit.unitType', function ($q) use ($cat) {
                    $q->where('category', $cat);
                });
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
            $siteExpenses = (float)DB::table('bills')->sum('final_amount');

            $profitLossEntries = [
                'revenue' => $revenue,
                'brokerage' => $brokeragePaid,
                'financing' => $financingCosts,
                'site_expenses' => $siteExpenses,
                'net_profit' => max(0, $revenue - ($brokeragePaid + $financingCosts + $siteExpenses)),
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

        // 16. DASHBOARD & MIS ANALYTICS
        if ($activeTab === 'dashboard') {
            $totalProjects = Project::where('is_active', true)->count();
            $totalUnits = Unit::count();
            $soldUnits = Unit::whereIn('status', ['sold', 'booked'])->count();
            $unsoldUnits = Unit::where('status', 'available')->count();
            $collections = (float)Receipt::sum('amount');
            $outstanding = (float)Sale::where('status', 'active')->sum('remaining_balance');
            $cashBalance = (float)Receipt::where('payment_mode', 'Cash')->sum('amount');
            $bankBalance = (float)Receipt::whereIn('payment_mode', ['Bank Transfer', 'Online', 'Cheque'])->sum('amount');
            $emiDue = (float)EmiSchedule::where('status', 'Due')->sum('emi_amount');

            // Profit calculation
            $revenue = (float)Sale::where('status', 'active')->sum('total_amount');
            $brokeragePaid = (float)Brokerage::sum('paid_amount');
            $financingCosts = (float)EmiSchedule::where('status', 'Paid')->sum('interest_component');
            $totalBills = (float)DB::table('bills')->sum('final_amount');
            $profit = max(0, $revenue - ($brokeragePaid + $financingCosts + $totalBills));

            // Bank Loan EMI alerts (upcoming due EMIs next 30 days)
            $loanEmiAlerts = EmiSchedule::with(['loan.project'])
                ->where('status', 'Due')
                ->whereDate('due_date', '<=', Carbon::now()->addDays(30))
                ->orderBy('due_date')
                ->get();

            // Profitability analysis per project
            $projectProfitability = Project::where('is_active', true)->get()->map(function($proj) {
                $expectedRev = (float)Unit::where('project_id', $proj->id)->sum('expected_sale_amount');
                $actualRev = (float)Sale::where('project_id', $proj->id)->where('status', 'active')->sum('total_amount');
                
                // Costs Breakdowns
                $partnerPayouts = (float)PartnerAllocation::where('project_id', $proj->id)->sum('allocated_amount');
                $brokerageCosts = (float)Brokerage::whereHas('sale', fn($q) => $q->where('project_id', $proj->id))->sum('paid_amount');
                
                // Fetch actual material costs (payee type 'Supplier')
                $materialCosts = (float)DB::table('bills')
                    ->join('payees', 'bills.payee_id', '=', 'payees.id')
                    ->where('bills.project_id', $proj->id)
                    ->where('payees.type', 'Supplier')
                    ->sum('bills.final_amount');

                // Fetch actual contractor payments (payee type 'Contractor')
                $contractorPayments = (float)DB::table('bills')
                    ->join('payees', 'bills.payee_id', '=', 'payees.id')
                    ->where('bills.project_id', $proj->id)
                    ->where('payees.type', 'Contractor')
                    ->sum('bills.final_amount');

                // Fetch other project expenses
                $siteExpenses = (float)DB::table('bills')
                    ->join('payees', 'bills.payee_id', '=', 'payees.id')
                    ->where('bills.project_id', $proj->id)
                    ->whereNotIn('payees.type', ['Supplier', 'Contractor', 'Partner'])
                    ->sum('bills.final_amount');

                $otherExpenses = 0.0;
                
                $totalCost = $partnerPayouts + $brokerageCosts + $materialCosts + $contractorPayments + $siteExpenses + $otherExpenses;
                $profit = max(0, $actualRev - $totalCost);
                $margin = $actualRev > 0 ? ($profit / $actualRev) * 100 : 0.0;

                return [
                    'project' => $proj,
                    'expected_revenue' => $expectedRev,
                    'actual_revenue' => $actualRev,
                    'partner_payouts' => $partnerPayouts,
                    'brokerage_costs' => $brokerageCosts,
                    'material_costs' => $materialCosts,
                    'contractor_payments' => $contractorPayments,
                    'site_expenses' => $siteExpenses,
                    'other_expenses' => $otherExpenses,
                    'total_cost' => $totalCost,
                    'profit' => $profit,
                    'margin' => $margin
                ];
            });

            $dashboardData = [
                'total_projects' => $totalProjects,
                'total_units' => $totalUnits,
                'sold_units' => $soldUnits,
                'unsold_units' => $unsoldUnits,
                'collections' => $collections,
                'outstanding' => $outstanding,
                'cash_balance' => $cashBalance,
                'bank_balance' => $bankBalance,
                'emi_due' => $emiDue,
                'profit' => $profit,
                'loan_emi_alerts' => $loanEmiAlerts,
                'project_profitability' => $projectProfitability
            ];
        }

        // 17. AUDIT TRAIL REPORT
        if ($activeTab === 'audit_trail') {
            $auditTrailEntries = ActivityLog::with('user')->orderByDesc('created_at')->paginate(50);
        }

        // 18. APPROVAL REPORTS
        if ($activeTab === 'approvals') {
            $approvalReportEntries = Approval::with(['requester', 'approver'])->orderByDesc('created_at')->paginate(50);
        }

        return view('reports.index', compact(
            'projects',
            'customers',
            'brokers',
            'partners',
            'suppliers',
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
            'balanceSheetEntries',
            'dashboardData',
            'auditTrailEntries',
            'approvalReportEntries',
            'cashBookStats',
            'cashBookChartData',
            'shops',
            'flats',
            'parkings',
            'others',
            'groupedSummary'
        ));
    }
}
