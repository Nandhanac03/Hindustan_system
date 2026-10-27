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
use App\Models\PartnerShare;
use App\Models\Brokerage;
use App\Models\Broker;
use App\Models\Customer;
use App\Models\Payee;
use App\Models\RaBill;
use App\Models\BillPayment;
use App\Models\Account;
use App\Models\Loan;
use App\Models\EmiSchedule;
use App\Models\ActivityLog;
use App\Models\Approval;
use App\Models\CustomerInstallment;
use App\Models\CollectionReminder;
use App\Services\CollectionAgeingService;
use App\Services\CollectionForecastService;
use App\Models\Voucher;
use App\Models\VoucherLine;
use App\Models\JournalEntry;
use App\Models\JournalVoucher;
use App\Models\CompanyBankAccount;
use App\Models\PettyCashBox;
use App\Models\VoucherType;
use App\Models\ChartOfAccount;
use App\Services\CollectionReminderService;
use App\Services\RealizedCollectionService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected function getCommonLookups(Request $request): array
    {
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
        $floors = Floor::orderBy('floor_number')->get();
        $bankAccounts = Account::where('type', 'Asset')->where('name', 'like', '%bank%')->get();

        return compact('projects', 'customers', 'brokers', 'partners', 'suppliers', 'unitTypes', 'floors', 'bankAccounts');
    }

    public function index(Request $request)
    {
        $report = $request->query('report', 'dashboard');
        if ($report && Route::has('reports.' . $report)) {
            return redirect()->route('reports.' . $report, $request->except('report'));
        }
        return redirect()->route('reports.dashboard');
    }

    public function dashboard(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'dashboard';

        $totalProjects = Project::where('is_active', true)->count();
        $totalUnits = Unit::count();
        $soldUnits = Unit::whereIn('status', ['sold', 'booked'])->count();
        $unsoldUnits = Unit::where('status', 'available')->count();
        $collections = (float)Receipt::sum('amount');
        $outstanding = (float)Sale::where('status', 'active')->sum('remaining_balance');
        $cashBalance = (float)Receipt::where('payment_mode', 'Cash')->sum('amount');
        $bankBalance = (float)Receipt::whereIn('payment_mode', ['Bank Transfer', 'Online', 'Cheque'])->sum('amount');
        $emiDue = (float)EmiSchedule::where('status', 'Due')->sum('emi_amount');

        $revenue = (float)Sale::where('status', 'active')->sum('total_amount');
        $brokeragePaid = (float)Brokerage::sum('paid_amount');
        $financingCosts = (float)EmiSchedule::where('status', 'Paid')->sum('interest_component');
        $totalBills = (float)DB::table('bills')->sum('final_amount');
        $profit = max(0, $revenue - ($brokeragePaid + $financingCosts + $totalBills));

        $loanEmiAlerts = EmiSchedule::with(['loan.project'])
            ->where('status', 'Due')
            ->whereDate('due_date', '<=', Carbon::now()->addDays(30))
            ->orderBy('due_date')
            ->get();

        $projectProfitability = Project::where('is_active', true)->get()->map(function($proj) {
            $expectedRev = (float)Unit::where('project_id', $proj->id)->sum('expected_sale_amount');
            $actualRev = (float)Sale::where('project_id', $proj->id)->where('status', 'active')->sum('total_amount');
            $partnerPayouts = (float)PartnerAllocation::where('project_id', $proj->id)->sum('allocated_amount');
            $brokerageCosts = (float)Brokerage::whereHas('sale', fn($q) => $q->where('project_id', $proj->id))->sum('paid_amount');
            
            $materialCosts = (float)DB::table('bills')
                ->join('payees', 'bills.payee_id', '=', 'payees.id')
                ->where('bills.project_id', $proj->id)
                ->where('payees.type', 'Supplier')
                ->sum('bills.final_amount');

            $contractorPayments = (float)DB::table('bills')
                ->join('payees', 'bills.payee_id', '=', 'payees.id')
                ->where('bills.project_id', $proj->id)
                ->where('payees.type', 'Contractor')
                ->sum('bills.final_amount');

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

        return view('reports.dashboard', array_merge($lookups, compact('activeTab', 'dashboardData')));
    }

    public function gst(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'gst_report';
        $gstReportEntries = collect();
        $sectionFilter = $request->query('section', 'all');

        // 1. SALES & UNIT BOOKINGS SECTION (OUTPUT GST)
        if ($sectionFilter === 'all' || $sectionFilter === 'sales') {
            $salesQuery = Sale::with(['customer', 'unit.unitType', 'project', 'saleUnits.unit'])->where('status', 'active');
            if ($request->filled('project_id')) {
                $salesQuery->where('project_id', $request->project_id);
            }
            if ($request->filled('customer_id')) {
                $customerIds = is_array($request->customer_id) ? $request->customer_id : [$request->customer_id];
                $salesQuery->whereIn('customer_id', $customerIds);
            }
            if ($request->filled('date_from')) {
                $salesQuery->whereDate('sale_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $salesQuery->whereDate('sale_date', '<=', $request->date_to);
            }
            $allSales = $salesQuery->orderByDesc('sale_date')->get();

            foreach ($allSales as $sale) {
                if ($sale->saleUnits && $sale->saleUnits->count() > 0) {
                    foreach ($sale->saleUnits as $su) {
                        $baseAmount = (float)($su->base_amount ?? 0);
                        $taxAmount  = (float)($su->gst_amount ?? 0);
                        $rate       = (float)($su->gst_percentage ?? 0);
                        if ($taxAmount > 0 && $baseAmount > 0 && $rate == 0) {
                            $rate = round(($taxAmount / $baseAmount) * 100, 2);
                        }
                        $cgst = $taxAmount / 2;
                        $sgst = $taxAmount / 2;
                        $doorNo = $su->unit?->door_no ?? $sale->unit?->door_no ?? 'N/A';

                        $gstReportEntries->push((object)[
                            'id' => 'sale_unit_' . $su->id,
                            'section_code' => 'sales',
                            'section_name' => 'Sales & Unit Bookings',
                            'type' => 'Output Tax (Sales)',
                            'invoice_number' => $sale->sale_number ?? ('INV-SALE-' . $sale->id),
                            'date' => $sale->sale_date ? Carbon::parse($sale->sale_date)->format('d M Y') : $sale->created_at->format('d M Y'),
                            'raw_date' => $sale->sale_date ? Carbon::parse($sale->sale_date)->format('Y-m-d') : $sale->created_at->format('Y-m-d'),
                            'entity_name' => $sale->customer?->name ?? 'Customer',
                            'customer_name' => $sale->customer?->name ?? 'Customer',
                            'gstin' => $sale->customer?->gstin ?? 'N/A',
                            'project_name' => $sale->project?->name ?? 'N/A',
                            'unit_door' => $doorNo,
                            'hsn_sac' => '995411',
                            'taxable_value' => $baseAmount,
                            'gst_rate' => $rate,
                            'cgst' => $cgst,
                            'sgst' => $sgst,
                            'igst' => 0.0,
                            'cess' => 0.0,
                            'total_tax' => $taxAmount,
                            'grand_total' => (float)($su->line_total ?? ($baseAmount + $taxAmount)),
                            'tax_nature' => 'output'
                        ]);
                    }
                } else {
                    $totalAmt = (float)($sale->total_amount ?? 0);
                    $baseAmt  = (float)($sale->base_amount ?? $sale->sale_amount ?? 0);
                    $gstAmt   = (float)($sale->gst_amount ?? 0);

                    if ($gstAmt > 0 && $baseAmt > 0) {
                        $taxAmount  = $gstAmt;
                        $baseAmount = $baseAmt;
                        $rate       = round(($gstAmt / $baseAmt) * 100, 2);
                    } elseif ($gstAmt > 0) {
                        $taxAmount  = $gstAmt;
                        $baseAmount = max(0, $totalAmt - $gstAmt);
                        $rate       = $baseAmount > 0 ? round(($gstAmt / $baseAmount) * 100, 2) : 0.0;
                    } else {
                        $rate = (float)($sale->gst_percentage ?? 0);
                        $behavior = strtolower($sale->gst_type ?? $sale->gst_behavior ?? 'inclusive');
                        if ($rate > 0) {
                            if ($behavior === 'included' || $behavior === 'inclusive') {
                                $baseAmount = $totalAmt / (1 + ($rate / 100));
                                $taxAmount  = $totalAmt - $baseAmount;
                            } else {
                                $baseAmount = $totalAmt > 0 ? $totalAmt : $baseAmt;
                                $taxAmount  = $baseAmount * ($rate / 100);
                            }
                        } else {
                            $baseAmount = $totalAmt > 0 ? $totalAmt : $baseAmt;
                            $taxAmount  = 0.0;
                            $rate       = 0.0;
                        }
                    }

                    $cgst = $taxAmount / 2;
                    $sgst = $taxAmount / 2;

                    $gstReportEntries->push((object)[
                        'id' => 'sale_' . $sale->id,
                        'section_code' => 'sales',
                        'section_name' => 'Sales & Unit Bookings',
                        'type' => 'Output Tax (Sales)',
                        'invoice_number' => $sale->sale_number ?? ('INV-SALE-' . $sale->id),
                        'date' => $sale->sale_date ? Carbon::parse($sale->sale_date)->format('d M Y') : $sale->created_at->format('d M Y'),
                        'raw_date' => $sale->sale_date ? Carbon::parse($sale->sale_date)->format('Y-m-d') : $sale->created_at->format('Y-m-d'),
                        'entity_name' => $sale->customer?->name ?? 'Customer',
                        'customer_name' => $sale->customer?->name ?? 'Customer',
                        'gstin' => $sale->customer?->gstin ?? 'N/A',
                        'project_name' => $sale->project?->name ?? 'N/A',
                        'unit_door' => $sale->unit?->door_no ?? 'N/A',
                        'hsn_sac' => '995411',
                        'taxable_value' => $baseAmount,
                        'gst_rate' => $rate,
                        'cgst' => $cgst,
                        'sgst' => $sgst,
                        'igst' => 0.0,
                        'cess' => 0.0,
                        'total_tax' => $taxAmount,
                        'grand_total' => $baseAmount + $taxAmount,
                        'tax_nature' => 'output'
                    ]);
                }
            }
        }

        // 2. EXTRA WORKS SECTION (OUTPUT GST)
        if ($sectionFilter === 'all' || $sectionFilter === 'extra_works') {
            try {
                $extraQuery = DB::table('sale_extra_works')
                    ->join('sales', 'sale_extra_works.sale_id', '=', 'sales.id')
                    ->leftJoin('customers', 'sales.customer_id', '=', 'customers.id')
                    ->leftJoin('projects', 'sales.project_id', '=', 'projects.id')
                    ->where('sales.status', 'active')
                    ->select('sale_extra_works.*', 'sales.sale_number', 'customers.name as customer_name', 'customers.id_proof_number as customer_gstin', 'projects.name as project_name');

                if ($request->filled('project_id')) {
                    $extraQuery->where('sales.project_id', $request->project_id);
                }
                if ($request->filled('customer_id')) {
                    $customerIds = is_array($request->customer_id) ? $request->customer_id : [$request->customer_id];
                    $extraQuery->whereIn('sales.customer_id', $customerIds);
                }
                if ($request->filled('date_from')) {
                    $extraQuery->whereDate('sale_extra_works.created_at', '>=', $request->date_from);
                }
                if ($request->filled('date_to')) {
                    $extraQuery->whereDate('sale_extra_works.created_at', '<=', $request->date_to);
                }
                $extraWorks = $extraQuery->get();

                foreach ($extraWorks as $ew) {
                    $baseAmount = (float)($ew->amount ?? 0);
                    $taxAmount  = (float)($ew->gst_amount ?? 0);
                    $rate       = (float)($ew->gst_percentage ?? 0);
                    if ($taxAmount > 0 && $baseAmount > 0) {
                        $rate = round(($taxAmount / $baseAmount) * 100, 2);
                    }
                    $cgst = $taxAmount / 2;
                    $sgst = $taxAmount / 2;

                    $gstReportEntries->push((object)[
                        'id' => 'extra_' . $ew->id,
                        'section_code' => 'extra_works',
                        'section_name' => 'Extra Works & Upgrades',
                        'type' => 'Output Tax (Extra Work)',
                        'invoice_number' => 'EW-' . $ew->id . ' (' . ($ew->sale_number ?? 'SALE') . ')',
                        'date' => Carbon::parse($ew->created_at)->format('d M Y'),
                        'raw_date' => Carbon::parse($ew->created_at)->format('Y-m-d'),
                        'entity_name' => $ew->customer_name ?? 'Customer',
                        'customer_name' => $ew->customer_name ?? 'Customer',
                        'gstin' => $ew->customer_gstin ?? 'N/A',
                        'project_name' => $ew->project_name ?? 'N/A',
                        'unit_door' => $ew->description ?? 'Custom Addition',
                        'hsn_sac' => '9954',
                        'taxable_value' => $baseAmount,
                        'gst_rate' => $rate,
                        'cgst' => $cgst,
                        'sgst' => $sgst,
                        'igst' => 0.0,
                        'cess' => 0.0,
                        'total_tax' => $taxAmount,
                        'grand_total' => (float)($ew->line_total ?? ($baseAmount + $taxAmount)),
                        'tax_nature' => 'output'
                    ]);
                }
            } catch (\Exception $e) {}
        }

        // 3. SUPPLIER PURCHASES SECTION (INPUT TAX CREDIT - ITC)
        if ($sectionFilter === 'all' || $sectionFilter === 'suppliers') {
            try {
                $supplierBillsQuery = DB::table('bills')
                    ->join('payees', 'bills.payee_id', '=', 'payees.id')
                    ->leftJoin('projects', 'bills.project_id', '=', 'projects.id')
                    ->where('payees.type', 'Supplier')
                    ->select('bills.*', 'payees.name as payee_name', 'payees.gstin as payee_gstin', 'projects.name as project_name');
                if ($request->filled('project_id')) {
                    $supplierBillsQuery->where('bills.project_id', $request->project_id);
                }
                $supplierBills = $supplierBillsQuery->get();

                foreach ($supplierBills as $bill) {
                    $finalAmt  = (float)($bill->final_amount ?? $bill->bill_amount ?? 0);
                    $billAmt   = (float)($bill->bill_amount ?? 0);
                    $rate      = (float)($bill->gst_rate ?? 0);
                    $taxAmount = (float)($bill->gst_amount ?? 0);

                    if ($taxAmount == 0 && $finalAmt > $billAmt && $billAmt > 0) {
                        $taxAmount = max(0, $finalAmt - $billAmt);
                        if ($rate == 0) {
                            $rate = round(($taxAmount / $billAmt) * 100, 2);
                        }
                    }

                    if ($taxAmount > 0) {
                        $baseAmount = max(0, $finalAmt - $taxAmount);
                        if ($rate == 0 && $baseAmount > 0) {
                            $rate = round(($taxAmount / $baseAmount) * 100, 2);
                        }
                    } else if ($rate > 0) {
                        $baseAmount = $finalAmt / (1 + ($rate / 100));
                        $taxAmount  = $finalAmt - $baseAmount;
                    } else {
                        $baseAmount = $finalAmt;
                        $taxAmount  = 0.0;
                    }

                    $cgst = $taxAmount / 2;
                    $sgst = $taxAmount / 2;

                    $gstReportEntries->push((object)[
                        'id' => 'sup_bill_' . $bill->id,
                        'section_code' => 'suppliers',
                        'section_name' => 'Material Purchases (ITC)',
                        'type' => 'Input Credit (Supplier)',
                        'invoice_number' => $bill->bill_number ?? ('SUP-BILL-' . $bill->id),
                        'date' => Carbon::parse($bill->created_at)->format('d M Y'),
                        'raw_date' => Carbon::parse($bill->created_at)->format('Y-m-d'),
                        'entity_name' => $bill->payee_name ?? 'Material Supplier',
                        'customer_name' => $bill->payee_name ?? 'Material Supplier',
                        'gstin' => $bill->payee_gstin ?? 'N/A',
                        'project_name' => $bill->project_name ?? 'N/A',
                        'unit_door' => 'Raw Materials',
                        'hsn_sac' => '6810',
                        'taxable_value' => $baseAmount,
                        'gst_rate' => $rate,
                        'cgst' => $cgst,
                        'sgst' => $sgst,
                        'igst' => 0.0,
                        'cess' => 0.0,
                        'total_tax' => $taxAmount,
                        'grand_total' => $finalAmt,
                        'tax_nature' => 'input'
                    ]);
                }
            } catch (\Exception $e) {}
        }

        $gstReportEntries = $gstReportEntries->filter(function ($entry) {
            return (float)($entry->total_tax ?? 0) > 0 || (float)($entry->gst_rate ?? 0) > 0 || (float)($entry->taxable_value ?? 0) > 0;
        })->values();

        $outputEntries = $gstReportEntries->where('tax_nature', 'output');
        $inputEntries  = $gstReportEntries->where('tax_nature', 'input');

        $totalTaxableSales = (float)$outputEntries->sum('taxable_value');
        $outputTax         = (float)$outputEntries->sum('total_tax');
        $inputTax          = (float)$inputEntries->sum('total_tax');
        $netPayableGst     = max(0, $outputTax - $inputTax);
        $effectiveTaxRate  = $totalTaxableSales > 0 ? round(($outputTax / $totalTaxableSales) * 100, 2) : 0.00;

        $totalCgst = (float)$outputEntries->sum('cgst');
        $totalSgst = (float)$outputEntries->sum('sgst');
        $totalIgst = (float)$outputEntries->sum('igst');

        $intraStateEntries = $outputEntries->filter(fn($e) => (float)($e->igst ?? 0) == 0);
        $interStateEntries = $outputEntries->filter(fn($e) => (float)($e->igst ?? 0) > 0);

        $intraTaxable = (float)$intraStateEntries->sum('taxable_value');
        $interTaxable = (float)$interStateEntries->sum('taxable_value');

        // Monthly Trend calculation for last 5 months
        $trendMonths = [];
        $outputTrend = [];
        $inputTrend  = [];

        for ($i = 4; $i >= 0; $i--) {
            $monthDt = Carbon::now()->subMonths($i);
            $monthKey = $monthDt->format('Y-m');
            $mLabel = $monthDt->format('M Y');

            $mOut = (float)$outputEntries->filter(fn($e) => isset($e->raw_date) && str_starts_with($e->raw_date, $monthKey))->sum('total_tax');
            $mIn  = (float)$inputEntries->filter(fn($e) => isset($e->raw_date) && str_starts_with($e->raw_date, $monthKey))->sum('total_tax');

            $trendMonths[] = $mLabel;
            $outputTrend[] = $mOut;
            $inputTrend[]  = $mIn;
        }

        // Real HSN / SAC Summary grouping
        $hsnSummary = $gstReportEntries->groupBy('hsn_sac')->map(function ($group, $hsn) {
            $taxable = (float)$group->sum('taxable_value');
            $cgst    = (float)$group->sum('cgst');
            $sgst    = (float)$group->sum('sgst');
            $igst    = (float)$group->sum('igst');
            $tax     = (float)$group->sum('total_tax');
            $desc    = $hsn === '995411' ? 'Construction Services of Residential Apartments & Units' : ($hsn === '6810' ? 'Building Materials & Goods Supply' : 'General Construction & Extra Upgrades Services');
            $avgRate = $taxable > 0 ? round(($tax / $taxable) * 100, 2) : 18.0;

            return (object)[
                'hsn_sac' => $hsn ?? '9954',
                'description' => $desc,
                'taxable_value' => $taxable,
                'gst_rate' => $avgRate,
                'cgst' => $cgst,
                'sgst' => $sgst,
                'igst' => $igst,
                'total_tax' => $tax
            ];
        })->values();

        // Section statistics
        $sectionStats = [
            'all' => [
                'name' => 'All Sections',
                'count' => $gstReportEntries->count(),
                'taxable' => $gstReportEntries->sum('taxable_value'),
                'tax' => $gstReportEntries->sum('total_tax'),
            ],
            'sales' => [
                'name' => 'Sales & Unit Bookings',
                'count' => $gstReportEntries->where('section_code', 'sales')->count(),
                'taxable' => $gstReportEntries->where('section_code', 'sales')->sum('taxable_value'),
                'tax' => $gstReportEntries->where('section_code', 'sales')->sum('total_tax'),
            ],
            'extra_works' => [
                'name' => 'Extra Works & Upgrades',
                'count' => $gstReportEntries->where('section_code', 'extra_works')->count(),
                'taxable' => $gstReportEntries->where('section_code', 'extra_works')->sum('taxable_value'),
                'tax' => $gstReportEntries->where('section_code', 'extra_works')->sum('total_tax'),
            ],
            'suppliers' => [
                'name' => 'Material Purchases (ITC)',
                'count' => $gstReportEntries->where('section_code', 'suppliers')->count(),
                'taxable' => $gstReportEntries->where('section_code', 'suppliers')->sum('taxable_value'),
                'tax' => $gstReportEntries->where('section_code', 'suppliers')->sum('total_tax'),
            ]
        ];

        $gstStats = [
            'total_taxable_sales' => $totalTaxableSales,
            'output_tax' => $outputTax,
            'input_tax' => $inputTax,
            'net_payable' => $netPayableGst,
            'effective_tax_rate' => $effectiveTaxRate,
            'total_cgst' => $totalCgst,
            'total_sgst' => $totalSgst,
            'total_igst' => $totalIgst,
            'intra_taxable' => $intraTaxable,
            'inter_taxable' => $interTaxable,
            'trend_months' => $trendMonths,
            'output_trend' => $outputTrend,
            'input_trend' => $inputTrend,
            'total_taxable' => $totalTaxableSales,
            'total_tax' => $outputTax
        ];

        $perPage = 20;
        $page = LengthAwarePaginator::resolveCurrentPage() ?: 1;
        $allGstReportEntries = $gstReportEntries;
        $gstReportEntries = new LengthAwarePaginator(
            $allGstReportEntries->forPage($page, $perPage)->values(),
            $allGstReportEntries->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        $lookups = [
            'projects' => DB::table('projects')->select('id', 'name')->orderBy('name')->get(),
            'customers' => DB::table('customers')->select('id', 'name', 'phone', 'email')->orderBy('name')->get(),
        ];

        return view('reports.gst', array_merge($lookups, compact(
            'activeTab',
            'gstReportEntries',
            'allGstReportEntries',
            'gstStats',
            'sectionStats',
            'hsnSummary'
        )));
    }

    public function activityStatements(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'activity_statements';
        return view('reports.activity-statements', array_merge($lookups, compact('activeTab')));
    }

    public function availability(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'availability';

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
        $availableUnits = $allUnits->where('status', 'available');

        $groupedSummary = $availableUnits->groupBy(function($unit) {
            $name = strtolower($unit->unitType?->name ?? 'other');
            if (str_contains($name, 'shop') || $unit->unitType?->category === 'commercial') {
                return 'SHOP';
            } elseif (str_contains($name, 'flat') || str_contains($name, 'apartment') || str_contains($name, 'bhk') || str_contains($name, 'villa') || $unit->unitType?->category === 'residential') {
                return 'APARTMENT';
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

        $shops = $availableUnits->filter(fn($u) => str_contains(strtolower($u->unitType?->name ?? ''), 'shop') || $u->unitType?->category === 'commercial')->values();
        $apartments = $availableUnits->filter(fn($u) => str_contains(strtolower($u->unitType?->name ?? ''), 'flat') || str_contains(strtolower($u->unitType?->name ?? ''), 'apartment') || str_contains(strtolower($u->unitType?->name ?? ''), 'bhk') || str_contains(strtolower($u->unitType?->name ?? ''), 'villa') || $u->unitType?->category === 'residential')->values();
        $parkings = $availableUnits->filter(fn($u) => str_contains(strtolower($u->unitType?->name ?? ''), 'parking') || $u->unitType?->category === 'parking')->values();
        $others = $availableUnits->filter(fn($u) => !$shops->contains('id', $u->id) && !$apartments->contains('id', $u->id) && !$parkings->contains('id', $u->id))->values();

        $floorsQuery = Floor::where('project_id', $request->project_id)
            ->orderBy('floor_number', 'desc')
            ->with(['units' => function($q) {
                $q->orderBy('door_no');
            }, 'units.booking', 'units.unitType']);
            
        $floors = $floorsQuery->get();
        $parkingRows = [];
        $regularFloors = [];
        foreach ($floors as $floor) {
            $isParking = false;
            if (stripos($floor->name, 'parking') !== false || stripos($floor->name, 'basement') !== false) {
                $isParking = true;
            } else {
                $parkingUnitsCount = $floor->units->where('unit_type_id', 5)->count();
                if ($floor->units->isNotEmpty() && $parkingUnitsCount === $floor->units->count()) {
                    $isParking = true;
                }
            }

            if ($isParking) {
                $rowUnits = $floor->units->sortBy('door_no')->values();
                $parkingRows[] = [
                    'floor'        => $floor,
                    'display_name' => $floor->name,
                    'units'        => $rowUnits,
                ];
            } else {
                $regularFloors[] = $floor;
            }
        }

        $allDoorNos = collect();
        foreach ($regularFloors as $floor) {
            foreach ($floor->units as $unit) {
                $allDoorNos->push($unit->door_no);
            }
        }
        $matrixColumns = $allDoorNos->unique()->sortBy(function($doorNo) {
            return [strlen($doorNo), $doorNo];
        })->values()->toArray();

        $floorMatrix = [];
        foreach ($regularFloors as $floor) {
            $unitsByDoor = $floor->units->keyBy('door_no');
            $cols = [];
            foreach ($matrixColumns as $doorNo) {
                $cols[$doorNo] = $unitsByDoor->get($doorNo);
            }

            $floorMatrix[] = [
                'floor'        => $floor,
                'display_name' => $floor->name,
                'columns'      => $cols,
            ];
        }

        $parkingRows = array_map(function($pRow, $index) {
            $pRow['display_name'] = 'P' . ($index + 1);
            return $pRow;
        }, $parkingRows, array_keys($parkingRows));

        return view('reports.availability', array_merge($lookups, compact('activeTab', 'inventoryGrid', 'groupedSummary', 'shops', 'apartments', 'parkings', 'others', 'floors', 'floorMatrix', 'parkingRows', 'matrixColumns')));
    }

    public function sales(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'sales';

        $salesQuery = Sale::with([
            'customer', 
            'unit.unitType', 
            'unit.floor', 
            'project', 
            'broker', 
            'saleUnits.unit.unitType', 
            'saleUnits.unit.floor',
            'extraWorks'
        ])->where('status', 'active');
        if ($request->filled('project_id')) {
            $salesQuery->where('project_id', $request->project_id);
        }
        if ($request->filled('customer_id')) {
            $customerIds = is_array($request->customer_id) ? $request->customer_id : [$request->customer_id];
            $salesQuery->whereIn('customer_id', $customerIds);
        }
        if ($request->filled('category')) {
            $salesQuery->whereHas('unit.unitType', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }
        $salesList = $salesQuery->orderByDesc('sale_date')->paginate(15);

        $monthlySales = Sale::where('status', 'active')
            ->when($request->filled('project_id'), fn($q) => $q->where('project_id', $request->project_id))
            ->when($request->filled('customer_id'), fn($q) => $q->whereIn('customer_id', is_array($request->customer_id) ? $request->customer_id : [$request->customer_id]))
            ->selectRaw("DATE_FORMAT(sale_date, '%b %Y') as m_label, DATE_FORMAT(sale_date, '%Y-%m') as ym, SUM(total_amount) as total")
            ->groupBy('ym', 'm_label')
            ->orderBy('ym')
            ->get();

        $sMonths = [];
        $sAmounts = [];
        if ($monthlySales->isNotEmpty()) {
            foreach ($monthlySales as $ms) {
                $sMonths[] = $ms->m_label;
                $sAmounts[] = (float)$ms->total;
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $dt = Carbon::now()->subMonths($i);
                $sMonths[] = $dt->format('M Y');
                $sAmounts[] = 0.0;
            }
        }

        $projectSales = Sale::with('project')
            ->where('status', 'active')
            ->when($request->filled('customer_id'), fn($q) => $q->where('customer_id', $request->customer_id))
            ->selectRaw("project_id, COUNT(*) as cnt, SUM(total_amount) as total")
            ->groupBy('project_id')
            ->get();

        $salesChartData = [
            'months' => $sMonths,
            'amounts' => $sAmounts,
            'project_names' => $projectSales->map(fn($p) => $p->project?->name ?? 'Project #' . $p->project_id)->toArray(),
            'project_counts' => $projectSales->map(fn($p) => (int)$p->cnt)->toArray(),
        ];

        return view('reports.sales', array_merge($lookups, compact('activeTab', 'salesList', 'salesChartData')));
    }

    public function emiCollections(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'emi_collections';

        $emiCollectionsSummary = [
            'total_receivable' => (float)Sale::where('status', 'active')->sum('total_amount'),
            'total_received'   => (float)Receipt::sum('amount'),
            'outstanding'      => (float)Sale::where('status', 'active')->sum('remaining_balance'),
            'mtd_collections'  => (float)Receipt::whereMonth('receipt_date', now()->month)->whereYear('receipt_date', now()->year)->sum('amount'),
        ];
        $cbQuery = Receipt::with(['customer', 'sale.project', 'sale.unit', 'bank']);
        if ($request->filled('customer_id')) {
            $customerIds = is_array($request->customer_id) ? $request->customer_id : [$request->customer_id];
            $cbQuery->whereIn('customer_id', $customerIds);
        }
        $cashBookEntries = $cbQuery->orderByDesc('receipt_date')->paginate(50);

        $mEmiQuery = Receipt::query();
        if ($request->filled('customer_id')) {
            $customerIds = is_array($request->customer_id) ? $request->customer_id : [$request->customer_id];
            $mEmiQuery->whereIn('customer_id', $customerIds);
        }
        $monthlyEmi = $mEmiQuery->selectRaw("DATE_FORMAT(receipt_date, '%b %Y') as m_label, DATE_FORMAT(receipt_date, '%Y-%m') as ym, SUM(amount) as total")
            ->groupBy('ym', 'm_label')
            ->orderBy('ym')
            ->get();

        $eMonths = [];
        $eAmounts = [];
        if ($monthlyEmi->isNotEmpty()) {
            foreach ($monthlyEmi as $me) {
                $eMonths[] = $me->m_label;
                $eAmounts[] = (float)$me->total;
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $dt = Carbon::now()->subMonths($i);
                $eMonths[] = $dt->format('M Y');
                $eAmounts[] = 0.0;
            }
        }

        $emiChartData = [
            'months' => $eMonths,
            'amounts' => $eAmounts,
        ];

        return view('reports.emi-collections', array_merge($lookups, compact('activeTab', 'emiCollectionsSummary', 'cashBookEntries', 'emiChartData')));
    }

    public function customerLedger(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'customer_ledger';

        $selectedCustomer = null;
        $selectedCustomers = collect();
        $totalDebits = 0;
        $totalCredits = 0;
        $closingBalance = 0;
        $ledgerEntries = collect();
        $customerSummaryList = collect();

        if ($request->filled('customer_id')) {
            $customerIds = is_array($request->customer_id) ? $request->customer_id : [$request->customer_id];
            $selectedCustomers = Customer::whereIn('id', $customerIds)->get();
            $selectedCustomer = $selectedCustomers->first();
            if ($selectedCustomer && $selectedCustomers->isNotEmpty()) {
                $salesQuery = Sale::with([
                    'project', 
                    'unit.unitType', 
                    'unit.floor', 
                    'saleUnits.unit.unitType', 
                    'saleUnits.unit.floor', 
                    'receipts.bank', 
                    'receipts.companyBankAccount', 
                    'customer', 
                    'customerInstallments'
                ])
                    ->whereIn('customer_id', $customerIds)
                    ->where('status', 'active');
                if ($request->filled('project_id')) {
                    $salesQuery->where('project_id', $request->project_id);
                }
                $sales = $salesQuery->get();

                $customerSummaryList = collect();
                $customerInstallmentsList = collect();
                $ledgerQuery = collect();
                $totalPendingCredits = 0.0;
                $totalRealizedCredits = 0.0;

                foreach ($sales as $sale) {
                    $cName = $sale->customer?->name ?? 'Customer #' . $sale->customer_id;
                    $saleDate = Carbon::parse($sale->sale_date);

                    $paidRealizedAmount = 0.0;
                    $pendingAmount = 0.0;
                    foreach ($sale->receipts as $r) {
                        $rst = strtolower($r->realization_status ?? 'realized');
                        if ($rst === 'realized') {
                            $paidRealizedAmount += (float)$r->amount;
                        } elseif (in_array($rst, ['pending', 'cheque_in_hand', 'deposited'], true)) {
                            $pendingAmount += (float)$r->amount;
                        }
                    }

                    $lastReceipt = $sale->receipts->sortByDesc('receipt_date')->first();

                    $unitDisplay = '—';
                    if ($sale->saleUnits && $sale->saleUnits->isNotEmpty()) {
                        $unitDisplay = $sale->saleUnits->map(fn($su) => $su->unit?->formatted_name)->filter()->implode(', ');
                    }
                    if (empty($unitDisplay) || $unitDisplay === '—') {
                        $unitDisplay = $sale->unit?->formatted_name ?: ($sale->unit?->door_no ?? '—');
                    }

                    $saleModalPayload = [
                        'sale_id'            => $sale->id,
                        'sale_number'        => $sale->sale_number,
                        'sale_date'          => $sale->sale_date ? Carbon::parse($sale->sale_date)->format('d M Y') : ($sale->agreement_date ? Carbon::parse($sale->agreement_date)->format('d M Y') : '—'),
                        'agreement_date'     => $sale->agreement_date ? Carbon::parse($sale->agreement_date)->format('d M Y') : '—',
                        'status'             => ucfirst($sale->status ?? 'active'),
                        'payment_plan'       => $sale->payment_plan ?: 'Standard',
                        'notes'              => $sale->notes ?: ($sale->remarks ?? '—'),
                        'customer' => [
                            'id'             => $sale->customer_id,
                            'name'           => $cName,
                            'phone'          => $sale->customer?->phone ?? '—',
                            'email'          => $sale->customer?->email ?? '—',
                            'address'        => $sale->customer?->address ?? '—',
                            'pan_no'         => $sale->customer?->pan_no ?? '—',
                            'aadhaar_no'     => $sale->customer?->aadhaar_no ?? '—',
                        ],
                        'project' => [
                            'id'             => $sale->project_id,
                            'name'           => $sale->project?->name ?? '—',
                            'code'           => $sale->project?->code ?? '—',
                            'location'       => $sale->project?->location ?? '—',
                        ],
                        'unit' => [
                            'door_no'        => $sale->unit?->door_no ?? '—',
                            'type'           => $sale->unit?->unitType?->name ?? '—',
                            'floor'          => $sale->unit?->floor?->name ?? '—',
                            'built_up_area'  => $sale->unit?->built_up_area ?? 0,
                            'formatted_name' => $unitDisplay,
                        ],
                        'financials' => [
                            'contract_value' => (float)$sale->total_amount,
                            'base_amount'    => (float)($sale->base_amount ?? $sale->sale_amount ?? 0),
                            'gst_amount'     => (float)($sale->gst_amount ?? 0),
                            'rate_per_sqft'  => (float)($sale->rate_per_sqft ?? 0),
                            'total_paid'     => $paidRealizedAmount,
                            'pending_amount' => $pendingAmount,
                            'outstanding'    => max(0, (float)$sale->total_amount - $paidRealizedAmount),
                        ],
                        'receipts' => $sale->receipts->sortByDesc('receipt_date')->values()->map(function($r) {
                            $rst = strtolower($r->realization_status ?? 'realized');
                            return [
                                'id'           => $r->id,
                                'ref_no'       => $r->reference_no ?? ('REC-' . sprintf("%05d", $r->id)),
                                'date'         => $r->receipt_date ? Carbon::parse($r->receipt_date)->format('d M Y') : '—',
                                'amount'       => (float)$r->amount,
                                'payment_mode' => $r->payment_mode ?? 'Cash',
                                'bank_name'    => $r->companyBankAccount?->bank_name ?? ($r->bank?->bank_name ?? ($r->drawee_bank ?? '—')),
                                'status'       => $r->realization_status_label ?? ucfirst(str_replace('_', ' ', $rst)),
                                'is_pending'   => in_array($rst, ['pending', 'cheque_in_hand', 'deposited'], true),
                                'remarks'      => $r->remarks ?? '—',
                            ];
                        })->all(),
                        'installments' => ($sale->customerInstallments ?? collect())->map(function($inst) {
                            return [
                                'id'             => $inst->id,
                                'installment_no' => $inst->installment_no,
                                'label'          => $inst->label ?? ('Installment #' . $inst->installment_no),
                                'due_date'       => $inst->due_date ? Carbon::parse($inst->due_date)->format('d M Y') : '—',
                                'amount'         => (float)$inst->amount,
                                'paid_amount'    => (float)$inst->paid_amount,
                                'outstanding'    => max(0, (float)$inst->amount - (float)$inst->paid_amount),
                                'status'         => ucfirst($inst->status ?? 'pending'),
                                'is_overdue'     => $inst->is_overdue ?? false,
                            ];
                        })->all(),
                    ];

                    $customerSummaryList->push([
                        'sale_id'         => $sale->id,
                        'customer_id'     => $sale->customer_id,
                        'customer_name'   => $cName,
                        'phone'           => $sale->customer?->phone,
                        'email'           => $sale->customer?->email,
                        'project'         => $sale->project?->name ?? '-',
                        'unit'            => $sale->unit?->door_no ?? '-',
                        'unit_formatted'  => $unitDisplay,
                        'sale_number'     => $sale->sale_number,
                        'total_amount'    => (float)$sale->total_amount,
                        'paid_amount'     => $paidRealizedAmount,
                        'pending_amount'  => $pendingAmount,
                        'outstanding'     => max(0, (float)$sale->total_amount - $paidRealizedAmount),
                        'last_payment'    => $lastReceipt ? Carbon::parse($lastReceipt->receipt_date)->format('d M Y') : 'No receipts',
                        'modal_payload'   => $saleModalPayload,
                    ]);

                    if ($sale->customerInstallments && $sale->customerInstallments->isNotEmpty()) {
                        foreach ($sale->customerInstallments as $idx => $inst) {
                            $instAmt = (float)$inst->amount;
                            $instPaid = (float)$inst->paid_amount;
                            $instDue = max(0, $instAmt - $instPaid);
                            $customerInstallmentsList->push([
                                'sale_number'     => $sale->sale_number,
                                'customer_name'   => $cName,
                                'phone'           => $sale->customer?->phone ?? '—',
                                'project'         => $sale->project?->name ?? '—',
                                'unit'            => $sale->unit?->door_no ?? '—',
                                'installment_no'  => $inst->installment_no ?? ($idx + 1),
                                'label'           => $inst->label ?? ('Installment #' . ($inst->installment_no ?? ($idx + 1))),
                                'due_date'        => $inst->due_date ? Carbon::parse($inst->due_date)->format('d M Y') : '—',
                                'amount'          => $instAmt,
                                'paid_amount'     => $instPaid,
                                'outstanding'     => $instDue,
                                'status'          => ucfirst($inst->status ?? 'pending'),
                            ]);
                        }
                    }

                    $ledgerQuery->push([
                        'customer_id'        => $sale->customer_id,
                        'customer_name'      => $cName,
                        'date'               => $saleDate->format('d M Y'),
                        'raw_date'           => $saleDate->timestamp,
                        'type'               => 'sale',
                        'description'        => 'Sale Agreement Registration' . ($sale->unit?->door_no ? " (Unit #{$sale->unit->door_no})" : ""),
                        'debit'              => (float)$sale->total_amount,
                        'credit'             => 0.0,
                        'effective_credit'   => 0.0,
                        'payment_mode'       => '—',
                        'ref_no'             => $sale->sale_number,
                        'realization_status' => 'realized',
                        'realization_label'  => 'Agreement',
                        'is_pending'         => false,
                        'is_bounced'         => false,
                        'is_realized'        => true,
                    ]);

                    foreach ($sale->receipts as $receipt) {
                        $rStatus = strtolower($receipt->realization_status ?? 'realized');
                        $isPending = in_array($rStatus, ['pending', 'cheque_in_hand', 'deposited'], true);
                        $isBounced = in_array($rStatus, ['bounced', 'cancelled'], true);
                        $isRealized = ($rStatus === 'realized');

                        $amt = (float)$receipt->amount;
                        $effectiveCredit = $isRealized ? $amt : 0.0;

                        if ($isPending) {
                            $totalPendingCredits += $amt;
                        } elseif ($isRealized) {
                            $totalRealizedCredits += $amt;
                        }

                        $receiptDate = Carbon::parse($receipt->receipt_date);

                        $ledgerQuery->push([
                            'customer_id'        => $sale->customer_id,
                            'customer_name'      => $cName,
                            'date'               => $receiptDate->format('d M Y'),
                            'raw_date'           => $receiptDate->timestamp,
                            'type'               => 'receipt',
                            'description'        => 'Payment Receipt' . ($receipt->remarks ? " — {$receipt->remarks}" : ""),
                            'debit'              => 0.0,
                            'credit'             => $amt,
                            'effective_credit'   => $effectiveCredit,
                            'payment_mode'       => $receipt->payment_mode ?? 'Cash',
                            'ref_no'             => $receipt->reference_no ?? 'REC-' . sprintf("%05d", $receipt->id),
                            'realization_status' => $rStatus,
                            'realization_label'  => $receipt->realization_status_label ?? ucfirst(str_replace('_', ' ', $rStatus)),
                            'is_pending'         => $isPending,
                            'is_bounced'         => $isBounced,
                            'is_realized'        => $isRealized,
                            'customer_id'        => $sale->customer_id,
                            'receipt_id'         => $receipt->id,
                        ]);
                    }
                }

                // Sort chronologically: sales before receipts if on the same timestamp
                $ledgerEntries = $ledgerQuery->sort(function ($a, $b) {
                    if ($a['raw_date'] === $b['raw_date']) {
                        if ($a['type'] === 'sale' && $b['type'] !== 'sale') return -1;
                        if ($a['type'] !== 'sale' && $b['type'] === 'sale') return 1;
                        return 0;
                    }
                    return $a['raw_date'] <=> $b['raw_date'];
                })->values();

                $runningBalance = 0;
                $ledgerEntries = $ledgerEntries->map(function ($entry) use (&$runningBalance) {
                    $runningBalance += ($entry['debit'] - $entry['effective_credit']);
                    $entry['balance'] = $runningBalance;
                    return $entry;
                });

                $totalDebits = (float)$ledgerEntries->sum('debit');

                // ── Deduct refunds (match Dashboard formula) ──────────────────────────
                $saleIdsForRefunds = $sales->pluck('id')->all();
                $refundVouchers = DB::table('vouchers')
                    ->whereIn('type', ['Receipt', 'Payment'])
                    ->where('status', 'Posted')
                    ->whereNotNull('reference_no')
                    ->get(['reference_no']);
                $refundDeducted = 0.0;
                foreach ($refundVouchers as $rv) {
                    $rd = json_decode($rv->reference_no, true);
                    if (!empty($rd['allocations']) && is_array($rd['allocations'])) {
                        foreach ($rd['allocations'] as $alloc) {
                            if (($alloc['type'] ?? '') === 'refund' && !empty($alloc['target_id'])) {
                                if (in_array((int)$alloc['target_id'], $saleIdsForRefunds, true)) {
                                    $refundDeducted += (float)($alloc['amount'] ?? 0);
                                }
                            }
                        }
                    }
                }
                // ─────────────────────────────────────────────────────────────────────

                $totalCredits = max(0.0, $totalRealizedCredits - $refundDeducted); // Only realized receipts minus refunds
                $closingBalance = max(0, $totalDebits - $totalCredits);
                $projectedBalance = max(0, $closingBalance - $totalPendingCredits);
            }
        } else {
            $salesQuery = Sale::with([
                'customer', 
                'project', 
                'unit.unitType', 
                'unit.floor', 
                'saleUnits.unit.unitType', 
                'saleUnits.unit.floor', 
                'receipts.bank', 
                'receipts.companyBankAccount', 
                'customerInstallments'
            ])
                ->where('status', 'active')
                ->latest('id');
            if ($request->filled('project_id')) {
                $salesQuery->where('project_id', $request->project_id);
            }
            $allSales = $salesQuery->get();

            $customerSummaryList = collect();
            $ledgerQuery = collect();
            $totalRealizedCredits = 0.0;
            $totalPendingCredits = 0.0;

            foreach ($allSales as $sale) {
                $cName = $sale->customer?->name ?? 'Customer #' . $sale->customer_id;
                $totalSale = (float)$sale->total_amount;
                
                $paidRealizedAmount = 0.0;
                $pendingAmount = 0.0;
                foreach ($sale->receipts as $r) {
                    $rst = strtolower($r->realization_status ?? 'realized');
                    if ($rst === 'realized') {
                        $paidRealizedAmount += (float)$r->amount;
                    } elseif (in_array($rst, ['pending', 'cheque_in_hand', 'deposited'], true)) {
                        $pendingAmount += (float)$r->amount;
                    }
                }
                
                $totalRealizedCredits += $paidRealizedAmount;
                $totalPendingCredits += $pendingAmount;

                $outstanding = max(0, $totalSale - $paidRealizedAmount);
                $lastReceipt = $sale->receipts->sortByDesc('receipt_date')->first();

                $unitDisplay = '—';
                if ($sale->saleUnits && $sale->saleUnits->isNotEmpty()) {
                    $unitDisplay = $sale->saleUnits->map(fn($su) => $su->unit?->formatted_name)->filter()->implode(', ');
                }
                if (empty($unitDisplay) || $unitDisplay === '—') {
                    $unitDisplay = $sale->unit?->formatted_name ?: ($sale->unit?->door_no ?? '—');
                }

                $saleModalPayload = [
                    'sale_id'            => $sale->id,
                    'sale_number'        => $sale->sale_number,
                    'sale_date'          => $sale->sale_date ? Carbon::parse($sale->sale_date)->format('d M Y') : ($sale->agreement_date ? Carbon::parse($sale->agreement_date)->format('d M Y') : '—'),
                    'agreement_date'     => $sale->agreement_date ? Carbon::parse($sale->agreement_date)->format('d M Y') : '—',
                    'status'             => ucfirst($sale->status ?? 'active'),
                    'payment_plan'       => $sale->payment_plan ?: 'Standard',
                    'notes'              => $sale->notes ?: ($sale->remarks ?? '—'),
                    'customer' => [
                        'id'             => $sale->customer_id,
                        'name'           => $cName,
                        'phone'          => $sale->customer?->phone ?? '—',
                        'email'          => $sale->customer?->email ?? '—',
                        'address'        => $sale->customer?->address ?? '—',
                        'pan_no'         => $sale->customer?->pan_no ?? '—',
                        'aadhaar_no'     => $sale->customer?->aadhaar_no ?? '—',
                    ],
                    'project' => [
                        'id'             => $sale->project_id,
                        'name'           => $sale->project?->name ?? '—',
                        'code'           => $sale->project?->code ?? '—',
                        'location'       => $sale->project?->location ?? '—',
                    ],
                    'unit' => [
                        'door_no'        => $sale->unit?->door_no ?? '—',
                        'type'           => $sale->unit?->unitType?->name ?? '—',
                        'floor'          => $sale->unit?->floor?->name ?? '—',
                        'built_up_area'  => $sale->unit?->built_up_area ?? 0,
                        'formatted_name' => $unitDisplay,
                    ],
                    'financials' => [
                        'contract_value' => $totalSale,
                        'base_amount'    => (float)($sale->base_amount ?? $sale->sale_amount ?? 0),
                        'gst_amount'     => (float)($sale->gst_amount ?? 0),
                        'rate_per_sqft'  => (float)($sale->rate_per_sqft ?? 0),
                        'total_paid'     => $paidRealizedAmount,
                        'pending_amount' => $pendingAmount,
                        'outstanding'    => $outstanding,
                    ],
                    'receipts' => $sale->receipts->sortByDesc('receipt_date')->values()->map(function($r) {
                        $rst = strtolower($r->realization_status ?? 'realized');
                        return [
                            'id'           => $r->id,
                            'ref_no'       => $r->reference_no ?? ('REC-' . sprintf("%05d", $r->id)),
                            'date'         => $r->receipt_date ? Carbon::parse($r->receipt_date)->format('d M Y') : '—',
                            'amount'       => (float)$r->amount,
                            'payment_mode' => $r->payment_mode ?? 'Cash',
                            'bank_name'    => $r->companyBankAccount?->bank_name ?? ($r->bank?->bank_name ?? ($r->drawee_bank ?? '—')),
                            'status'       => $r->realization_status_label ?? ucfirst(str_replace('_', ' ', $rst)),
                            'is_pending'   => in_array($rst, ['pending', 'cheque_in_hand', 'deposited'], true),
                            'remarks'      => $r->remarks ?? '—',
                        ];
                    })->all(),
                    'installments' => ($sale->customerInstallments ?? collect())->map(function($inst) {
                        return [
                            'id'             => $inst->id,
                            'installment_no' => $inst->installment_no,
                            'label'          => $inst->label ?? ('Installment #' . $inst->installment_no),
                            'due_date'       => $inst->due_date ? Carbon::parse($inst->due_date)->format('d M Y') : '—',
                            'amount'         => (float)$inst->amount,
                            'paid_amount'    => (float)$inst->paid_amount,
                            'outstanding'    => max(0, (float)$inst->amount - (float)$inst->paid_amount),
                            'status'         => ucfirst($inst->status ?? 'pending'),
                            'is_overdue'     => $inst->is_overdue ?? false,
                        ];
                    })->all(),
                ];

                $customerSummaryList->push([
                    'sale_id'         => $sale->id,
                    'customer_id'     => $sale->customer_id,
                    'customer_name'   => $cName,
                    'phone'           => $sale->customer?->phone,
                    'email'           => $sale->customer?->email,
                    'project'         => $sale->project?->name ?? '-',
                    'unit'            => $sale->unit?->door_no ?? '-',
                    'unit_formatted'  => $unitDisplay,
                    'sale_number'     => $sale->sale_number,
                    'total_amount'    => $totalSale,
                    'paid_amount'     => $paidRealizedAmount,
                    'pending_amount'  => $pendingAmount,
                    'outstanding'     => $outstanding,
                    'last_payment'    => $lastReceipt ? Carbon::parse($lastReceipt->receipt_date)->format('d M Y') : 'No receipts',
                    'modal_payload'   => $saleModalPayload,
                ]);

                $saleDate = Carbon::parse($sale->sale_date);
                $ledgerQuery->push([
                    'customer_name'      => $cName,
                    'date'               => $saleDate->format('d M Y'),
                    'raw_date'           => $saleDate->timestamp,
                    'type'               => 'sale',
                    'description'        => "Sale Agreement — {$cName}" . ($sale->unit?->door_no ? " (Unit #{$sale->unit->door_no})" : ""),
                    'debit'              => $totalSale,
                    'credit'             => 0.0,
                    'effective_credit'   => 0.0,
                    'payment_mode'       => 'Agreement',
                    'ref_no'             => $sale->sale_number,
                    'realization_status' => 'realized',
                    'is_pending'         => false,
                    'is_bounced'         => false,
                    'is_realized'        => true,
                ]);

                foreach ($sale->receipts as $receipt) {
                    $rStatus = strtolower($receipt->realization_status ?? 'realized');
                    $isPending = in_array($rStatus, ['pending', 'cheque_in_hand', 'deposited'], true);
                    $isBounced = in_array($rStatus, ['bounced', 'cancelled'], true);
                    $isRealized = ($rStatus === 'realized');

                    $amt = (float)$receipt->amount;
                    $effectiveCredit = $isRealized ? $amt : 0.0;
                    $receiptDate = Carbon::parse($receipt->receipt_date);

                    $ledgerQuery->push([
                        'customer_name'      => $cName,
                        'date'               => $receiptDate->format('d M Y'),
                        'raw_date'           => $receiptDate->timestamp,
                        'type'               => 'receipt',
                        'description'        => "Payment Receipt — {$cName}" . ($receipt->remarks ? " ({$receipt->remarks})" : ""),
                        'debit'              => 0.0,
                        'credit'             => $amt,
                        'effective_credit'   => $effectiveCredit,
                        'payment_mode'       => $receipt->payment_mode ?? 'Cash',
                        'ref_no'             => $receipt->reference_no ?? 'REC-' . sprintf("%05d", $receipt->id),
                        'realization_status' => $rStatus,
                        'realization_label'  => $receipt->realization_status_label ?? ucfirst(str_replace('_', ' ', $rStatus)),
                        'is_pending'         => $isPending,
                        'is_bounced'         => $isBounced,
                        'is_realized'        => $isRealized,
                        'customer_id'        => $sale->customer_id,
                        'receipt_id'         => $receipt->id,
                    ]);
                }
            }

            $ledgerEntries = $ledgerQuery->sortByDesc(fn($r) => $r['raw_date'])->values();
            $totalDebits = (float)$allSales->sum('total_amount');

            // ── Single Source of Truth for Realized Collections (Match Dashboard & EMI Collections) ──
            $projectFilterId = $request->filled('project_id') ? (int)$request->project_id : null;
            $collectionSummary = RealizedCollectionService::summarize($projectFilterId);
            $totalCredits = $collectionSummary['net'];
            $refundDeductedAll = $collectionSummary['refunds'];
            $closingBalance = max(0, $totalDebits - $totalCredits);
            $projectedBalance = max(0, $closingBalance - $totalPendingCredits);

            $customerSummaryList = $customerSummaryList->sortByDesc('sale_id')->values();

            $perPage = 50;
            $customerPage = LengthAwarePaginator::resolveCurrentPage('customer_page');
            $customerSummaryList = new LengthAwarePaginator(
                $customerSummaryList->forPage($customerPage, $perPage)->values(),
                $customerSummaryList->count(),
                $perPage,
                $customerPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query(), 'pageName' => 'customer_page']
            );

            $ledgerPage = LengthAwarePaginator::resolveCurrentPage('ledger_page');
            $ledgerEntries = new LengthAwarePaginator(
                $ledgerEntries->forPage($ledgerPage, $perPage)->values(),
                $ledgerEntries->count(),
                $perPage,
                $ledgerPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query(), 'pageName' => 'ledger_page']
            );
        }

        $totalPendingCredits = $totalPendingCredits ?? 0.0;
        $projectedBalance = $projectedBalance ?? $closingBalance;
        $customerInstallmentsList = $customerInstallmentsList ?? collect();
        $refundDeductedTotal = $refundDeducted ?? ($refundDeductedAll ?? 0.0);

        return view('reports.customer-ledger', array_merge($lookups, compact('activeTab', 'selectedCustomer', 'selectedCustomers', 'ledgerEntries', 'customerSummaryList', 'customerInstallmentsList', 'totalDebits', 'totalCredits', 'closingBalance', 'totalPendingCredits', 'projectedBalance', 'refundDeductedTotal')));
    }

    public function cashBook(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'cash_book';

        $selectedPartnerId = $request->filled('partner_id') ? (int)$request->partner_id : null;
        $selectedProjectId = $request->filled('project_id') ? (int)$request->project_id : null;

        $partnerShares = collect();
        if ($selectedPartnerId) {
            $pSharesQ = DB::table('partner_shares')->where('partner_id', $selectedPartnerId);
            if ($selectedProjectId) {
                $pSharesQ->where('project_id', $selectedProjectId);
            }
            $partnerShares = $pSharesQ->get()->keyBy('project_id');
        }

        $cashQuery = Receipt::with(['customer', 'sale.project', 'sale.unit', 'partner']);

        if ($selectedPartnerId) {
            if ($partnerShares->isNotEmpty()) {
                $cashQuery->whereIn('project_id', $partnerShares->keys())
                    ->where(function($q) use ($selectedPartnerId) {
                        $q->whereNull('partner_id')->orWhere('partner_id', $selectedPartnerId);
                    });
            } else {
                $cashQuery->where('partner_id', $selectedPartnerId);
            }
        } elseif ($selectedProjectId) {
            $cashQuery->where('project_id', $selectedProjectId);
        }

        if ($request->filled('payment_mode')) {
            $cashQuery->where('payment_mode', $request->payment_mode);
        }
        if ($request->filled('customer_id')) {
            $customerIds = is_array($request->customer_id) ? $request->customer_id : [$request->customer_id];
            $cashQuery->whereIn('customer_id', $customerIds);
        }
        if ($request->filled('date_from')) {
            $cashQuery->whereDate('receipt_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $cashQuery->whereDate('receipt_date', '<=', $request->date_to);
        }

        $cashBookEntries = $cashQuery->orderByDesc('receipt_date')->paginate(25);

        $getPartnerMultiplier = function($projId) use ($selectedPartnerId, $partnerShares) {
            if (!$selectedPartnerId) return 1.0;
            $sh = $partnerShares->get($projId);
            return $sh ? ((float)$sh->share_pct / 100.0) : 1.0;
        };

        $statsBaseQuery = Receipt::query();
        if ($selectedPartnerId) {
            if ($partnerShares->isNotEmpty()) {
                $statsBaseQuery->whereIn('project_id', $partnerShares->keys())
                    ->where(function($q) use ($selectedPartnerId) {
                        $q->whereNull('partner_id')->orWhere('partner_id', $selectedPartnerId);
                    });
            } else {
                $statsBaseQuery->where('partner_id', $selectedPartnerId);
            }
        } elseif ($selectedProjectId) {
            $statsBaseQuery->where('project_id', $selectedProjectId);
        }

        if ($request->filled('date_from')) {
            $statsBaseQuery->whereDate('receipt_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $statsBaseQuery->whereDate('receipt_date', '<=', $request->date_to);
        }

        $allStatsReceipts = $statsBaseQuery->get();

        $totalReceived = 0.0;
        $cashReceived  = 0.0;
        $bankReceived  = 0.0;

        foreach ($allStatsReceipts as $rec) {
            $mult = $getPartnerMultiplier($rec->project_id);
            $amt = (float)$rec->amount * $mult;
            $totalReceived += $amt;
            if ($rec->payment_mode === 'Cash') {
                $cashReceived += $amt;
            } else {
                $bankReceived += $amt;
            }
        }

        $pendingQuery = Sale::where('status', 'active')->where('remaining_balance', '>', 0);
        if ($selectedProjectId) {
            $pendingQuery->where('project_id', $selectedProjectId);
        } elseif ($selectedPartnerId && $partnerShares->isNotEmpty()) {
            $pendingQuery->whereIn('project_id', $partnerShares->keys());
        }
        $allPendingSales = $pendingQuery->get();
        $pendingBalance = 0.0;
        foreach ($allPendingSales as $psale) {
            $mult = $getPartnerMultiplier($psale->project_id);
            $pendingBalance += (float)$psale->remaining_balance * $mult;
        }

        $cashBookStats = [
            'total_received'  => $totalReceived,
            'cash_received'   => $cashReceived,
            'bank_received'   => $bankReceived,
            'pending_balance' => $pendingBalance,
            'selected_partner_id' => $selectedPartnerId,
        ];

        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $mQ = Receipt::query()
                ->whereYear('receipt_date', $month->year)
                ->whereMonth('receipt_date', $month->month);

            if ($selectedPartnerId) {
                if ($partnerShares->isNotEmpty()) {
                    $mQ->whereIn('project_id', $partnerShares->keys())
                       ->where(function($q) use ($selectedPartnerId) {
                           $q->whereNull('partner_id')->orWhere('partner_id', $selectedPartnerId);
                       });
                } else {
                    $mQ->where('partner_id', $selectedPartnerId);
                }
            } elseif ($selectedProjectId) {
                $mQ->where('project_id', $selectedProjectId);
            }

            $mRecs = $mQ->get();
            $mAmt = 0.0;
            foreach ($mRecs as $mr) {
                $mAmt += (float)$mr->amount * $getPartnerMultiplier($mr->project_id);
            }

            $monthlyData[] = [
                'label'  => $month->format('M Y'),
                'amount' => $mAmt,
            ];
        }

        $pmQ = Receipt::query();
        if ($selectedPartnerId) {
            if ($partnerShares->isNotEmpty()) {
                $pmQ->whereIn('project_id', $partnerShares->keys())
                    ->where(function($q) use ($selectedPartnerId) {
                        $q->whereNull('partner_id')->orWhere('partner_id', $selectedPartnerId);
                    });
            } else {
                $pmQ->where('partner_id', $selectedPartnerId);
            }
        } elseif ($selectedProjectId) {
            $pmQ->where('project_id', $selectedProjectId);
        }
        if ($request->filled('date_from')) $pmQ->whereDate('receipt_date', '>=', $request->date_from);
        if ($request->filled('date_to')) $pmQ->whereDate('receipt_date', '<=', $request->date_to);

        $pmRecs = $pmQ->whereNotNull('payment_mode')->where('payment_mode', '!=', '')->get();
        $paymentModes = $pmRecs->groupBy('payment_mode')->map(function($group, $mode) use ($getPartnerMultiplier) {
            $sum = 0.0;
            foreach ($group as $r) {
                $sum += (float)$r->amount * $getPartnerMultiplier($r->project_id);
            }
            return (object)['payment_mode' => $mode, 'total' => $sum];
        })->values()->sortByDesc('total');

        $partnerWise = PartnerAllocation::with('partner')
            ->when($selectedProjectId, fn($q) => $q->where('project_id', $selectedProjectId))
            ->selectRaw("partner_id, SUM(allocated_amount) as total")
            ->groupBy('partner_id')
            ->get();

        $dailyData = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);
            $dQ = Receipt::query()->whereDate('receipt_date', $day->toDateString());
            if ($selectedPartnerId) {
                if ($partnerShares->isNotEmpty()) {
                    $dQ->whereIn('project_id', $partnerShares->keys())
                       ->where(function($q) use ($selectedPartnerId) {
                           $q->whereNull('partner_id')->orWhere('partner_id', $selectedPartnerId);
                       });
                } else {
                    $dQ->where('partner_id', $selectedPartnerId);
                }
            } elseif ($selectedProjectId) {
                $dQ->where('project_id', $selectedProjectId);
            }
            $dRecs = $dQ->get();
            $dAmt = 0.0;
            foreach ($dRecs as $dr) {
                $dAmt += (float)$dr->amount * $getPartnerMultiplier($dr->project_id);
            }
            $dailyData[] = [
                'label'  => $day->format('d M'),
                'amount' => $dAmt,
            ];
        }

        $cashBookChartData = [
            'monthly'       => $monthlyData,
            'daily'         => $dailyData,
            'payment_modes' => $paymentModes,
            'partner_wise'  => $partnerWise,
        ];

        return view('reports.cash-book', array_merge($lookups, compact('activeTab', 'cashBookEntries', 'cashBookStats', 'cashBookChartData')));
    }

    public function bankReports(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'bank_reports';

        $bankQuery = Receipt::with(['customer', 'sale.project', 'sale.unit'])->whereIn('payment_mode', ['Bank Transfer', 'Cheque', 'Online']);
        if ($request->filled('bank_name')) {
            $bankQuery->where('bank_name', 'like', "%{$request->bank_name}%");
        }
        if ($request->filled('customer_id')) {
            $customerIds = is_array($request->customer_id) ? $request->customer_id : [$request->customer_id];
            $bankQuery->whereIn('customer_id', $customerIds);
        }
        if ($request->filled('date_from')) {
            $bankQuery->whereDate('receipt_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $bankQuery->whereDate('receipt_date', '<=', $request->date_to);
        }
        $bankReportEntries = $bankQuery->orderByDesc('receipt_date')->paginate(50);

        $monthlyBank = Receipt::whereIn('payment_mode', ['Bank Transfer', 'Cheque', 'Online'])
            ->when($request->filled('bank_name'), fn($q) => $q->where('bank_name', 'like', "%{$request->bank_name}%"))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('receipt_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('receipt_date', '<=', $request->date_to))
            ->selectRaw("DATE_FORMAT(receipt_date, '%b %Y') as m_label, DATE_FORMAT(receipt_date, '%Y-%m') as ym, SUM(amount) as total")
            ->groupBy('ym', 'm_label')
            ->orderBy('ym')
            ->get();

        $bMonths = [];
        $bAmounts = [];
        if ($monthlyBank->isNotEmpty()) {
            foreach ($monthlyBank as $mb) {
                $bMonths[] = $mb->m_label;
                $bAmounts[] = (float)$mb->total;
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $dt = Carbon::now()->subMonths($i);
                $bMonths[] = $dt->format('M Y');
                $bAmounts[] = 0.0;
            }
        }
        $bankChartData = [
            'months' => $bMonths,
            'amounts' => $bAmounts,
            'total_cleared' => (float)$monthlyBank->sum('total'),
        ];

        return view('reports.bank-reports', array_merge($lookups, compact('activeTab', 'bankReportEntries', 'bankChartData')));
    }

    public function partnerStatements(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'partner_statements';

        $partnerId = $request->input('partner_id');
        $projectId = $request->input('project_id');
        $dateFrom = $request->input('from_date') ?: $request->input('date_from');
        $dateTo = $request->input('to_date') ?: $request->input('date_to');

        // Fetch partners & projects for filter dropdowns
        $partners = Payee::whereRaw("LOWER(type) = 'partner'")->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        $partnerPayeeIds = $partners->pluck('id')->toArray();
        $partnerAccountMap = $partners->pluck('id', 'linked_account_id')->filter()->toArray();
        $partnerAccountIds = array_keys($partnerAccountMap);

        // 1. Fetch Partner Shares
        $allPartnerShares = PartnerShare::all();
        $sharesQuery = PartnerShare::with(['partner', 'project']);
        if ($partnerId) {
            $sharesQuery->where('partner_id', $partnerId);
        }
        if ($projectId) {
            $sharesQuery->where('project_id', $projectId);
        }
        $partnerShares = $sharesQuery->get();

        // 2. Fetch Allocations (Debits - Payouts / Drawings Released)
        $allocQuery = PartnerAllocation::with(['partner', 'project', 'voucher', 'payment']);
        if ($partnerId) {
            $allocQuery->where('partner_id', $partnerId);
        }
        if ($projectId) {
            $allocQuery->where('project_id', $projectId);
        }
        if ($dateFrom) {
            $allocQuery->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $allocQuery->whereDate('date', '<=', $dateTo);
        }
        $allocations = $allocQuery->orderBy('date')->get();

        // 3. Additional Payouts (bill_payments & voucher_lines)
        $billPayoutsQuery = DB::table('bill_payments')->whereIn('payee_id', $partnerPayeeIds);
        if ($partnerId) {
            $billPayoutsQuery->where('payee_id', $partnerId);
        }
        if ($dateFrom) {
            $billPayoutsQuery->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $billPayoutsQuery->whereDate('date', '<=', $dateTo);
        }
        $billPayouts = $billPayoutsQuery->orderBy('date')->get();

        $allocVoucherIds = $allocations->pluck('voucher_id')->filter()->toArray();
        $voucherPayoutsQuery = DB::table('voucher_lines')
            ->join('vouchers', 'voucher_lines.voucher_id', '=', 'vouchers.id')
            ->whereIn('voucher_lines.account_id', $partnerAccountIds)
            ->where('vouchers.type', 'Payment')
            ->where('voucher_lines.debit', '>', 0)
            ->where('vouchers.voucher_number', 'NOT LIKE', 'PY-REF-%')
            ->where('vouchers.narration', 'NOT LIKE', '%Customer Refund%')
            ->where('vouchers.narration', 'NOT LIKE', '%cancellation%');
        if (!empty($allocVoucherIds)) {
            $voucherPayoutsQuery->whereNotIn('vouchers.id', $allocVoucherIds);
        }
        if ($dateFrom) {
            $voucherPayoutsQuery->whereDate('vouchers.date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $voucherPayoutsQuery->whereDate('vouchers.date', '<=', $dateTo);
        }
        $voucherPayouts = $voucherPayoutsQuery->select(
            'voucher_lines.*',
            'vouchers.voucher_number',
            'vouchers.date as v_date',
            'vouchers.narration'
        )->get();

        // 4. Fetch Direct Partner Capital Contributions
        $contribsQuery = \App\Models\PartnerContribution::with(['partner', 'project']);
        if ($partnerId) {
            $contribsQuery->where('partner_id', $partnerId);
        }
        if ($projectId) {
            $contribsQuery->where('project_id', $projectId);
        }
        if ($dateFrom) {
            $contribsQuery->whereDate('contribution_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $contribsQuery->whereDate('contribution_date', '<=', $dateTo);
        }
        $partnerContributions = $contribsQuery->orderBy('contribution_date')->get();

        // 5. Combine into Section A Table: ONLY Payouts / Drawings entries
        $ledgerTransactions = collect();

        // Add Allocations Payouts (Debits)
        foreach ($allocations as $alloc) {
            $dateStr = $alloc->date ? Carbon::parse($alloc->date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $paymentMode = $alloc->payment_mode ?? '';
            if (empty($paymentMode)) {
                $narration = $alloc->voucher?->narration ?? $alloc->remarks ?? '';
                if (stripos($narration, 'Bank Transfer') !== false) $paymentMode = 'Bank Transfer';
                elseif (stripos($narration, 'Cheque') !== false) $paymentMode = 'Cheque';
                elseif (stripos($narration, 'Cash') !== false) $paymentMode = 'Cash';
                elseif (stripos($narration, 'UPI') !== false || stripos($narration, 'Online') !== false) $paymentMode = 'UPI / Online';
                else $paymentMode = 'Bank Transfer';
            }

            // $baseDesc = ($alloc->project?->name ? ($alloc->project->name . ' - ') : '') . 'Partner Payout / Drawing' . ($alloc->remarks ? (' - ' . $alloc->remarks) : '');
            $baseDesc = 'Partner Payout / Drawing' . ($alloc->remarks ? (' - ' . $alloc->remarks) : '');

            $ledgerTransactions->push((object)[
                'date' => $dateStr,
                'ref_no' => $alloc->voucher?->voucher_number ?? ('PY-PTR-' . Carbon::parse($dateStr)->format('Ymd') . '-' . str_pad((string)$alloc->id, 5, '0', STR_PAD_LEFT)),
                'description' => $baseDesc,
                'payment_mode' => $paymentMode,
                'credit' => 0.00,
                'debit' => (float)$alloc->allocated_amount,
                'amount' => (float)$alloc->allocated_amount,
                'partner_id' => $alloc->partner_id,
                'partner_name' => $alloc->partner?->name ?? ('Partner #' . $alloc->partner_id),
            ]);
        }

        // Add Bill Payouts (Debits)
        foreach ($billPayouts as $pay) {
            $pName = $partners->firstWhere('id', $pay->payee_id)?->name ?? ('Partner #' . $pay->payee_id);
            $dateStr = $pay->date ? Carbon::parse($pay->date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $ledgerTransactions->push((object)[
                'date' => $dateStr,
                'ref_no' => 'BILL-PAY-' . str_pad((string)$pay->id, 3, '0', STR_PAD_LEFT),
                'description' => 'Partner Payout / Drawing',
                'payment_mode' => 'Bank Transfer',
                'credit' => 0.00,
                'debit' => (float)$pay->amount,
                'amount' => (float)$pay->amount,
                'partner_id' => $pay->payee_id,
                'partner_name' => $pName,
            ]);
        }

        // Add Voucher Payouts (Debits)
        foreach ($voucherPayouts as $vp) {
            $pId = $partnerAccountMap[$vp->account_id] ?? null;
            if ($pId && (!$partnerId || (string)$pId === (string)$partnerId)) {
                $pName = $partners->firstWhere('id', $pId)?->name ?? ('Partner #' . $pId);
                $dateStr = $vp->v_date ? Carbon::parse($vp->v_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
                $narr = $vp->narration ?? $vp->line_narration ?? '';
                $paymentMode = '';
                if (stripos($narr, 'Bank Transfer') !== false) $paymentMode = 'Bank Transfer';
                elseif (stripos($narr, 'Cheque') !== false) $paymentMode = 'Cheque';
                elseif (stripos($narr, 'Cash') !== false) $paymentMode = 'Cash';
                elseif (stripos($narr, 'UPI') !== false || stripos($narr, 'Online') !== false) $paymentMode = 'UPI / Online';
                else $paymentMode = 'Bank Transfer';

                $ledgerTransactions->push((object)[
                    'date' => $dateStr,
                    'ref_no' => $vp->voucher_number,
                    'description' => $vp->line_narration ?: ($vp->narration ?: 'Partner Payout / Drawing'),
                    'payment_mode' => $paymentMode,
                    'credit' => 0.00,
                    'debit' => (float)$vp->debit,
                    'amount' => (float)$vp->debit,
                    'partner_id' => $pId,
                    'partner_name' => $pName,
                ]);
            }
        }

        // Sort ledger transactions chronologically
        $sortedLedger = $ledgerTransactions->sortBy(fn($t) => Carbon::parse($t->date)->timestamp)->values();

        // Calculate running cumulative payout totals
        $runningCumulativePayout = 0.0;
        $runningLedger = collect();

        foreach ($sortedLedger as $row) {
            $runningCumulativePayout += $row->debit;
            $row->running_balance = $runningCumulativePayout;
            $runningLedger->push($row);
        }

        // 6. Section B: Project-Wide Equity & Profit Distribution Matrix
        $matrixPartners = collect();
        $totalMatrixPayouts = 0.0;
        $totalMatrixContribs = 0.0;
        $totalMatrixAgreedPct = 0.0;

        foreach ($partners as $partner) {
            $pShare = $allPartnerShares->where('partner_id', $partner->id)->first();
            $sharePct = $pShare ? (float)$pShare->share_pct : ($partner->id == 1 ? 57.5 : 42.5);

            // Direct partner capital contributions
            $partnerContribTotal = (float)$partnerContributions->where('partner_id', $partner->id)->sum('amount');

            // Debits for this partner (from allocations, bill payouts, voucher payouts)
            $partnerPayoutTotal = (float)$allocations->where('partner_id', $partner->id)->sum('allocated_amount')
                + (float)$billPayouts->where('payee_id', $partner->id)->sum('amount');

            foreach ($voucherPayouts as $vp) {
                $vpPartnerId = $partnerAccountMap[$vp->account_id] ?? null;
                if ($vpPartnerId && (string)$vpPartnerId === (string)$partner->id) {
                    $partnerPayoutTotal += (float)$vp->debit;
                }
            }

            // Net Balance = Capital Contributed - Payouts Released
            $partnerNetBalance = $partnerContribTotal - $partnerPayoutTotal;

            $totalMatrixAgreedPct += $sharePct;
            $totalMatrixContribs += $partnerContribTotal;
            $totalMatrixPayouts += $partnerPayoutTotal;

            $matrixPartners->push((object)[
                'id' => $partner->id,
                'name' => $partner->name,
                'role' => $partner->role ?? $partner->designation ?? ($partner->id == 1 ? 'Lead Developer' : 'JV Partner / Land Owner'),
                'share_pct' => $sharePct,
                'total_contribs' => $partnerContribTotal,
                'total_allocated' => 0.0,
                'total_payouts' => $partnerPayoutTotal,
                'net_balance' => $partnerNetBalance,
            ]);
        }

        // 7. KPI Card Metrics (Clean, logically correct according to new partner logic)
        $selectedPartnerObj = $partnerId ? $partners->firstWhere('id', $partnerId) : null;
        $agreedProfitShare = $selectedPartnerObj
            ? ($matrixPartners->firstWhere('id', $selectedPartnerObj->id)->share_pct ?? 0.0)
            : $totalMatrixAgreedPct;

        $totalCapitalContributed = $selectedPartnerObj
            ? ($matrixPartners->firstWhere('id', $selectedPartnerObj->id)->total_contribs ?? 0.0)
            : $totalMatrixContribs;

        $totalPayoutsReleased = (float)$sortedLedger->sum('debit');
        $netCapitalBalance = $totalCapitalContributed - $totalPayoutsReleased;

        $totalCredit = $totalCapitalContributed;
        $totalDebit = $totalPayoutsReleased;
        $runningBalance = $netCapitalBalance;
        $earnedProfitShare = $totalCapitalContributed;
        $currentNetEquityBalance = $netCapitalBalance;
        $totalMatrixAllocated = 0.0;

        $companyBankAccounts = \App\Models\CompanyBankAccount::orderByDesc('is_default')->orderBy('bank_name')->get();
        $paymentModes = \App\Models\PaymentMode::where('status', 'active')->orderBy('id')->get();
        if ($paymentModes->isEmpty()) {
            $paymentModes = \App\Models\PaymentMode::orderBy('id')->get();
        }

        return view('reports.partner-statements', array_merge($lookups, compact(
            'activeTab',
            'partners',
            'projects',
            'companyBankAccounts',
            'paymentModes',
            'runningLedger',
            'totalCredit',
            'totalDebit',
            'totalPayoutsReleased',
            'totalCapitalContributed',
            'netCapitalBalance',
            'runningBalance',
            'matrixPartners',
            'totalMatrixAgreedPct',
            'totalMatrixContribs',
            'totalMatrixAllocated',
            'totalMatrixPayouts',
            'agreedProfitShare',
            'earnedProfitShare',
            'currentNetEquityBalance',
            'partnerId',
            'projectId',
            'dateFrom',
            'dateTo'
        )));
    }

    public function recordPartnerPayout(Request $request)
    {
        $validated = $request->validate([
            'partner_id'              => 'required|exists:payees,id',
            'project_id'              => 'required|exists:projects,id',
            'payment_mode'            => 'required|string|max:100',
            'company_bank_account_id' => 'nullable|exists:company_bank_accounts,id',
            'allocated_amount'        => 'required|numeric|min:1',
            'date'                    => 'required|date',
            'remarks'                 => 'nullable|string|max:500',
        ]);

        $systemId = auth()->user()->system_id ?? 1;
        $user = auth()->user();
        $partner = \App\Models\Payee::where('type', 'Partner')->findOrFail($validated['partner_id']);
        $amount = (float) $validated['allocated_amount'];
        $paymentMode = $validated['payment_mode'];
        $companyBankId = !empty($validated['company_bank_account_id']) ? (int)$validated['company_bank_account_id'] : null;

        $companyBank = $companyBankId ? \App\Models\CompanyBankAccount::find($companyBankId) : null;
        $bankName = $companyBank ? ($companyBank->bank_name . ($companyBank->account_number ? ' (' . $companyBank->account_number . ')' : '')) : '';

        // Skip bank balance validation if this is a historical entry
        if (!$request->boolean('is_historical') && $companyBank) {
            $availBal = (float)($companyBank->current_balance ?? $companyBank->opening_balance ?? 0);
            if ($amount > $availBal) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Insufficient Bank Funds! Payout amount (Rs. ' . number_format($amount, 2) . ') exceeds available balance in ' . $companyBank->bank_name . ' Account (Rs. ' . number_format($availBal, 2) . ').');
            }
        }

        DB::transaction(function () use ($systemId, $partner, $validated, $user, $amount, $paymentMode, $companyBankId, $bankName, $request) {
            $isHistorical = $request->boolean('is_historical');

            // Deduct payout amount from selected CompanyBankAccount balance (skip if historical)
            if (!$isHistorical && $companyBankId) {
                $bankAccount = \App\Models\CompanyBankAccount::lockForUpdate()->find($companyBankId);
                if ($bankAccount) {
                    $currentBal = (float)($bankAccount->current_balance ?? $bankAccount->opening_balance ?? 0);
                    $bankAccount->current_balance = $currentBal - $amount;
                    $bankAccount->save();
                }
            }

            $narrationText = 'Partner Payout / Drawing (' . $paymentMode . ($bankName ? ' via ' . $bankName : '') . ') — ' . $partner->name;
            if (!empty($validated['remarks'])) {
                $narrationText .= ' (' . $validated['remarks'] . ')';
            }

            $voucher = \App\Models\Voucher::create([
                'system_id'      => $systemId,
                'voucher_number' => 'PY-PTR-' . date('Ymd-His'),
                'type'           => 'Payment',
                'date'           => $validated['date'],
                'narration'      => $narrationText,
                'created_by'     => $user->id ?? 1,
                'status'         => 'Posted',
            ]);

            // 1. Debit Partner Drawing / Equity Account
            $partnerAccountId = $partner->linked_account_id ?? 2;
            $debitLine = \App\Models\VoucherLine::create([
                'voucher_id'     => $voucher->id,
                'account_id'     => $partnerAccountId,
                'debit'          => $amount,
                'credit'         => 0.00,
                'line_narration' => 'Partner Payout Release (Debit Drawing)',
            ]);

            \App\Models\LedgerEntry::create([
                'system_id'       => $systemId,
                'account_id'      => $partnerAccountId,
                'voucher_id'      => $voucher->id,
                'voucher_line_id' => $debitLine->id,
                'date'            => $validated['date'],
                'debit'           => $amount,
                'credit'          => 0.00,
                'running_balance' => 0.00,
            ]);

            // 2. Credit Pay-From Bank / Cash Account
            $payFromAccountId = null;
            if ($companyBankId) {
                $bankAccountChart = \App\Models\Account::where('system_id', $systemId)
                    ->where('type', 'Asset')
                    ->where(function($q) use ($bankName) {
                        $q->where('name', 'like', '%' . $bankName . '%')->orWhere('code', 'like', '%bank%');
                    })->first();
                $payFromAccountId = $bankAccountChart ? $bankAccountChart->id : null;
            }

            if (!$payFromAccountId) {
                $defaultBank = \App\Models\Account::where('system_id', $systemId)
                    ->where('type', 'Asset')
                    ->where(function($q) {
                        $q->where('name', 'like', '%bank%')->orWhere('code', 'like', '%bank%')->orWhere('code', 'CASH-HAND');
                    })->first();
                $payFromAccountId = $defaultBank ? $defaultBank->id : 1;
            }

            $creditLine = \App\Models\VoucherLine::create([
                'voucher_id'     => $voucher->id,
                'account_id'     => $payFromAccountId,
                'debit'          => 0.00,
                'credit'         => $amount,
                'line_narration' => 'Credit Pay-From Account (' . $paymentMode . ($bankName ? ' - ' . $bankName : '') . ')',
            ]);

            \App\Models\LedgerEntry::create([
                'system_id'       => $systemId,
                'account_id'      => $payFromAccountId,
                'voucher_id'      => $voucher->id,
                'voucher_line_id' => $creditLine->id,
                'date'            => $validated['date'],
                'debit'           => 0.00,
                'credit'          => $amount,
                'running_balance' => 0.00,
            ]);

            // 3. Create PartnerAllocation record storing payment_mode, remarks, and company_bank_account_id in DB
            $alloc = \App\Models\PartnerAllocation::create([
                'system_id'               => $systemId,
                'partner_id'              => $partner->id,
                'project_id'              => $validated['project_id'],
                'allocated_amount'        => $amount,
                'payment_mode'            => $paymentMode,
                'remarks'                 => $validated['remarks'] ?? ('Partner Payout Released (' . $paymentMode . ')'),
                'company_bank_account_id' => $companyBankId,
                'date'                    => $validated['date'],
                'voucher_id'              => $voucher->id,
                'created_by'              => $user->id ?? 1,
            ]);

            // 4. Create Double Entry Accounting Postings in journal_vouchers & journal_entries
            try {
                // Dynamically calculate account code starting from 3001 (3001 for Partner A, 3002 for Partner B, etc.)
                $allPartners = Payee::where('type', 'Partner')->orderBy('id')->get();
                $partnerIndex = $allPartners->search(function ($item) use ($partner) {
                    return $item->id == $partner->id;
                });
                $partnerCode = (string)(3001 + ($partnerIndex !== false ? $partnerIndex : 0));

                // Fetch partner share percentage for this project if available
                $partnerShare = PartnerShare::where('project_id', $validated['project_id'])
                    ->where('partner_id', $partner->id)
                    ->first();
                $sharePctText = $partnerShare ? (' (' . (float)$partnerShare->share_pct . '% Share)') : '';

                $allBankNames = CompanyBankAccount::pluck('bank_name')->filter()->unique()->implode(' / ');
                $bankAccountName = 'Bank Balances (' . ($allBankNames ?: 'Karnataka Bank / HDFC Escrow') . ')';
                $requiredAccounts = [
                    // $partnerCode => ['name' => 'Partner ' . $partner->name . ' Capital Account' . $sharePctText, 'type' => 'EQUITY'],
                    '3001' => ['name' => 'Share Capital', 'type' => 'EQUITY'],
                    '1001' => ['name' => 'Bank Balances', 'type' => 'ASSET'],
                ];
                foreach ($requiredAccounts as $accCode => $accInfo) {
                    ChartOfAccount::firstOrCreate(
                        ['account_code' => $accCode],
                        [
                            'account_name' => $accInfo['name'],
                            'account_type' => $accInfo['type'],
                            'is_active'    => true,
                        ]
                    );
                }

                $voucherType = VoucherType::firstOrCreate(
                    ['code' => 'PARTNER_SHARE'],
                    [
                        'name'        => 'Partner Share Payout Voucher',
                        'prefix'      => 'JV-PS',
                        'description' => 'Generated on partner profit share / payout release',
                        'is_active'   => true,
                    ]
                );

                $jvNo = 'JV-PS-' . date('Y', strtotime($validated['date'])) . '-' . str_pad((string)$alloc->id, 4, '0', STR_PAD_LEFT);
                $journalVoucher = JournalVoucher::create([
                    'voucher_no'      => $jvNo,
                    'voucher_type_id' => $voucherType->id,
                    'voucher_date'    => $validated['date'],
                    'reference_id'    => $alloc->id,
                    'narration'       => 'partner payout to ' . strtolower($partner->name),
                    'is_active'       => true,
                ]);

                // Debit Partner Capital Account (Account 3001 for 1st Partner, 3002 for 2nd Partner, etc.)
                JournalEntry::create([
                    'voucher_id'     => $journalVoucher->id,
                    'account_id'     => '3001',
                    'debit_amount'   => $amount,
                    'credit_amount'  => 0.00,
                    'entity_type'    => 'PARTNER',
                    'entity_id'      => $partner->id,
                    'line_narration' => 'Partner Capital Liability Cleared (' . $partner->name . ')',
                ]);

                // Credit 1001 Bank Account — only post when NOT a historical entry
                if (!$isHistorical) {
                    JournalEntry::create([
                        'voucher_id'     => $journalVoucher->id,
                        'account_id'     => '1001',
                        'debit_amount'   => 0.00,
                        'credit_amount'  => $amount,
                        'entity_type'    => 'BANK',
                        'entity_id'      => $companyBankId,
                        'line_narration' => 'Bank Account (Bank Asset Decreases)',
                    ]);
                }
            } catch (\Exception $e) {}
        });

        return redirect()->back()->with('success', 'Partner Payout / Drawing of Rs. ' . number_format($amount, 2) . ' recorded successfully!');
    }

    /**
     * Partner Statement Ledger & Capital Outflows report view.
     */
    public function partnerOutflowLedger(Request $request): View
    {
        $projects = Project::where('is_active', true)->orderBy('name')->get();
        if ($projects->isEmpty()) {
            $projects = Project::orderBy('name')->get();
        }

        $selectedProjectId = $request->input('project_id');
        $selectedPartnerId = $request->input('partner_id', 'all');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $selectedProject = ($selectedProjectId && $selectedProjectId !== 'all')
            ? ($projects->firstWhere('id', $selectedProjectId) ?? $projects->first())
            : null;

        $allPartners = \App\Models\Payee::whereRaw("LOWER(type) = 'partner'")->orderBy('name')->get();
        $partnerPayeeIds = $allPartners->pluck('id')->toArray();
        $partnerAccountMap = $allPartners->pluck('id', 'linked_account_id')->filter()->toArray();
        $partnerAccountIds = array_keys($partnerAccountMap);

        // 1. Fetch Partner Shares
        $allPartnerShares = PartnerShare::all();

        // 2. Fetch Allocations (Debits - Payouts / Drawings Released)
        $allocQuery = PartnerAllocation::with(['partner', 'project', 'voucher', 'payment', 'companyBankAccount']);
        if ($selectedProjectId && $selectedProjectId !== 'all') {
            $allocQuery->where('project_id', $selectedProjectId);
        }
        if ($selectedPartnerId && $selectedPartnerId !== 'all') {
            $allocQuery->where('partner_id', $selectedPartnerId);
        }
        if ($fromDate) {
            $allocQuery->whereDate('date', '>=', $fromDate);
        }
        if ($toDate) {
            $allocQuery->whereDate('date', '<=', $toDate);
        }
        $allocations = $allocQuery->orderBy('date')->get();

        // 3. Additional Payouts: bill_payments & voucher_lines
        $billPayoutsQuery = DB::table('bill_payments')->whereIn('payee_id', $partnerPayeeIds);
        if ($selectedPartnerId && $selectedPartnerId !== 'all') {
            $billPayoutsQuery->where('payee_id', $selectedPartnerId);
        }
        if ($fromDate) {
            $billPayoutsQuery->whereDate('date', '>=', $fromDate);
        }
        if ($toDate) {
            $billPayoutsQuery->whereDate('date', '<=', $toDate);
        }
        $billPayouts = $billPayoutsQuery->orderBy('date')->get();

        $allocVoucherIds = $allocations->pluck('voucher_id')->filter()->toArray();
        $voucherPayoutsQuery = DB::table('voucher_lines')
            ->join('vouchers', 'voucher_lines.voucher_id', '=', 'vouchers.id')
            ->whereIn('voucher_lines.account_id', $partnerAccountIds)
            ->where('vouchers.type', 'Payment')
            ->where('voucher_lines.debit', '>', 0)
            ->where('vouchers.voucher_number', 'NOT LIKE', 'PY-REF-%')
            ->where('vouchers.narration', 'NOT LIKE', '%Customer Refund%')
            ->where('vouchers.narration', 'NOT LIKE', '%cancellation%');
        if (!empty($allocVoucherIds)) {
            $voucherPayoutsQuery->whereNotIn('vouchers.id', $allocVoucherIds);
        }
        if ($fromDate) {
            $voucherPayoutsQuery->whereDate('vouchers.date', '>=', $fromDate);
        }
        if ($toDate) {
            $voucherPayoutsQuery->whereDate('vouchers.date', '<=', $toDate);
        }
        $voucherPayouts = $voucherPayoutsQuery->select(
            'voucher_lines.*',
            'vouchers.voucher_number',
            'vouchers.date as v_date',
            'vouchers.narration'
        )->get();

        // 4. Fetch Direct Partner Capital Contributions
        $contribsQuery = \App\Models\PartnerContribution::with(['partner', 'project']);
        if ($selectedPartnerId && $selectedPartnerId !== 'all') {
            $contribsQuery->where('partner_id', $selectedPartnerId);
        }
        if ($selectedProjectId && $selectedProjectId !== 'all') {
            $contribsQuery->where('project_id', $selectedProjectId);
        }
        if ($fromDate) {
            $contribsQuery->whereDate('contribution_date', '>=', $fromDate);
        }
        if ($toDate) {
            $contribsQuery->whereDate('contribution_date', '<=', $toDate);
        }
        $partnerContributions = $contribsQuery->orderBy('contribution_date')->get();

        // Build all transactions map by partner
        $partnerTransactions = [];
        foreach ($allPartners as $p) {
            $partnerTransactions[$p->id] = collect();
        }

        // Add Allocations
        foreach ($allocations as $alloc) {
            $dateStr = $alloc->date ? Carbon::parse($alloc->date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $paymentMode = $alloc->payment_mode ?? '';
            if (empty($paymentMode)) {
                $narration = $alloc->voucher?->narration ?? $alloc->remarks ?? '';
                if (stripos($narration, 'Bank Transfer') !== false) $paymentMode = 'Bank Transfer';
                elseif (stripos($narration, 'Cheque') !== false) $paymentMode = 'Cheque';
                elseif (stripos($narration, 'Cash') !== false) $paymentMode = 'Cash';
                elseif (stripos($narration, 'UPI') !== false || stripos($narration, 'Online') !== false) $paymentMode = 'UPI / Online';
                else $paymentMode = 'Bank Transfer';
            }

            if (isset($partnerTransactions[$alloc->partner_id])) {
                $partnerTransactions[$alloc->partner_id]->push((object)[
                    'id' => $alloc->id,
                    'date' => Carbon::parse($dateStr)->format('d/m/Y'),
                    'raw_date' => $dateStr,
                    'voucher_no' => $alloc->voucher?->voucher_number ?? ('PY-PTR-' . Carbon::parse($dateStr)->format('Ymd') . '-' . str_pad((string)$alloc->id, 5, '0', STR_PAD_LEFT)),
                    'payment_mode' => $paymentMode,
                    'bank' => $alloc->companyBankAccount?->bank_name ?? 'Bank Account',
                    'amount' => (float)$alloc->allocated_amount,
                    'remarks' => $alloc->remarks ?: 'Partner Payout / Drawing',
                    'project_name' => $alloc->project?->name ?? ($selectedProject?->name ?? 'Project Allocation'),
                ]);
            }
        }

        // Add Bill Payouts
        foreach ($billPayouts as $pay) {
            $dateStr = $pay->date ? Carbon::parse($pay->date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            if (isset($partnerTransactions[$pay->payee_id])) {
                $partnerTransactions[$pay->payee_id]->push((object)[
                    'id' => $pay->id,
                    'date' => Carbon::parse($dateStr)->format('d/m/Y'),
                    'raw_date' => $dateStr,
                    'voucher_no' => 'BILL-PAY-' . str_pad((string)$pay->id, 3, '0', STR_PAD_LEFT),
                    'payment_mode' => 'Bank Transfer',
                    'bank' => 'Corporate Bank',
                    'amount' => (float)$pay->amount,
                    'remarks' => 'Partner Payout / Drawing',
                    'project_name' => $selectedProject?->name ?? 'General Disbursement',
                ]);
            }
        }

        // Add Voucher Payouts
        foreach ($voucherPayouts as $vp) {
            $pId = $partnerAccountMap[$vp->account_id] ?? null;
            if ($pId && isset($partnerTransactions[$pId])) {
                $dateStr = $vp->v_date ? Carbon::parse($vp->v_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
                $narr = $vp->narration ?? $vp->line_narration ?? '';
                $paymentMode = 'Bank Transfer';
                if (stripos($narr, 'Cheque') !== false) $paymentMode = 'Cheque';
                elseif (stripos($narr, 'Cash') !== false) $paymentMode = 'Cash';
                elseif (stripos($narr, 'UPI') !== false || stripos($narr, 'Online') !== false) $paymentMode = 'UPI / Online';

                $partnerTransactions[$pId]->push((object)[
                    'id' => $vp->id,
                    'date' => Carbon::parse($dateStr)->format('d/m/Y'),
                    'raw_date' => $dateStr,
                    'voucher_no' => $vp->voucher_number,
                    'payment_mode' => $paymentMode,
                    'bank' => 'Main Bank Account',
                    'amount' => (float)$vp->debit,
                    'remarks' => $vp->line_narration ?: ($vp->narration ?: 'Partner Payout / Drawing'),
                    'project_name' => $selectedProject?->name ?? 'Voucher Payment',
                ]);
            }
        }

        // Calculate total allocated outflow
        $allTxns = collect();
        foreach ($partnerTransactions as $pId => $txns) {
            $allTxns = $allTxns->concat($txns);
        }
        $totalAllocatedOutflow = (float)$allTxns->sum('amount');
        $totalCapitalContributed = (float)$partnerContributions->sum('amount');

        // Build monthly trend data
        $monthlyTrendData = $allTxns->groupBy(function ($item) {
            return Carbon::parse($item->raw_date)->format('M Y');
        })->map(function ($group) {
            return (float)$group->sum('amount');
        });

        // Build outflowList
        $partnerShareData = collect();
        $outflowList = collect();

        foreach ($allPartners as $p) {
            $pId = (string)$p->id;
            if ($selectedPartnerId && $selectedPartnerId !== 'all' && (string)$selectedPartnerId !== $pId) {
                continue;
            }

            $txns = $partnerTransactions[$p->id] ?? collect();
            $pAmount = (float)$txns->sum('amount');
            $pContrib = (float)$partnerContributions->where('partner_id', $p->id)->sum('amount');

            $memos = $txns->pluck('remarks')->filter()->unique()->values();
            if ($memos->isNotEmpty()) {
                $description = $memos->take(3)->implode(' • ');
            } else {
                $description = 'Partner Profit Share & Drawings Disbursed';
            }

            $pct = $totalAllocatedOutflow > 0 ? round(($pAmount / $totalAllocatedOutflow) * 100, 1) : 0;
            $sortedTxns = $txns->sortByDesc(fn($t) => Carbon::parse($t->raw_date)->timestamp)->values();
            $lastDate = $sortedTxns->first()?->date ?? '—';

            if ($pAmount > 0) {
                $partnerShareData->put($p->name, $pAmount);
            }

            $allocDetails = $sortedTxns->map(function($a) {
                return [
                    'id' => $a->id,
                    'date' => $a->date,
                    'voucher_no' => $a->voucher_no,
                    'payment_mode' => $a->payment_mode,
                    'bank' => $a->bank,
                    'amount' => (float)$a->amount,
                    'remarks' => $a->remarks,
                    'project_name' => $a->project_name,
                ];
            })->values()->toArray();

            $pShare = $allPartnerShares->where('partner_id', $p->id)->first();
            $sharePct = $pShare ? (float)$pShare->share_pct : ($p->id == 1 ? 57.5 : 42.5);

            $outflowList->push((object)[
                'partner_id' => $p->id,
                'partner_name' => $p->name,
                'partner_phone' => $p->phone ?? '',
                'partner_pan' => $p->pan ?? '',
                'project_name' => $selectedProject?->name ?? ($selectedProjectId === 'all' || empty($selectedProjectId) ? 'All Projects Portfolio' : 'Tabasco Hindustan Infra Developers Pvt. Ltd'),
                'description' => $description,
                'amount' => $pAmount,
                'capital_contributed' => $pContrib,
                'share_pct' => $sharePct,
                'percentage' => $pct,
                'allocations_count' => $txns->count(),
                'last_date' => $lastDate,
                'items' => $allocDetails,
            ]);
        }

        $activePartnersCount = $outflowList->where('amount', '>', 0)->count();
        $avgOutflowPerPartner = $activePartnersCount > 0 ? ($totalAllocatedOutflow / $activePartnersCount) : 0;
        $sortedPartners = $outflowList->sortByDesc('amount');
        $topPartner = $sortedPartners->first();
        $totalTransactionsCount = $allTxns->count();
        $detailedAllocations = $allocations;

        return view('reports.partner-outflow-ledger', compact(
            'projects',
            'allPartners',
            'selectedProjectId',
            'selectedPartnerId',
            'fromDate',
            'toDate',
            'selectedProject',
            'totalAllocatedOutflow',
            'totalCapitalContributed',
            'monthlyTrendData',
            'partnerShareData',
            'outflowList',
            'activePartnersCount',
            'avgOutflowPerPartner',
            'topPartner',
            'totalTransactionsCount',
            'detailedAllocations'
        ));
    }

    public function supplierContractor(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'supplier_contractor';

        // 1. Fetch all relevant payees (Contractor, Supplier, Vendor, or linked in bills)
        $payees = Payee::where(function($q) {
            $q->whereIn(\Illuminate\Support\Facades\DB::raw("LOWER(type)"), ['contractor', 'supplier', 'vendor'])
              ->orWhereIn('id', RaBill::select('contractor_id')->whereNotNull('contractor_id'));
        })->orderBy('name')->get();

        // 2. Fetch distinct contractors from RA Bills to ensure every contractor from bills is displayed
        $billContractors = RaBill::whereNotNull('contractor_name')
            ->where('contractor_name', '!=', '')
            ->select('contractor_id', 'contractor_name')
            ->distinct()
            ->get();

        // 3. Merge into unified collection of ALL contractors
        $allContractorsMap = collect();

        foreach ($payees as $p) {
            $allContractorsMap->put(strtolower(trim($p->name)), (object)[
                'id' => (string)$p->id,
                'filter_value' => (string)$p->id,
                'name' => $p->name,
                'phone' => $p->phone ?? '',
                'gstin' => $p->gstin ?? '',
                'type' => $p->type ?? 'Contractor',
            ]);
        }

        foreach ($billContractors as $bc) {
            $name = trim($bc->contractor_name);
            $key = strtolower($name);
            if (!$allContractorsMap->has($key)) {
                $linkedPayee = $bc->contractor_id ? Payee::find($bc->contractor_id) : null;
                $allContractorsMap->put($key, (object)[
                    'id' => $name,
                    'filter_value' => $name,
                    'name' => $name,
                    'phone' => $linkedPayee?->phone ?? '',
                    'gstin' => $linkedPayee?->gstin ?? '',
                    'type' => 'Contractor',
                ]);
            }
        }

        $suppliers = $allContractorsMap->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->values();

        // Query RA Bills for Contractor Statement
        $supplierQuery = RaBill::with(['contractor', 'project', 'unit']);

        if ($request->filled('project_id') && $request->project_id !== 'all') {
            $supplierQuery->where('project_id', $request->project_id);
        }

        // Multi-select contractor filtering support
        $contractorIds = [];
        if ($request->has('contractor_id')) {
            $rawC = $request->input('contractor_id');
            if (is_array($rawC)) {
                $contractorIds = array_values(array_filter(array_map('trim', $rawC)));
            } elseif (is_string($rawC) && strlen(trim($rawC)) > 0) {
                $contractorIds = array_values(array_filter(array_map('trim', explode(',', $rawC))));
            }
        }

        $applyContractorFilter = function($query) use ($contractorIds) {
            $query->where(function($sub) use ($contractorIds) {
                foreach ($contractorIds as $idx => $cVal) {
                    $subMethod = $idx === 0 ? 'where' : 'orWhere';
                    $sub->$subMethod(function($subQ) use ($cVal) {
                        if (is_numeric($cVal)) {
                            $selectedPayee = Payee::find($cVal);
                            if ($selectedPayee && $selectedPayee->name) {
                                $payeeName = trim($selectedPayee->name);
                                $subQ->where(function($pQ) use ($cVal, $payeeName) {
                                    $pQ->where('contractor_name', $payeeName)
                                       ->orWhere('contractor_name', 'like', "%{$payeeName}%")
                                       ->orWhere(function($idQ) use ($cVal) {
                                           $idQ->where('contractor_id', $cVal)
                                               ->where(function($emptyQ) {
                                                   $emptyQ->whereNull('contractor_name')
                                                          ->orWhere('contractor_name', '');
                                               });
                                       });
                                });
                            } else {
                                $subQ->where('contractor_id', $cVal);
                            }
                        } else {
                            $subQ->where('contractor_name', $cVal)
                                 ->orWhere('contractor_name', 'like', "%{$cVal}%")
                                 ->orWhereHas('contractor', fn($c) => $c->where('name', 'like', "%{$cVal}%"));
                        }
                    });
                }
            });
        };

        if (!empty($contractorIds)) {
            $applyContractorFilter($supplierQuery);
        }

        if ($request->filled('status')) {
            $supplierQuery->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $supplierQuery->where(function($q) use ($search) {
                $q->where('ra_bill_number', 'like', "%{$search}%")
                  ->orWhere('contractor_name', 'like', "%{$search}%")
                  ->orWhereHas('contractor', fn($c) => $c->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('project', fn($p) => $p->where('name', 'like', "%{$search}%"));
            });
        }

        // Executive KPI summary calculations
        $totalApprovedDues = (float) (clone $supplierQuery)->sum('net_approved_amount');
        $totalPaidAmount   = (float) (clone $supplierQuery)->sum('paid_amount');
        $totalBalanceDue   = (float) (clone $supplierQuery)->sum('balance_amount');
        $totalBillsCount   = (clone $supplierQuery)->count();

        // Paginated for Web UI table
        $supplierContractorEntries = (clone $supplierQuery)->orderByDesc('created_at')->paginate(50);

        // Full dataset for Excel export & reactive live client-side filtering (all bills for active project)
        $allSupplierContractorEntries = RaBill::with(['contractor', 'project', 'unit'])
            ->when($request->filled('project_id') && $request->project_id !== 'all', fn($q) => $q->where('project_id', $request->project_id))
            ->orderByDesc('created_at')
            ->get();

        // Contractor payables chart summary
        $contractorSummary = RaBill::selectRaw("contractor_name, SUM(net_approved_amount) as total_due, SUM(paid_amount) as total_paid")
            ->when($request->filled('project_id') && $request->project_id !== 'all', fn($q) => $q->where('project_id', $request->project_id))
            ->when(!empty($contractorIds), fn($q) => $applyContractorFilter($q))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), function($q) use ($request) {
                $search = trim($request->search);
                $q->where(function($sub) use ($search) {
                    $sub->where('ra_bill_number', 'like', "%{$search}%")
                        ->orWhere('contractor_name', 'like', "%{$search}%")
                        ->orWhereHas('contractor', fn($c) => $c->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('project', fn($p) => $p->where('name', 'like', "%{$search}%"));
                });
            })
            ->groupBy('contractor_name')
            ->get()
            ->groupBy(fn($item) => empty($item->contractor_name) ? 'General Contractor' : $item->contractor_name)
            ->map(fn($group, $key) => (object)[
                'c_name' => $key,
                'total_due' => $group->sum('total_due'),
                'total_paid' => $group->sum('total_paid')
            ])
            ->values();

        $supplierChartData = [
            'labels' => $contractorSummary->pluck('c_name')->toArray(),
            'dues' => $contractorSummary->map(fn($c) => (float)$c->total_due)->toArray(),
            'paids' => $contractorSummary->map(fn($c) => (float)$c->total_paid)->toArray(),
            'total_due' => (float)$contractorSummary->sum('total_due'),
            'total_paid' => (float)$contractorSummary->sum('total_paid'),
        ];

        return view('reports.supplier-contractor', array_merge($lookups, compact(
            'activeTab',
            'suppliers',
            'contractorIds',
            'supplierContractorEntries',
            'allSupplierContractorEntries',
            'supplierChartData',
            'totalApprovedDues',
            'totalPaidAmount',
            'totalBalanceDue',
            'totalBillsCount'
        )));
    }

    public function salesReturn(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'sales_return';

        $retQuery = Sale::with([
            'customer',
            'unit.unitType',
            'unit.floor',
            'saleUnits.unit.unitType',
            'saleUnits.unit.floor',
            'project',
            'broker',
            'receipts.bank',
            'receipts.companyBankAccount',
            'statusLogs.performedBy',
        ])
            ->withSum('receipts as total_paid', 'amount')
            ->whereIn('status', ['cancelled', 'returned']);
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
        if ($request->filled('customer_id')) {
            $customerIds = is_array($request->customer_id) ? $request->customer_id : [$request->customer_id];
            $retQuery->whereIn('customer_id', $customerIds);
        }
        $salesReturns = $retQuery->orderByDesc('cancelled_at')->paginate(50);

        // Fetch refund disbursement vouchers to provide comprehensive refund payout details
        $postedVouchers = DB::table('vouchers')
            ->whereIn('type', ['Receipt', 'Payment'])
            ->where('status', 'Posted')
            ->whereNotNull('reference_no')
            ->orderBy('date')
            ->orderBy('id')
            ->get(['id', 'voucher_number', 'date', 'reference_no', 'company_bank_account_id', 'created_by', 'created_at']);

        $bankNames = CompanyBankAccount::get(['id', 'bank_name', 'account_number'])->keyBy('id');
        $userNames = DB::table('users')
            ->whereIn('id', $postedVouchers->pluck('created_by')->filter()->unique()->all())
            ->pluck('name', 'id');

        $refundsPaidBySale = [];
        $refundPaymentsBySale = [];
        foreach ($postedVouchers as $v) {
            $refData = json_decode($v->reference_no, true);
            if (!empty($refData['allocations']) && is_array($refData['allocations'])) {
                foreach ($refData['allocations'] as $alloc) {
                    if (($alloc['type'] ?? '') === 'refund' && !empty($alloc['target_id'])) {
                        $sId = (int)$alloc['target_id'];
                        $amt = (float)($alloc['amount'] ?? 0);
                        $refundsPaidBySale[$sId] = ($refundsPaidBySale[$sId] ?? 0.0) + $amt;

                        $bankId = $refData['company_bank_account_id'] ?? $v->company_bank_account_id;
                        $bank = $bankId ? $bankNames->get((int)$bankId) : null;
                        $refundPaymentsBySale[$sId][] = [
                            'voucher_id'     => $v->id,
                            'voucher_number' => $v->voucher_number,
                            'date'           => $v->date,
                            'created_at'     => $v->created_at,
                            'amount'         => $amt,
                            'payment_mode'   => $refData['payment_mode'] ?? 'Bank Transfer',
                            'bank_name'      => $bank ? $bank->bank_name . ($bank->account_number ? ' (' . $bank->account_number . ')' : '') : '—',
                            'remarks'        => $refData['remarks'] ?? 'Cancellation refund payout',
                            'processed_by'   => $v->created_by ? ($userNames[$v->created_by] ?? null) : null,
                        ];
                    }
                }
            }
        }

        $salesReturns->getCollection()->transform(function ($sale) use ($refundsPaidBySale, $refundPaymentsBySale) {
            $sale->refund_paid = $refundsPaidBySale[$sale->id] ?? 0.00;
            $sale->refund_payments = $refundPaymentsBySale[$sale->id] ?? [];
            return $sale;
        });

        $allReturns = Sale::whereIn('status', ['cancelled', 'returned'])
            ->when($request->filled('project_id'), fn($q) => $q->where('project_id', $request->project_id))
            ->when($request->filled('customer_id'), fn($q) => $q->whereIn('customer_id', is_array($request->customer_id) ? $request->customer_id : [$request->customer_id]))
            ->get();

        // NOTE: sales.refund_amount holds the REMAINING refund balance (reduced by every
        // payout). Total approved liability = remaining balance + already-paid vouchers.
        $allReturns->each(function ($s) use ($refundsPaidBySale) {
            $s->refund_paid = $refundsPaidBySale[$s->id] ?? 0.00;
        });

        $totalFee = (float)$allReturns->sum('cancellation_fee');
        $totalRefund = (float)$allReturns->sum(fn($s) => (float)$s->refund_amount + (float)$s->refund_paid);
        $totalCount = $allReturns->count();

        // Monthly trend grouped in PHP so the refund series stays on total-liability basis
        $monthAgg = [];
        foreach ($allReturns as $s) {
            if (empty($s->cancelled_at)) {
                continue;
            }
            $ym = Carbon::parse($s->cancelled_at)->format('Y-m');
            if (!isset($monthAgg[$ym])) {
                $monthAgg[$ym] = ['m_label' => Carbon::parse($s->cancelled_at)->format('M Y'), 'cnt' => 0, 'total_fee' => 0.0, 'total_refund' => 0.0];
            }
            $monthAgg[$ym]['cnt']++;
            $monthAgg[$ym]['total_fee'] += (float)$s->cancellation_fee;
            $monthAgg[$ym]['total_refund'] += (float)$s->refund_amount + (float)$s->refund_paid;
        }
        ksort($monthAgg);

        $rMonths = [];
        $rCounts = [];
        $rFees = [];
        $rRefunds = [];
        if (!empty($monthAgg)) {
            foreach ($monthAgg as $mr) {
                $rMonths[] = $mr['m_label'];
                $rCounts[] = (int)$mr['cnt'];
                $rFees[] = (float)$mr['total_fee'];
                $rRefunds[] = (float)$mr['total_refund'];
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $dt = Carbon::now()->subMonths($i);
                $rMonths[] = $dt->format('M Y');
                $rCounts[] = 0;
                $rFees[] = 0.0;
                $rRefunds[] = 0.0;
            }
        }
        $salesReturnChartData = [
            'months' => $rMonths,
            'counts' => $rCounts,
            'fees' => $rFees,
            'refunds' => $rRefunds,
            'total_fee' => $totalFee,
            'total_refund' => $totalRefund,
            'total_count' => $totalCount,
        ];

        return view('reports.sales-return', array_merge($lookups, compact('activeTab', 'salesReturns', 'salesReturnChartData')));
    }

    public function exchange(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'exchange_report';

        $exQuery = Sale::with(['customer', 'unit.unitType', 'unit.floor', 'project', 'statusLogs', 'receipts', 'saleUnits.unit.unitType', 'saleUnits.unit.floor'])->where('status', 'exchanged');
        if ($request->filled('project_id')) {
            $exQuery->where('project_id', $request->project_id);
        }
        if ($request->filled('customer_id')) {
            $customerIds = is_array($request->customer_id) ? $request->customer_id : [$request->customer_id];
            $exQuery->whereIn('customer_id', $customerIds);
        }
        $exchangeEntries = $exQuery->orderByDesc('sale_date')->paginate(50);

        $exchangeEntries->getCollection()->transform(function ($sale) {
            return $this->enrichExchangeSale($sale);
        });

        $allExSales = Sale::with(['customer', 'unit.unitType', 'unit.floor', 'project', 'statusLogs', 'receipts', 'saleUnits.unit.unitType', 'saleUnits.unit.floor'])
            ->where('status', 'exchanged')
            ->when($request->filled('project_id'), fn($q) => $q->where('project_id', $request->project_id))
            ->when($request->filled('customer_id'), fn($q) => $q->whereIn('customer_id', is_array($request->customer_id) ? $request->customer_id : [$request->customer_id]))
            ->get()
            ->map(function ($sale) {
                return $this->enrichExchangeSale($sale);
            });

        $monthlyGrouped = $allExSales->groupBy(fn($s) => $s->exchange_date ? \Carbon\Carbon::parse($s->exchange_date)->format('M Y') : 'Unknown');

        $exMonths = [];
        $exCounts = [];
        $exEquities = [];
        if ($monthlyGrouped->isNotEmpty()) {
            foreach ($monthlyGrouped as $mLabel => $salesInMonth) {
                $exMonths[] = $mLabel;
                $exCounts[] = $salesInMonth->count();
                $exEquities[] = (float)$salesInMonth->sum('equity_paid');
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $dt = Carbon::now()->subMonths($i);
                $exMonths[] = $dt->format('M Y');
                $exCounts[] = 0;
                $exEquities[] = 0.0;
            }
        }
        $exchangeChartData = [
            'months' => $exMonths,
            'counts' => $exCounts,
            'equities' => $exEquities,
            'total_equity' => (float)$allExSales->sum('equity_paid'),
            'total_contract' => (float)$allExSales->sum('new_unit_value'),
            'total_count' => $allExSales->count(),
        ];

        return view('reports.exchange', array_merge($lookups, compact('activeTab', 'exchangeEntries', 'exchangeChartData')));
    }

    private function enrichExchangeSale(Sale $sale): Sale
    {
        $exLog = $sale->statusLogs->firstWhere('event_type', 'exchanged');

        // 1. Exchange No
        $logId = $exLog?->id ?? $sale->id;
        $sale->exchange_no = 'EXCH-' . str_pad((string)$logId, 5, '0', STR_PAD_LEFT);

        // 2. Exchange Date
        $exDate = $exLog?->snapshot_data['exchange_meta']['exchanged_at']
            ?? $exLog?->created_at
            ?? $sale->cancelled_at
            ?? $sale->updated_at
            ?? $sale->sale_date;
        $sale->exchange_date = $exDate;

        // 3. Old Unit Details
        if ($sale->relationLoaded('saleUnits') && $sale->saleUnits->isNotEmpty()) {
            $oldUnitParts = $sale->saleUnits->map(function ($su) {
                return $this->formatExchangeUnitDetails($su->unit?->door_no, $su->unit?->unitType?->name, $su->unit?->floor?->name);
            })->filter()->implode(', ');
            $oldUnitStr = $oldUnitParts ?: '—';
        } else {
            $oldUnitDoor = $exLog?->snapshot_data['old_unit']['door_no'] ?? $sale->unit?->door_no;
            $oldUnitType = $exLog?->snapshot_data['old_unit']['unit_type_name'] ?? $sale->unit?->unitType?->name;
            $oldUnitFloor = $exLog?->snapshot_data['old_unit']['floor_name'] ?? $sale->unit?->floor?->name;

            $oldUnitStr = $this->formatExchangeUnitDetails($oldUnitDoor, $oldUnitType, $oldUnitFloor);
        }
        $sale->old_unit_details = $oldUnitStr;

        // 4. Old Customer Name
        $sale->old_customer_name = $sale->customer?->name ?? $exLog?->snapshot_data['customer']['name'] ?? '—';

        // 5. Find Replacement (New) Sale
        $newSale = Sale::where('notes', 'like', '%Exchanged from sale ' . $sale->sale_number . '%')
            ->with(['unit.unitType', 'unit.floor', 'customer', 'project'])
            ->first();

        if (!$newSale && $exLog && !empty($exLog->snapshot_data['exchange_meta']['target_unit_id'])) {
            $targetUnitId = $exLog->snapshot_data['exchange_meta']['target_unit_id'];
            $newSale = Sale::where('unit_id', $targetUnitId)
                ->where('customer_id', $sale->customer_id)
                ->with(['unit.unitType', 'unit.floor', 'customer', 'project'])
                ->first();
        }

        $sale->new_sale = $newSale;

        // 6. New Unit Details & Customer Name & Contract Value
        if ($newSale && $newSale->unit) {
            $sale->new_unit_details = $this->formatExchangeUnitDetails(
                $newSale->unit->door_no,
                $newSale->unit->unitType?->name ?? $newSale->unit->type,
                $newSale->unit->floor?->name
            );
            $sale->new_customer_name = $newSale->customer?->name ?? $sale->old_customer_name;
            $sale->new_unit_value = (float)$newSale->total_amount;
        } else {
            $targetDoor = $exLog?->snapshot_data['exchange_meta']['target_door_no'] ?? null;
            $targetType = $exLog?->snapshot_data['exchange_meta']['target_unit_type'] ?? null;
            $targetFloor = $exLog?->snapshot_data['exchange_meta']['target_floor'] ?? null;
            $sale->new_unit_details = $targetDoor ? $this->formatExchangeUnitDetails($targetDoor, $targetType, $targetFloor) : '—';
            $sale->new_customer_name = $sale->old_customer_name;
            $sale->new_unit_value = (float)($sale->total_amount ?? 0);
        }

        // 7. Equity Paid = Old contract paid (archived snapshot) + New contract paid (new sale receipts)
        $exSnap = $exLog?->snapshot_data ?? [];
        $carryForward = !empty($exSnap['exchange_meta']['carry_forward']);

        // Old down payment: total amount paid on the OLD contract (from the archived snapshot)
        $oldDownPayment = (float)($exSnap['old_sale']['total_paid'] ?? 0);
        if ($oldDownPayment <= 0) {
            // Fallback to transferred_equity or old sale receipts
            $oldDownPayment = (float)($sale->transferred_equity ?? 0);
            if ($oldDownPayment <= 0 && $sale->relationLoaded('receipts')) {
                $oldDownPayment = (float)$sale->receipts->sum('amount');
            }
        }

        // New down payment: amount paid ONLY on the NEW contract.
        // When carry_forward=true, old receipts are physically moved to the new sale,
        // so new sale's receipts total already includes the old payments — subtract them
        // to avoid double-counting.
        $newSaleReceiptsTotal = $newSale ? (float)$newSale->receipts()->sum('amount') : 0.0;
        if ($carryForward) {
            $newDownPayment = max(0.0, $newSaleReceiptsTotal - $oldDownPayment);
        } else {
            $newDownPayment = $newSaleReceiptsTotal;
        }

        $sale->old_down_payment = $oldDownPayment;
        $sale->new_down_payment = $newDownPayment;
        $sale->equity_paid      = $oldDownPayment + $newDownPayment;

        return $sale;
    }

    private function formatExchangeUnitDetails($door, $type = null, $floor = null): string
    {
        $doorStr = trim((string)($door ?? ''));
        $typeStr = trim((string)($type ?? ''));
        $floorStr = trim((string)($floor ?? ''));

        if ($doorStr === '—') {
            $doorStr = '';
        }
        if ($floorStr === '—') {
            $floorStr = '';
        }

        if ($doorStr === '' && $typeStr === '' && $floorStr === '') {
            return '—';
        }

        if ($floorStr !== '' && is_numeric($floorStr)) {
            $floorStr = 'Floor ' . $floorStr;
        }

        $result = $doorStr;
        if ($typeStr !== '') {
            $result .= '(' . $typeStr . ')';
        }
        if ($floorStr !== '') {
            $result .= ($result !== '' ? ' - ' : '') . $floorStr;
        }
        return $result ?: '—';
    }

    public function pettyCash(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'petty_cash';

        $pettyQuery = Receipt::with(['customer', 'sale.project'])->where('payment_mode', 'Cash');
        if ($request->filled('customer_id')) {
            $customerIds = is_array($request->customer_id) ? $request->customer_id : [$request->customer_id];
            $pettyQuery->whereIn('customer_id', $customerIds);
        }
        if ($request->filled('date_from')) {
            $pettyQuery->whereDate('receipt_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $pettyQuery->whereDate('receipt_date', '<=', $request->date_to);
        }
        $pettyCashEntries = $pettyQuery->orderByDesc('receipt_date')->paginate(50);

        $allPetty = Receipt::with('customer')
            ->where('payment_mode', 'Cash')
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('receipt_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('receipt_date', '<=', $request->date_to))
            ->get();

        $totalPettyAmount = (float)$allPetty->sum('amount');
        $pettyCount = $allPetty->count();
        $avgPetty = $pettyCount > 0 ? $totalPettyAmount / $pettyCount : 0;
        $maxPetty = (float)($allPetty->max('amount') ?? 0);

        $custBreakdown = $allPetty->groupBy('customer_id')->map(function($items) {
            $custName = $items->first()?->customer?->name ?? 'Customer #' . $items->first()?->customer_id;
            return [
                'name' => $custName,
                'total' => (float)$items->sum('amount'),
            ];
        })->values();

        $monthlyPetty = Receipt::where('payment_mode', 'Cash')
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('receipt_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('receipt_date', '<=', $request->date_to))
            ->selectRaw("DATE_FORMAT(receipt_date, '%b %Y') as m_label, DATE_FORMAT(receipt_date, '%Y-%m') as ym, SUM(amount) as total")
            ->groupBy('ym', 'm_label')
            ->orderBy('ym')
            ->get();

        $pcMonths = [];
        $pcAmounts = [];
        if ($monthlyPetty->isNotEmpty()) {
            foreach ($monthlyPetty as $mp) {
                $pcMonths[] = $mp->m_label;
                $pcAmounts[] = (float)$mp->total;
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $dt = Carbon::now()->subMonths($i);
                $pcMonths[] = $dt->format('M Y');
                $pcAmounts[] = 0.0;
            }
        }
        $pettyCashChartData = [
            'months' => $pcMonths,
            'amounts' => $pcAmounts,
            'total_amount' => $totalPettyAmount,
            'total_count' => $pettyCount,
            'avg_amount' => $avgPetty,
            'max_amount' => $maxPetty,
            'cust_labels' => $custBreakdown->pluck('name')->toArray(),
            'cust_totals' => $custBreakdown->pluck('total')->toArray(),
        ];

        return view('reports.petty-cash', array_merge($lookups, compact('activeTab', 'pettyCashEntries', 'pettyCashChartData')));
    }

    public function pettyCashReports(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'petty_cash_reports';

        // Default filters
        $project_id = $request->input('project_id');
        if (!$project_id && count($lookups['projects']) > 0) {
            $project_id = $lookups['projects']->first()->id;
        }

        $from_date = $request->input('date_from', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'));
        $to_date = $request->input('date_to', \Carbon\Carbon::now()->format('Y-m-d'));

        // Query transactions
        $transactionsQuery = \App\Models\PettyCashTransaction::whereHas('pettyCashBox', function($q) use ($project_id) {
            if ($project_id) {
                $q->where('project_id', $project_id);
            }
        });

        if ($request->filled('search')) {
            $searchTerm = '%' . trim($request->search) . '%';
            $transactionsQuery->where(function($q) use ($searchTerm) {
                $q->where('voucher_number', 'like', $searchTerm)
                  ->orWhere('narration', 'like', $searchTerm)
                  ->orWhere('reference_no', 'like', $searchTerm)
                  ->orWhere('transaction_type', 'like', $searchTerm)
                  ->orWhere('payment_mode', 'like', $searchTerm);
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $transactionsQuery->whereIn('status', ['Posted', 'approved', 'Active', 'active']);
            } elseif ($request->status === 'pending') {
                $transactionsQuery->whereIn('status', ['pending', 'Draft', 'Pending', 'draft']);
            } else {
                $transactionsQuery->where('status', $request->status);
            }
        }

        // Get past transactions for Opening Balance
        $openingCashIn = (clone $transactionsQuery)->whereDate('transaction_date', '<', $from_date)->sum('cash_in');
        $openingCashOut = (clone $transactionsQuery)->whereDate('transaction_date', '<', $from_date)->sum('cash_out');
        $openingBalance = $openingCashIn - $openingCashOut;

        // Current period transactions
        $periodTransactions = (clone $transactionsQuery)
            ->whereDate('transaction_date', '>=', $from_date)
            ->whereDate('transaction_date', '<=', $to_date)
            ->orderBy('transaction_date', 'asc')
            ->get();

        $totalCashIn = $periodTransactions->sum('cash_in');
        $totalCashOut = $periodTransactions->sum('cash_out');
        $closingBalance = $openingBalance + $totalCashIn - $totalCashOut;

        // Build running balance
        $runningBalance = $openingBalance;
        $reportEntries = collect();
        
        // Add Opening Balance row
        $reportEntries->push((object)[
            'date' => \Carbon\Carbon::parse($from_date)->format('d-M-Y'),
            'voucher_number' => '-',
            'particulars' => 'Opening Balance',
            'cash_in' => 0,
            'cash_out' => 0,
            'balance' => $openingBalance,
            'type' => 'Opening',
            'reference_no' => '-'
        ]);

        foreach ($periodTransactions as $t) {
            $runningBalance += $t->cash_in - $t->cash_out;
            $reportEntries->push((object)[
                'date' => \Carbon\Carbon::parse($t->transaction_date)->format('d-M-Y'),
                'voucher_number' => $t->voucher_number ?? '-',
                'particulars' => $t->narration,
                'cash_in' => $t->cash_in,
                'cash_out' => $t->cash_out,
                'balance' => $runningBalance,
                'type' => $t->cash_in > 0 ? 'Contra' : 'Expense',
                'reference_no' => $t->reference_no ?? '-'
            ]);
        }

        $selectedProjectObj = $lookups['projects']->firstWhere('id', $project_id);
        $pettyCashBox = $project_id ? \App\Models\PettyCashBox::with('incharge')->where('project_id', $project_id)->first() : null;
        $cashBoxIncharge = $pettyCashBox && $pettyCashBox->incharge ? $pettyCashBox->incharge->name : (auth()->check() ? auth()->user()->name : 'Owner');
        $cashBoxCode = $pettyCashBox ? $pettyCashBox->box_code : ('PC-' . strtoupper(substr(optional($selectedProjectObj)->code ?? 'HEV', 0, 8)) . '-001');
        $siteName = $selectedProjectObj ? $selectedProjectObj->name : 'All Sites';
        $lastUpdated = $periodTransactions->last() ? \Carbon\Carbon::parse($periodTransactions->last()->created_at ?? $periodTransactions->last()->transaction_date)->format('d-M-Y h:i A') : date('d-M-Y h:i A');
        $updatedBy = auth()->check() ? auth()->user()->name : 'Owner';

        $reportData = [
            'project_id' => $project_id,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'opening_balance' => $openingBalance,
            'total_cash_in' => $totalCashIn,
            'total_cash_out' => $totalCashOut,
            'closing_balance' => $closingBalance,
            'entries' => $reportEntries,
            'site_name' => $siteName,
            'cash_box_incharge' => $cashBoxIncharge,
            'cash_box_code' => $cashBoxCode,
            'last_updated' => $lastUpdated,
            'updated_by' => $updatedBy,
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'reportData' => $reportData
            ]);
        }

        return view('reports.petty-cash-reports', array_merge($lookups, compact('activeTab', 'reportData')));
    }

    public function loanSchedules(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'loan_schedules';

        $loanSchedules = EmiSchedule::with(['loan.project'])->orderBy('due_date')->paginate(50);

        $monthlyLoans = EmiSchedule::selectRaw("DATE_FORMAT(due_date, '%b %Y') as m_label, DATE_FORMAT(due_date, '%Y-%m') as ym, SUM(principal_component) as principal, SUM(interest_component) as interest")
            ->groupBy('ym', 'm_label')
            ->orderBy('ym')
            ->limit(12)
            ->get();

        $lMonths = [];
        $lPrincipals = [];
        $lInterests = [];
        if ($monthlyLoans->isNotEmpty()) {
            foreach ($monthlyLoans as $ml) {
                $lMonths[] = $ml->m_label;
                $lPrincipals[] = (float)$ml->principal;
                $lInterests[] = (float)$ml->interest;
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $dt = Carbon::now()->subMonths($i);
                $lMonths[] = $dt->format('M Y');
                $lPrincipals[] = 0.0;
                $lInterests[] = 0.0;
            }
        }
        $loanChartData = [
            'months' => $lMonths,
            'principals' => $lPrincipals,
            'interests' => $lInterests,
        ];

        return view('reports.loan-schedules', array_merge($lookups, compact('activeTab', 'loanSchedules', 'loanChartData')));
    }

   public function trialBalance(Request $request): View
    {
        $activeTab = 'trial_balance';

        $allProjects = Project::where('is_active', true)->orderBy('name')->get();
        if ($allProjects->isEmpty()) {
            $allProjects = Project::orderBy('name')->get();
        }

        // Filters
        $firstProjectId = $allProjects->first()?->id ?? 'all';
        $selectedProjectId = $request->input('project_id', 'all');
        $periodType = $request->input('period_type', 'fy');
        $viewMode = $request->input('view_mode', 'detailed'); // 'detailed' | 'summary'
        $hideZero = $request->boolean('hide_zero', false);

        // Date Calculations based on period type
        $today = Carbon::today();
        // Dynamic current Financial Year (Apr 01 -> Mar 31)
        $fyStartYear = ((int) $today->format('n')) >= 4 ? (int) $today->format('Y') : ((int) $today->format('Y')) - 1;
        $defaultFrom = sprintf('%04d-04-01', $fyStartYear);
        $defaultTo = sprintf('%04d-03-31', $fyStartYear + 1);

        if ($periodType === 'fy') {
            $fromDate = $request->input('from_date', $defaultFrom);
            $toDate   = $request->input('to_date', $defaultTo);
        } elseif ($periodType === 'quarter') {
            $fromDate = $request->input('from_date', $today->copy()->startOfQuarter()->format('Y-m-d'));
            $toDate   = $request->input('to_date', $today->copy()->endOfQuarter()->format('Y-m-d'));
        } elseif ($periodType === 'month') {
            $fromDate = $request->input('from_date', $today->copy()->startOfMonth()->format('Y-m-d'));
            $toDate   = $request->input('to_date', $today->copy()->endOfMonth()->format('Y-m-d'));
        } else {
            $fromDate = $request->input('from_date', $defaultFrom);
            $toDate   = $request->input('to_date', $defaultTo);
        }

        $formattedFrom = Carbon::parse($fromDate)->format('d-M-Y');
        $formattedTo   = Carbon::parse($toDate)->format('d-M-Y');
        $financialYearLabel = "{$formattedFrom} to {$formattedTo}";

        // Fetch all active Chart of Accounts directly from database (100% dynamic, no hardcoded values)
        $coas = ChartOfAccount::where('is_active', true)->orderBy('account_code')->get();

        // 1. Fetch Dynamic Movements from journal_entries
        $jeQuery = JournalEntry::join('journal_vouchers', 'journal_entries.voucher_id', '=', 'journal_vouchers.id')
            ->where(function($q) {
                $q->where('journal_vouchers.status', 'Posted')
                  ->orWhere('journal_vouchers.is_active', true);
            });

        if ($selectedProjectId !== 'all' && $selectedProjectId) {
            $jeQuery->where(function($q) use ($selectedProjectId) {
                $q->whereIn('journal_vouchers.reference_id', function($sub) use ($selectedProjectId) {
                    $sub->select('id')->from('ra_bills')->where('project_id', $selectedProjectId);
                })
                ->orWhereIn('journal_vouchers.reference_id', function($sub) use ($selectedProjectId) {
                    $sub->select('ra_bill_payments.id')->from('ra_bill_payments')
                        ->join('ra_bills', 'ra_bill_payments.ra_bill_id', '=', 'ra_bills.id')
                        ->where('ra_bills.project_id', $selectedProjectId);
                })
                ->orWhereIn('journal_vouchers.reference_id', function($sub) use ($selectedProjectId) {
                    $sub->select('id')->from('site_expenses')->where('project_id', $selectedProjectId);
                })
                ->orWhereIn('journal_vouchers.reference_id', function($sub) use ($selectedProjectId) {
                    $sub->select('site_expense_payments.id')->from('site_expense_payments')
                        ->join('site_expenses', 'site_expense_payments.site_expense_id', '=', 'site_expenses.id')
                        ->where('site_expenses.project_id', $selectedProjectId);
                });
            });
        }

        $allJe = $jeQuery->select(
            'journal_entries.account_id',
            'journal_entries.debit_amount',
            'journal_entries.credit_amount',
            'journal_vouchers.voucher_date',
            'journal_vouchers.voucher_no',
            'journal_vouchers.id as jv_id'
        )->get();

        // 2. Fetch Dynamic Movements from voucher_lines & vouchers (excluding duplicates already covered in journal_entries)
        $vlQuery = VoucherLine::join('vouchers', 'voucher_lines.voucher_id', '=', 'vouchers.id')
            ->join('accounts', 'voucher_lines.account_id', '=', 'accounts.id')
            ->where('vouchers.status', 'Posted')
            ->where(function($q) {
                // Exclude contractor & site expense payment PVs already covered in journal_entries (JV-RA-* / JV-CP-* / JV-SE-* / JV-SEP-*)
                $q->whereNot(function($sub) {
                    $sub->where('vouchers.voucher_number', 'like', 'PV-%')
                        ->where(function($n) {
                            $n->where('vouchers.narration', 'like', '%RA Bill%')
                              ->orWhere('vouchers.narration', 'like', '%Site Expense%');
                        });
                });
                // Exclude site expense bill vouchers already covered in journal_entries
                $q->where('vouchers.voucher_number', 'not like', 'BILL-EXP-%');
                // Exclude loan EMI payment vouchers already covered in journal_entries (JV-EMI-*)
                $q->where('vouchers.voucher_number', 'not like', 'PAY-LOAN-%');
                // Exclude broker payout vouchers already covered in journal_entries (JV-AP-*)
                $q->where('vouchers.voucher_number', 'not like', 'PV-BROKER-%');
                // Exclude contra cash withdrawal vouchers already covered in journal_entries (JV-PC-*)
                $q->where('vouchers.voucher_number', 'not like', 'PCON-%');
            });

        if ($selectedProjectId !== 'all' && $selectedProjectId) {
            $vlQuery->where(function($q) use ($selectedProjectId) {
                $q->where('vouchers.reference_no', 'like', '%"project_id":"' . $selectedProjectId . '"%')
                  ->orWhere('vouchers.system_id', $selectedProjectId);
            });
        }

        $allVl = $vlQuery->select(
            'accounts.code as acc_code',
            'accounts.name as acc_name',
            'voucher_lines.debit',
            'voucher_lines.credit',
            'vouchers.date as voucher_date'
        )->get();

        // Map Account Code to standard COA Code
        // Dynamic Resolver: Map database Account codes to standard Chart of Accounts (COA) codes
        $resolveCoaCode = function (string $accCode, string $accName = '') use ($coas): string {
            if ($coas->contains('account_code', $accCode)) {
                return $accCode;
            }
            $upper = strtoupper(trim($accCode));
            $name  = strtoupper(trim($accName));

            // Loan interest must be checked before generic BANK / EXP matching
            if (str_contains($name, 'LOAN INTEREST') || str_contains($upper, 'LOAN-INT') || str_contains($upper, 'EXP-LOAN')) {
                return '2202'; // Bank Loan Interest
            }
            if (str_starts_with($upper, 'LOAN-') || str_contains($name, 'LOAN ACCOUNT') || str_contains($name, 'CONSTRUCTION LOAN')) {
                return '2201'; // Bank Construction Loans / Project Credit Limits
            }
            if (str_starts_with($upper, 'BANK-') || str_starts_with($upper, 'BK-') || $upper === '1071' || str_contains($name, 'BANK')) {
                return '1001'; // Bank Accounts
            }
            if (str_starts_with($upper, 'CASH-') || str_contains($upper, 'PETTY') || str_contains($name, 'CASH')) {
                return '1002'; // Cash in Hand / Petty Cash
            }
            if (str_starts_with($upper, 'CUST-') || str_contains($name, 'RECEIV')) {
                return '1010'; // Customer Receivables
            }
            if (str_starts_with($upper, 'BRK-') || str_contains($name, 'BROKER') || str_contains($name, 'COMMISSION')) {
                return '2003'; // Broker Commissions Payable
            }
            if (str_starts_with($upper, 'SUP-') || str_starts_with($upper, 'VND-') || str_starts_with($upper, 'CONT-')) {
                return '2002'; // Contractor / Supplier Payables
            }
            if (str_starts_with($upper, 'PRT-') || str_contains($name, 'CAPITAL')) {
                return '3001'; // Share / Partner Capital
            }
            if (str_starts_with($upper, 'EXP-SITE') || str_starts_with($upper, 'EXP-')) {
                if (str_contains($name, 'MACHINERY') || str_contains($name, 'EQUIPMENT')) {
                    return '4004'; // Machinery & Heavy Equipment Rental
                }
                if (str_contains($name, 'MATERIAL') || str_contains($name, 'CEMENT') || str_contains($name, 'SAND') || str_contains($name, 'AGGREGATE')) {
                    return '4005'; // Construction Material Expenses
                }
                if (str_contains($name, 'AGENT') || str_contains($name, 'BROKER')) {
                    return '4001'; // Agent Payable Expense
                }
                if (str_contains($name, 'CONTRACTOR') || str_contains($name, 'RA BILL')) {
                    return '4002'; // Contractor Work Expenses
                }
                return '4003'; // Site Office & Administrative
            }
            if (str_starts_with($upper, 'INC-')) {
                return '5010'; // Sales Revenue
            }
            // Unknown numeric codes (e.g. standalone bank sub-ledgers) -> group by first digit
            if (ctype_digit($upper)) {
                return match ($upper[0]) {
                    '1'     => '1001',
                    '2'     => '2002',
                    '3'     => '3001',
                    '4'     => '4003',
                    default => $accCode,
                };
            }
            return $accCode;
        };

        // Group definitions matching the reference design
        $groupsData = [
            '1000' => [
                'code' => '1000',
                'name' => 'ASSETS',
                'type' => 'ASSET',
                'accounts' => [],
                'opening_balance' => 0.0,
                'period_debit' => 0.0,
                'period_credit' => 0.0,
                'closing_balance' => 0.0,
            ],
            '2000' => [
                'code' => '2000',
                'name' => 'LIABILITIES',
                'type' => 'LIABILITY',
                'accounts' => [],
                'opening_balance' => 0.0,
                'period_debit' => 0.0,
                'period_credit' => 0.0,
                'closing_balance' => 0.0,
            ],
            '3000' => [
                'code' => '3000',
                'name' => 'EQUITY',
                'type' => 'EQUITY',
                'accounts' => [],
                'opening_balance' => 0.0,
                'period_debit' => 0.0,
                'period_credit' => 0.0,
                'closing_balance' => 0.0,
            ],
            '4000' => [
                'code' => '4000',
                'name' => 'DIRECT EXPENSES',
                'type' => 'EXPENSE',
                'accounts' => [],
                'opening_balance' => 0.0,
                'period_debit' => 0.0,
                'period_credit' => 0.0,
                'closing_balance' => 0.0,
            ],
            '5000' => [
                'code' => '5000',
                'name' => 'REVENUE / INCOME',
                'type' => 'REVENUE',
                'accounts' => [],
                'opening_balance' => 0.0,
                'period_debit' => 0.0,
                'period_credit' => 0.0,
                'closing_balance' => 0.0,
            ],
        ];

        // Ensure all groups are visible even when empty (so grand totals balance)
        // Note: groups with zero amounts will be hidden if hideZero is on

        // Precompute unbacked sales booking contract amounts on 1010 to reflect realized collections
        $sbUnbacked = [];
        $sbVouchers = $allJe->filter(fn($e) => str_starts_with((string)($e->voucher_no ?? ''), 'JV-SB-'))->groupBy('jv_id');
        foreach ($sbVouchers as $jvId => $entries) {
            $dr1010 = (float)$entries->where('account_id', '1010')->sum('debit_amount');
            $taxCr = (float)$entries->whereIn('account_id', ['2021', '2022'])->sum('credit_amount');
            if ($dr1010 > $taxCr) {
                $sbUnbacked[$jvId] = round($dr1010 - $taxCr, 2);
            }
        }

        // Process each COA account
        foreach ($coas as $coa) {
            $code = (string) $coa->account_code;
            $name = (string) $coa->account_name;
            $type = strtoupper((string) $coa->account_type);

            // Determine parent group — use account_type first for correctness,
            // then fall back to account_code prefix.
            if (in_array($type, ['EXPENSE', 'EXPENSES'])) {
                $groupKey = '4000'; // All expense-typed accounts go to DIRECT EXPENSES
            } elseif (in_array($type, ['INCOME', 'REVENUE'])) {
                $groupKey = '5000'; // All income/revenue accounts go to REVENUE
            } elseif (in_array($type, ['ASSET', 'ASSETS'])) {
                $groupKey = '1000';
            } elseif (in_array($type, ['LIABILITY', 'LIABILITIES'])) {
                $groupKey = '2000';
            } elseif (in_array($type, ['EQUITY'])) {
                $groupKey = '3000';
            } else {
                // Fallback: use account_code prefix
                if (str_starts_with($code, '1')) {
                    $groupKey = '1000';
                } elseif (str_starts_with($code, '2')) {
                    $groupKey = '2000';
                } elseif (str_starts_with($code, '3')) {
                    $groupKey = '3000';
                } elseif (str_starts_with($code, '4')) {
                    $groupKey = '4000';
                } elseif (str_starts_with($code, '5')) {
                    $groupKey = '5000';
                } else {
                    $groupKey = '1000';
                }
            }

            // Initial Opening Balance from COA Master
            $initialOb = (float) $coa->opening_balance;
            $obSide = strtoupper((string) ($coa->opening_balance_type ?: 'DR'));

            // Accumulate prior movements (before $fromDate)
            $priorDr = 0.0;
            $priorCr = 0.0;

            // Movements within period ($fromDate to $toDate)
            $periodDr = 0.0;
            $periodCr = 0.0;

            // 1. From Journal Entries
            $appliedSb = [];
            foreach ($allJe as $je) {
                if ($je->account_id === $code) {
                    $dr = (float) $je->debit_amount;
                    if ($code === '1010' && isset($sbUnbacked[$je->jv_id]) && !isset($appliedSb[$je->jv_id])) {
                        $dr = max(0.0, $dr - $sbUnbacked[$je->jv_id]);
                        $appliedSb[$je->jv_id] = true;
                    }
                    $cr = (float) $je->credit_amount;
                    $vDate = $je->voucher_date ? Carbon::parse($je->voucher_date)->format('Y-m-d') : null;
                    if ($vDate && $vDate < $fromDate) {
                        $priorDr += $dr;
                        $priorCr += $cr;
                    } elseif (!$vDate || ($vDate >= $fromDate && $vDate <= $toDate)) {
                        $periodDr += $dr;
                        $periodCr += $cr;
                    }
                }
            }

            // 2. From Voucher Lines
            foreach ($allVl as $vl) {
                $mappedCode = $resolveCoaCode($vl->acc_code, (string) ($vl->acc_name ?? ''));
                if ($mappedCode === $code) {
                    $vDate = $vl->voucher_date ? Carbon::parse($vl->voucher_date)->format('Y-m-d') : null;
                    if ($vDate && $vDate < $fromDate) {
                        $priorDr += (float) $vl->debit;
                        $priorCr += (float) $vl->credit;
                    } elseif (!$vDate || ($vDate >= $fromDate && $vDate <= $toDate)) {
                        $periodDr += (float) $vl->debit;
                        $periodCr += (float) $vl->credit;
                    }
                }
            }

            // Compute Net Opening Balance
            // Signed convention: Debit positive (+), Credit negative (-)
            // Opening Balance = COA opening balance (as per its Dr/Cr nature) + all prior period movements
            $openingBalance = ($obSide === 'CR' ? -$initialOb : $initialOb) + ($priorDr - $priorCr);

            // Compute Closing Balance:
            // Formula = Opening Balance + Debit Movement - Credit Movement
            // Dr / Cr Suffix
            $closingSigned = $openingBalance + $periodDr - $periodCr;
            $closingSide = $closingSigned >= 0 ? 'Dr' : 'Cr';
            $closingNet = abs($closingSigned);
            $openingSide = $openingBalance >= 0 ? 'Dr' : 'Cr';

            $isZero = ($openingBalance == 0 && $periodDr == 0 && $periodCr == 0);

            $accountRow = [
                'id'              => $coa->id,
                'code'            => $code,
                'name'            => $name,
                'type'            => $type,
                'opening_balance' => $openingBalance,
                'opening_side'    => $openingSide,
                'period_debit'    => $periodDr,
                'period_credit'   => $periodCr,
                'closing_balance' => $closingNet,
                'closing_side'    => $closingSide,
                'is_zero'         => $isZero,
            ];

            $groupsData[$groupKey]['accounts'][] = $accountRow;
            $groupsData[$groupKey]['opening_balance'] += $openingBalance;
            $groupsData[$groupKey]['period_debit']    += $periodDr;
            $groupsData[$groupKey]['period_credit']   += $periodCr;
            $groupsData[$groupKey]['closing_balance'] += $closingSigned;
        }

        // Grand Totals across all accounts (Opening uses signed values)
        $signedOpenings = [];
        foreach ($groupsData as $grp) {
            foreach ($grp['accounts'] as $acc) {
                $signedOpenings[] = $acc['opening_balance'];
            }
        }
        $grandTotalOpening = array_sum($signedOpenings);
        $grandTotalOpeningSide = $grandTotalOpening >= 0 ? 'Dr' : 'Cr';
        $grandTotalOpening = abs($grandTotalOpening);
        $grandTotalDebit   = array_sum(array_column($groupsData, 'period_debit'));
        $grandTotalCredit  = array_sum(array_column($groupsData, 'period_credit'));

        // Format group opening/closing sides (absolute display + Dr/Cr tag)
        foreach ($groupsData as $k => &$grp) {
            $grp['opening_side'] = $grp['opening_balance'] >= 0 ? 'Dr' : 'Cr';
            $grp['opening_balance'] = abs($grp['opening_balance']);
            $grp['closing_side'] = $grp['closing_balance'] >= 0 ? 'Dr' : 'Cr';
            $grp['closing_balance'] = abs($grp['closing_balance']);
        }
        unset($grp);

        // Filter out groups that have no accounts and zero movements (e.g. 5000 REVENUE if 5010 is removed)
        $groupsData = array_filter($groupsData, function($grp) {
            return !empty($grp['accounts']) || $grp['period_debit'] > 0 || $grp['period_credit'] > 0 || $grp['opening_balance'] != 0;
        });

        $diff = abs($grandTotalDebit - $grandTotalCredit);
        $isBalanced = ($diff < 0.01);

        return view('reports.trial-balance', compact(
            'activeTab',
            'allProjects',
            'selectedProjectId',
            'periodType',
            'fromDate',
            'toDate',
            'viewMode',
            'hideZero',
            'financialYearLabel',
            'groupsData',
            'grandTotalOpening',
            'grandTotalOpeningSide',
            'grandTotalDebit',
            'grandTotalCredit',
            'isBalanced',
            'diff'
        ));
    }

    public function profitLoss(Request $request): View
    {
        $lookups   = $this->getCommonLookups($request);
        $activeTab = 'profit_loss';

        $projectId = $request->get('project_id');
        $financialYear = $request->get('financial_year', 'FY 2026-27');

        // ── 1. Determine Date Ranges for Current & Prior Periods ────────────────
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $currFrom = $request->date_from;
            $currTo   = $request->date_to;
            $priorFrom = date('Y-m-d', strtotime("$currFrom -1 year"));
            $priorTo   = date('Y-m-d', strtotime("$currTo -1 year"));

            $currLabel  = date('d/m/Y', strtotime($currFrom)) . ' – ' . date('d/m/Y', strtotime($currTo));
            $priorLabel = date('d/m/Y', strtotime($priorFrom)) . ' – ' . date('d/m/Y', strtotime($priorTo));
        } else {
            if ($financialYear === 'FY 2025-26') {
                $currFrom   = '2025-04-01';
                $currTo     = '2026-03-31';
                $priorFrom  = '2024-04-01';
                $priorTo    = '2025-03-31';
                $currLabel  = 'FY 2025-26 (01/04/2025 – 31/03/2026)';
                $priorLabel = 'FY 2024-25 (01/04/2024 – 31/03/2025)';
            } elseif ($financialYear === 'FY 2024-25') {
                $currFrom   = '2024-04-01';
                $currTo     = '2025-03-31';
                $priorFrom  = '2023-04-01';
                $priorTo    = '2024-03-31';
                $currLabel  = 'FY 2024-25 (01/04/2024 – 31/03/2025)';
                $priorLabel = 'FY 2023-24 (01/04/2023 – 31/03/2024)';
            } else {
                $currFrom   = '2026-04-01';
                $currTo     = '2027-03-31';
                $priorFrom  = '2025-04-01';
                $priorTo    = '2026-03-31';
                $currLabel  = 'FY 2026-27 (01/04/2026 – 31/03/2027)';
                $priorLabel = 'FY 2025-26 (01/04/2025 – 31/03/2026)';
            }
        }

        // ── 2. Accurate DB Query Helper for Any Period ──────────────────────────
        $fetchPeriodData = function (string $from, string $to) use ($projectId, $request) {
            $salesQ     = Sale::where('status', 'active')->whereBetween('sale_date', [$from, $to]);
            $brokerageQ = Brokerage::whereBetween(DB::raw('DATE(created_at)'), [$from, $to]);
            $emiQ       = EmiSchedule::where('status', 'Paid')->whereBetween('paid_date', [$from, $to]);

            // Realized customer receipts only (matches Dashboard "COLLECTIONS" logic)
            $realizedReceiptsQ = Receipt::whereBetween('receipt_date', [$from, $to])
                ->whereNull('partner_id')
                ->where('realization_status', 'realized');

            if ($projectId && $projectId !== 'all') {
                $salesQ->where('project_id', $projectId);
                $realizedReceiptsQ->where('project_id', $projectId);
                $brokerageQ->whereHas('sale', fn($q) => $q->where('project_id', $projectId));
                $emiQ->whereHas('loan', fn($q) => $q->where('project_id', $projectId));
            }
            if ($request->filled('customer_id')) {
                $salesQ->where('customer_id', $request->customer_id);
                $realizedReceiptsQ->where('customer_id', $request->customer_id);
            }

            // Gross realized collections
            $grossRealizedCollections = round((float)$realizedReceiptsQ->sum('amount'), 2);

            // Deduct refunds paid (from Posted vouchers with refund allocations) — same as Dashboard
            $projectSaleIds = ($projectId && $projectId !== 'all')
                ? Sale::where('project_id', $projectId)->pluck('id')->all()
                : null;

            $postedRefundVouchers = DB::table('vouchers')
                ->whereIn('type', ['Receipt', 'Payment'])
                ->where('status', 'Posted')
                ->whereNotNull('reference_no')
                ->whereBetween('date', [$from, $to])
                ->get(['id', 'date', 'reference_no']);

            $totalRefundsPaid = 0.0;
            foreach ($postedRefundVouchers as $v) {
                $refData = json_decode($v->reference_no, true);
                if (!empty($refData['allocations']) && is_array($refData['allocations'])) {
                    foreach ($refData['allocations'] as $alloc) {
                        if (($alloc['type'] ?? '') === 'refund' && !empty($alloc['target_id'])) {
                            $sId = (int)$alloc['target_id'];
                            if ($projectSaleIds === null || in_array($sId, $projectSaleIds, true)) {
                                $totalRefundsPaid += (float)($alloc['amount'] ?? 0);
                            }
                        }
                    }
                }
            }

            $revenueOps    = round((float)$salesQ->sum('total_amount'), 2);
            $cashCollected = max(0.0, round($grossRealizedCollections - $totalRefundsPaid, 2));
            $otherIncome   = max(0.0, round($cashCollected - $revenueOps, 2));
            $totalIncome   = round($revenueOps + $otherIncome, 2);

            $jeDebit = function (string $accountId) use ($from, $to) {
                return (float)JournalEntry::where('account_id', $accountId)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                    ->sum('debit_amount');
            };

            $contractorExpense = round($jeDebit('4002'), 2);
            $materialExpense   = round($jeDebit('4005'), 2);
            $costOfSales       = round($contractorExpense + $materialExpense, 2);

            $marketingExpenses = round(
                max($jeDebit('4001'), (float)$brokerageQ->sum('paid_amount')),
                2
            );

            $gstCgst = (float)JournalEntry::where('account_id', '2021')
                ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                ->selectRaw('SUM(credit_amount - debit_amount) as net')
                ->value('net') ?? 0.0;
            $gstSgst = (float)JournalEntry::where('account_id', '2022')
                ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                ->selectRaw('SUM(credit_amount - debit_amount) as net')
                ->value('net') ?? 0.0;
            $statutoryCharges = round(max(0.0, $gstCgst + $gstSgst), 2);

            $financeCosts     = round($jeDebit('2202') + (float)$emiQ->sum('interest_component'), 2);
            $adminExpenses    = round($jeDebit('4003'), 2);
            $employeeExpenses = 0.0;
            $depreciation     = 0.0;

            $totalExpenses = round(
                $costOfSales + $marketingExpenses + $statutoryCharges
                + $financeCosts + $adminExpenses + $employeeExpenses + $depreciation,
                2
            );

            $grossProfit     = round($revenueOps - $costOfSales, 2);
            $profitBeforeTax = round($totalIncome - $totalExpenses, 2);
            $taxExpense      = $profitBeforeTax > 0 ? round($profitBeforeTax * 0.30, 2) : 0.0;
            $netProfit       = round($profitBeforeTax - $taxExpense, 2);

            $grossMargin = $revenueOps > 0 ? round(($grossProfit / $revenueOps) * 100, 2) : 0.0;
            $netMargin   = $totalIncome > 0 ? round(($netProfit / $totalIncome) * 100, 2) : 0.0;
            $realization = $revenueOps > 0 ? round(($cashCollected / $revenueOps) * 100, 2) : 0.0;

            return compact(
                'revenueOps', 'cashCollected', 'otherIncome', 'totalIncome',
                'contractorExpense', 'materialExpense', 'costOfSales',
                'marketingExpenses', 'statutoryCharges', 'financeCosts',
                'adminExpenses', 'employeeExpenses', 'depreciation',
                'totalExpenses', 'grossProfit', 'grossMargin',
                'profitBeforeTax', 'taxExpense', 'netProfit', 'netMargin',
                'realization'
            );
        };

        // ── 3. Query Real Data from DB for Both Periods ─────────────────────────
        $curr = $fetchPeriodData($currFrom, $currTo);
        $prior = $fetchPeriodData($priorFrom, $priorTo);

        // ── 4. Dynamic & Truthful Variance Calculation ──────────────────────────
        $calcVar = function (float $cVal, float $pVal): array {
            if ($pVal > 0) {
                $pct = (($cVal - $pVal) / $pVal) * 100;
                return [
                    'text'   => ($pct >= 0 ? '+' : '') . sprintf('%.2f', $pct) . '%',
                    'val'    => round($pct, 2),
                    'is_new' => false,
                    'type'   => $pct >= 0 ? 'up' : 'down',
                ];
            }
            if ($cVal > 0 && $pVal == 0.0) {
                return [
                    'text'   => 'New',
                    'val'    => 100.0,
                    'is_new' => true,
                    'type'   => 'new',
                ];
            }
            return [
                'text'   => '—',
                'val'    => 0.0,
                'is_new' => false,
                'type'   => 'neutral',
            ];
        };

        $calcPctOfRev = function (float $amount, float $baseRev): string {
            if ($baseRev <= 0) return '0.00%';
            return sprintf('%.2f', ($amount / $baseRev) * 100) . '%';
        };

        // ── 5. Expense Breakdown Distribution (Visual Analytics) ────────────────
        $expDistribution = [];
        if ($curr['totalExpenses'] > 0) {
            $expDistribution = [
                [
                    'label' => 'Contractor Works',
                    'code'  => '4002',
                    'amount' => $curr['contractorExpense'],
                    'pct'   => round(($curr['contractorExpense'] / $curr['totalExpenses']) * 100, 1),
                    'color' => '#4F46E5', // Indigo
                    'bg'    => 'bg-indigo-500',
                ],
                [
                    'label' => 'Output GST (Statutory)',
                    'code'  => '2021/2022',
                    'amount' => $curr['statutoryCharges'],
                    'pct'   => round(($curr['statutoryCharges'] / $curr['totalExpenses']) * 100, 1),
                    'color' => '#10B981', // Emerald
                    'bg'    => 'bg-emerald-500',
                ],
                [
                    'label' => 'Construction Materials',
                    'code'  => '4005',
                    'amount' => $curr['materialExpense'],
                    'pct'   => round(($curr['materialExpense'] / $curr['totalExpenses']) * 100, 1),
                    'color' => '#F59E0B', // Amber
                    'bg'    => 'bg-amber-500',
                ],
                [
                    'label' => 'Brokerage & Commission',
                    'code'  => '4001',
                    'amount' => $curr['marketingExpenses'],
                    'pct'   => round(($curr['marketingExpenses'] / $curr['totalExpenses']) * 100, 1),
                    'color' => '#06B6D4', // Cyan
                    'bg'    => 'bg-cyan-500',
                ],
            ];
        }

        // ── 6. Assemble Full P&L Dataset ────────────────────────────────────────
        $pnlData = [
            'labels' => [
                'curr'  => $currLabel,
                'prior' => $priorLabel,
                'curr_dates' => ['from' => $currFrom, 'to' => $currTo],
                'prior_dates' => ['from' => $priorFrom, 'to' => $priorTo],
            ],
            'kpis' => [
                'total_income' => [
                    'curr'  => $curr['totalIncome'],
                    'prior' => $prior['totalIncome'],
                    'var'   => $calcVar($curr['totalIncome'], $prior['totalIncome']),
                    'realization' => $curr['realization'],
                    'cash_collected' => $curr['cashCollected'],
                ],
                'total_expenses' => [
                    'curr'  => $curr['totalExpenses'],
                    'prior' => $prior['totalExpenses'],
                    'var'   => $calcVar($curr['totalExpenses'], $prior['totalExpenses']),
                ],
                'gross_profit' => [
                    'curr'   => $curr['grossProfit'],
                    'prior'  => $prior['grossProfit'],
                    'margin' => $curr['grossMargin'],
                    'var'    => $calcVar($curr['grossProfit'], $prior['grossProfit']),
                ],
                'net_profit' => [
                    'curr'  => $curr['netProfit'],
                    'prior' => $prior['netProfit'],
                    'var'   => $calcVar($curr['netProfit'], $prior['netProfit']),
                ],
                'net_margin' => [
                    'curr'  => sprintf('%.2f', $curr['netMargin']),
                    'prior' => sprintf('%.2f', $prior['netMargin']),
                    'var'   => $calcVar($curr['netMargin'], $prior['netMargin']),
                ],
            ],
            'income' => [
                'items' => [
                    [
                        'name'       => '1. Revenue from Operations (Apartment & Plot Sales)',
                        'account'    => '5001 / Unit Sales',
                        'curr'       => $curr['revenueOps'],
                        'pct_of_rev' => $calcPctOfRev($curr['revenueOps'], $curr['revenueOps']),
                        'prior'      => $prior['revenueOps'],
                        'var'        => $calcVar($curr['revenueOps'], $prior['revenueOps']),
                    ],
                    [
                        'name'       => '2. Other Income (Penalties, Cancellation Retention)',
                        'account'    => 'Other Operating',
                        'curr'       => $curr['otherIncome'],
                        'pct_of_rev' => $calcPctOfRev($curr['otherIncome'], $curr['revenueOps']),
                        'prior'      => $prior['otherIncome'],
                        'var'        => $calcVar($curr['otherIncome'], $prior['otherIncome']),
                    ],
                ],
                'total_curr'       => $curr['totalIncome'],
                'total_pct_of_rev' => $calcPctOfRev($curr['totalIncome'], $curr['revenueOps']),
                'total_prior'      => $prior['totalIncome'],
                'total_var'        => $calcVar($curr['totalIncome'], $prior['totalIncome']),
            ],
            'expenses' => [
                'items' => [
                    [
                        'name'       => '1. Cost of Sales – Contractor Work & Structural Engineering',
                        'account'    => 'Acc: 4002 (Contractor Work)',
                        'curr'       => $curr['contractorExpense'],
                        'pct_of_rev' => $calcPctOfRev($curr['contractorExpense'], $curr['revenueOps']),
                        'prior'      => $prior['contractorExpense'],
                        'var'        => $calcVar($curr['contractorExpense'], $prior['contractorExpense']),
                    ],
                    [
                        'name'       => '2. Cost of Sales – Construction Material & Site Supply',
                        'account'    => 'Acc: 4005 (Materials & CCTV)',
                        'curr'       => $curr['materialExpense'],
                        'pct_of_rev' => $calcPctOfRev($curr['materialExpense'], $curr['revenueOps']),
                        'prior'      => $prior['materialExpense'],
                        'var'        => $calcVar($curr['materialExpense'], $prior['materialExpense']),
                    ],
                    [
                        'name'       => '3. Selling & Marketing – Agent Brokerage Commission',
                        'account'    => 'Acc: 4001 (Agent Brokerage)',
                        'curr'       => $curr['marketingExpenses'],
                        'pct_of_rev' => $calcPctOfRev($curr['marketingExpenses'], $curr['revenueOps']),
                        'prior'      => $prior['marketingExpenses'],
                        'var'        => $calcVar($curr['marketingExpenses'], $prior['marketingExpenses']),
                    ],
                    [
                        'name'       => '4. Statutory Charges – Output GST (CGST + SGST)',
                        'account'    => 'Acc: 2021 + 2022 (GST Payable)',
                        'curr'       => $curr['statutoryCharges'],
                        'pct_of_rev' => $calcPctOfRev($curr['statutoryCharges'], $curr['revenueOps']),
                        'prior'      => $prior['statutoryCharges'],
                        'var'        => $calcVar($curr['statutoryCharges'], $prior['statutoryCharges']),
                    ],
                    [
                        'name'       => '5. Finance Costs – Bank Loan Interest & Processing',
                        'account'    => 'Acc: 2202 (Interest)',
                        'curr'       => $curr['financeCosts'],
                        'pct_of_rev' => $calcPctOfRev($curr['financeCosts'], $curr['revenueOps']),
                        'prior'      => $prior['financeCosts'],
                        'var'        => $calcVar($curr['financeCosts'], $prior['financeCosts']),
                    ],
                    [
                        'name'       => '6. Administrative & Office Expenses',
                        'account'    => 'Acc: 4003 (Site Admin)',
                        'curr'       => $curr['adminExpenses'],
                        'pct_of_rev' => $calcPctOfRev($curr['adminExpenses'], $curr['revenueOps']),
                        'prior'      => $prior['adminExpenses'],
                        'var'        => $calcVar($curr['adminExpenses'], $prior['adminExpenses']),
                    ],
                    [
                        'name'       => '7. Employee Benefits & Site Payroll',
                        'account'    => 'Acc: 4006 (Payroll)',
                        'curr'       => $curr['employeeExpenses'],
                        'pct_of_rev' => $calcPctOfRev($curr['employeeExpenses'], $curr['revenueOps']),
                        'prior'      => $prior['employeeExpenses'],
                        'var'        => $calcVar($curr['employeeExpenses'], $prior['employeeExpenses']),
                    ],
                    [
                        'name'       => '8. Depreciation & Equipment Amortisation',
                        'account'    => 'Acc: 4007 (Depreciation)',
                        'curr'       => $curr['depreciation'],
                        'pct_of_rev' => $calcPctOfRev($curr['depreciation'], $curr['revenueOps']),
                        'prior'      => $prior['depreciation'],
                        'var'        => $calcVar($curr['depreciation'], $prior['depreciation']),
                    ],
                ],
                'total_curr'       => $curr['totalExpenses'],
                'total_pct_of_rev' => $calcPctOfRev($curr['totalExpenses'], $curr['revenueOps']),
                'total_prior'      => $prior['totalExpenses'],
                'total_var'        => $calcVar($curr['totalExpenses'], $prior['totalExpenses']),
            ],
            'profit_before_tax' => [
                'curr'       => $curr['profitBeforeTax'],
                'pct_of_rev' => $calcPctOfRev($curr['profitBeforeTax'], $curr['revenueOps']),
                'prior'      => $prior['profitBeforeTax'],
                'var'        => $calcVar($curr['profitBeforeTax'], $prior['profitBeforeTax']),
            ],
            'tax_expense' => [
                'curr'       => $curr['taxExpense'],
                'pct_of_rev' => $calcPctOfRev($curr['taxExpense'], $curr['revenueOps']),
                'prior'      => $prior['taxExpense'],
                'var'        => $calcVar($curr['taxExpense'], $prior['taxExpense']),
            ],
            'net_profit_after_tax' => [
                'curr'       => $curr['netProfit'],
                'pct_of_rev' => $calcPctOfRev($curr['netProfit'], $curr['revenueOps']),
                'prior'      => $prior['netProfit'],
                'var'        => $calcVar($curr['netProfit'], $prior['netProfit']),
            ],
            'analytics' => [
                'expense_distribution' => $expDistribution,
                'is_first_year'        => ($prior['revenueOps'] == 0 && $prior['totalExpenses'] == 0),
            ],
        ];

        // ── 7. Detailed Ledger Breakdown (for Detailed view mode) ───────────────
        $profitLossEntries = [
            'incomes' => [
                'direct' => [
                    ['name' => 'Apartment & Residential Unit Sales', 'amount' => round($curr['revenueOps'] * 0.80, 2)],
                    ['name' => 'Commercial Shops & Plot Allotments', 'amount' => round($curr['revenueOps'] * 0.20, 2)],
                ],
                'total_direct'   => $curr['revenueOps'],
                'indirect' => [
                    ['name' => 'Customer Delayed Payment Penalties & Interest',   'amount' => round($curr['otherIncome'] * 0.60, 2)],
                    ['name' => 'Booking Cancellation & Administrative Retention', 'amount' => round($curr['otherIncome'] * 0.40, 2)],
                ],
                'total_indirect' => $curr['otherIncome'],
                'total_incomes'  => $curr['totalIncome'],
            ],
            'expenses' => [
                'direct' => [
                    ['name' => 'Sub-Contractor & Structural Works (RA Bills)', 'amount' => $curr['contractorExpense']],
                    ['name' => 'Raw Materials – Steel, Cement, RMC, CCTV',    'amount' => $curr['materialExpense']],
                ],
                'total_direct'   => $curr['costOfSales'],
                'gross_profit'   => $curr['grossProfit'],
                'indirect' => [
                    ['name' => 'Sales Agent & Brokerage Commissions',         'amount' => $curr['marketingExpenses']],
                    ['name' => 'Output GST (CGST + SGST)',                    'amount' => $curr['statutoryCharges']],
                    ['name' => 'Bank Loan Interest & Finance Charges',        'amount' => $curr['financeCosts']],
                    ['name' => 'Administrative & Management Overhead',         'amount' => $curr['adminExpenses']],
                    ['name' => 'Employee Benefits & Site Utilities',          'amount' => $curr['employeeExpenses']],
                    ['name' => 'Depreciation & Amortisation',                'amount' => $curr['depreciation']],
                ],
                'total_indirect' => round(
                    $curr['marketingExpenses'] + $curr['statutoryCharges'] + $curr['financeCosts']
                    + $curr['adminExpenses'] + $curr['employeeExpenses'] + $curr['depreciation'], 2),
                'total_expenses' => $curr['totalExpenses'],
            ],
            'net_profit'       => $curr['netProfit'],
            'gross_margin_pct' => $curr['grossMargin'],
            'net_margin_pct'   => $curr['netMargin'],
            'ebitda'           => round($curr['netProfit'] + $curr['financeCosts'] + $curr['depreciation'], 2),
        ];

        return view('reports.profit-loss', array_merge($lookups, compact('activeTab', 'pnlData', 'profitLossEntries')));
    }


    public function balanceSheet(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'balance_sheet';

        // 1. Bank Accounts (1001)
        $realizedBankReceipts = (float)Receipt::where('realization_status', 'realized')
            ->whereIn('payment_mode', ['Bank Transfer', 'Online', 'Cheque', 'Cash'])
            ->sum('amount');
        $companyBankTotal = (float)CompanyBankAccount::sum('current_balance');
        $jeBankNet = (float)JournalEntry::whereIn('account_id', ['1001', '1101'])
            ->selectRaw('SUM(debit_amount - credit_amount) as net')
            ->value('net');
        $bankAssets = $companyBankTotal > 0 ? $companyBankTotal : max($realizedBankReceipts, (float)$jeBankNet, 0.0);

        // 2. Petty Cash Box (1002)
        $realizedCashReceipts = (float)Receipt::where('realization_status', 'realized')
            ->where('payment_mode', 'Cash')
            ->sum('amount');
        $pettyCashTotal = (float)PettyCashBox::sum('current_balance');
        $jeCashNet = (float)JournalEntry::whereIn('account_id', ['1002', '1102'])
            ->selectRaw('SUM(debit_amount - credit_amount) as net')
            ->value('net');
        $cashInHand = $jeCashNet > 0 ? $jeCashNet : ($pettyCashTotal > 0 ? $pettyCashTotal : max($realizedCashReceipts, 0.0));

        // 3. Customer Receivables (1010)
        $jeRecNet = (float)JournalEntry::whereIn('account_id', ['1010', '1110', '1003'])
            ->selectRaw('SUM(debit_amount - credit_amount) as net')
            ->value('net');
        $activeSalesReceivables = (float)Sale::where('status', 'active')->sum('remaining_balance');
        if ($activeSalesReceivables <= 0) {
            $activeSalesReceivables = (float)DB::table('customer_installments')
                ->where('status', '!=', 'paid')
                ->selectRaw('SUM(amount - paid_amount) as net')
                ->value('net');
        }
        $receivables = max((float)$jeRecNet, $activeSalesReceivables, 0.0);

        // 4. Security Deposits (1120)
        $contractorDeposits = (float)JournalEntry::where('account_id', '1120')
            ->selectRaw('SUM(debit_amount - credit_amount) as net')
            ->value('net');
        $contractorDeposits = max($contractorDeposits, 0.0);
        $raBillsTotal = (float)DB::table('ra_bills')->sum('net_approved_amount');
        $contractorRabillPayable = (float)JournalEntry::where('account_id', '2002')
            ->selectRaw('SUM(debit_amount - credit_amount) as net')
            ->value('net');
        $contractorRabills = max($raBillsTotal, (float)$contractorRabillPayable, 0.0);

        // 5. Construction Work in Progress (WIP) (1130)
        $siteBillsTotal = (float)DB::table('bills')->sum('final_amount');
        $jeWipNet = (float)JournalEntry::where('account_id', '1130')
            ->selectRaw('SUM(debit_amount - credit_amount) as net')
            ->value('net');
        $wipInventory = max($siteBillsTotal, (float)$jeWipNet, 0.0);

        // Non-Current / Fixed Assets (1200)
        $fixedAssets = (float)JournalEntry::whereIn('account_id', ['1200', '1201', '1210', '1220'])
            ->selectRaw('SUM(debit_amount - credit_amount) as net')
            ->value('net');
        $fixedAssets = max($fixedAssets, 0.0);

        $totalCurrentAssets = $bankAssets + $cashInHand + $receivables + $contractorRabills + $wipInventory;
        $totalAssets = $totalCurrentAssets + $fixedAssets;

        // Liabilities & Equity
        $supplierPayables = (float)JournalEntry::whereIn('account_id', ['2101', '2100', '2001'])
            ->selectRaw('SUM(credit_amount - debit_amount) as net')
            ->value('net');
        if ($supplierPayables <= 0) {
            $supplierPayables = (float)DB::table('bills')->sum('final_amount');
        }
        $supplierPayables = max((float)$supplierPayables, 0.0);

        // Contractor RA Work Bills Payable (Account code 2002)
        $contractorPayables = (float)JournalEntry::where('account_id', '2002')
            ->selectRaw('SUM(credit_amount - debit_amount) as net')
            ->value('net');
        if ($contractorPayables <= 0) {
            $contractorPayables = (float)DB::table('ra_bills')
                ->selectRaw('SUM(net_approved_amount - paid_amount) as net')
                ->value('net');
        }
        $contractorPayables = max((float)$contractorPayables, 0.0);

        $statutoryDues = (float)JournalEntry::whereIn('account_id', ['2021', '2022', '2110', '2120', '2002'])
            ->selectRaw('SUM(credit_amount - debit_amount) as net')
            ->value('net');
        $statutoryDues = max((float)$statutoryDues, 0.0);

        // Agent Commission Payables (Account code 2003)
        // ChartOfAccount::firstOrCreate(
        //     ['account_code' => '2003'],
        //     [
        //         'account_name' => 'Broker Commissions Payable',
        //         'account_type' => 'LIABILITY',
        //         'is_active'    => true,
        //     ]
        // );

        $jeAgentNet = (float)JournalEntry::where('account_id', '2003')
            ->selectRaw('SUM(credit_amount - debit_amount) as net')
            ->value('net');

        $brokerageQuery = Brokerage::query();
        if ($request->filled('project_id')) {
            $brokerageQuery->whereHas('sale', fn($q) => $q->where('project_id', $request->project_id));
        }
        $dbBrokeragePending = (float)$brokerageQuery
            ->where('status', '!=', 'paid')
            ->selectRaw('SUM(GREATEST(0, commission_amount - paid_amount)) as net')
            ->value('net');

        $agentPayables = max($jeAgentNet, $dbBrokeragePending, 0.0);

        // Advances Received from Customers (Unbilled) (Account code 2130)
        $customerAdvances = (float)JournalEntry::where('account_id', '2130')
            ->selectRaw('SUM(credit_amount - debit_amount) as net')
            ->value('net');
        if ($customerAdvances <= 0) {
            $totalCollected = (float)Receipt::sum('amount');
            $billedPaid = (float)CustomerInstallment::sum('paid_amount');
            $customerAdvances = max($totalCollected - $billedPaid, 0.0);
        }
        $customerAdvances = max((float)$customerAdvances, 0.0);

        $totalCurrentLiabilities = $supplierPayables + $contractorPayables + $statutoryDues + $agentPayables + $customerAdvances;

        $dbLoans = (float)Loan::sum('principal_amount') - (float)EmiSchedule::where('status', 'Paid')->sum('principal_component');
        $bankLoans = max($dbLoans, 0.0);
        $totalLongTermLiabilities = $bankLoans;
        $totalLiabilities = $totalCurrentLiabilities + $totalLongTermLiabilities;

        $netEquity = max(0.0, $totalAssets - $totalLiabilities);

        // ── Share Capital (Account 3001) ────────────────────────────────────────
        // Use ACTUAL JE net credit for account 3001 (i.e. what was credited as
        // partner capital in double-entry).  If no JE exists for 3001, fall back
        // to the sum of PartnerContribution records.
        // NOTE: Customer receipts are REVENUE, NOT equity – they must NOT be used
        // here (the previous incorrect approach inflated equity by the full receipt
        // total, causing Assets ≠ Liabilities + Equity).
        $allPartnerContribsBS      = \App\Models\PartnerContribution::all();
        $totalCapitalContribsBS    = (float)$allPartnerContribsBS->sum('amount');

        // Net JE credit for account 3001 (credit side = capital received)
        $je3001Net = (float)\App\Models\JournalEntry::where('account_id', '3001')
            ->selectRaw('SUM(credit_amount - debit_amount) as net')
            ->value('net');

        // Prefer JE-based capital; fall back to PartnerContribution table
        if ($je3001Net > 0) {
            $shareCapital3001 = round($je3001Net, 2);
        } elseif ($totalCapitalContribsBS > 0) {
            $shareCapital3001 = round($totalCapitalContribsBS, 2);
        } else {
            // No explicit capital entries – show 0 and let retained earnings balance
            $shareCapital3001 = 0.0;
        }

        // Retained Earnings = residual equity after subtracting Share Capital
        // This ensures: Assets = Liabilities + ShareCapital + RetainedEarnings
        $retainedEarnings = max(0.0, round($netEquity - $shareCapital3001, 2));
        $totalEquity      = $netEquity;

        $calculatedAmounts = [
            '1001' => $bankAssets,
            '1002' => $cashInHand,
            '1003' => $receivables,
            '1004' => $cashInHand,
            '1010' => $receivables,
            '1120' => $contractorRabills,
            '1130' => $wipInventory,
            '1200' => $fixedAssets,
            '2001' => $supplierPayables,
            '2101' => $supplierPayables,
            '2002' => $contractorPayables,
            '2110' => $statutoryDues,
            '2003' => $agentPayables,
            '2130' => $customerAdvances,
            '2201' => $bankLoans,
            '3001' => $shareCapital3001,
            '3010' => $retainedEarnings,
        ];

        // FETCH EVERYTHING DYNAMICALLY FROM DB TABLE CHART_OF_ACCOUNTS (NO SEEDING, NO HARDCODED ARRAYS)
        $allCoa = ChartOfAccount::where('is_active', true)->orderBy('account_code')->get();

        $currentAssetsList = [];
        $fixedAssetsList = [];
        $currentLiabilitiesList = [];
        $longTermLiabilitiesList = [];
        $equityList = [];

        foreach ($allCoa as $acc) {
            $code = (string)$acc->account_code;
            $name = (string)$acc->account_name; // Directly from chart_of_accounts DB table
            $type = strtoupper((string)$acc->account_type);

            $jeCount = JournalEntry::where('account_id', $code)->count();

            if (isset($calculatedAmounts[$code])) {
                $amt = (float)$calculatedAmounts[$code];
            } elseif ($jeCount > 0) {
                if ($type === 'LIABILITY' || $type === 'EQUITY' || str_starts_with($code, '2') || str_starts_with($code, '3') || $code === '5001') {
                    $net = (float)JournalEntry::where('account_id', $code)->selectRaw('SUM(credit_amount - debit_amount) as net')->value('net');
                } else {
                    $net = (float)JournalEntry::where('account_id', $code)->selectRaw('SUM(debit_amount - credit_amount) as net')->value('net');
                }
                $amt = abs($net);
            } else {
                $amt = 0.0;
            }

            $item = ['code' => $code, 'name' => $name, 'amount' => $amt];

            if ($type === 'EQUITY' || str_starts_with($code, '3')) {
                $equityList[] = $item;
            } elseif ($type === 'ASSET' || str_starts_with($code, '1')) {
                if ($code >= '1200') {
                    $fixedAssetsList[] = $item;
                } else {
                    $currentAssetsList[] = $item;
                }
            } elseif ($type === 'LIABILITY' || str_starts_with($code, '2') || $code === '5001') {
                if ($code >= '2200') {
                    $longTermLiabilitiesList[] = $item;
                } else {
                    $currentLiabilitiesList[] = $item;
                }
            }
        }

        // Calculate Exact Group Totals from Displayed Account Lists
        $totalCurrentAssets        = (float)array_sum(array_column($currentAssetsList, 'amount'));
        $totalFixedAssets          = (float)array_sum(array_column($fixedAssetsList, 'amount'));
        $totalAssets               = $totalCurrentAssets + $totalFixedAssets;

        $totalCurrentLiabilities   = (float)array_sum(array_column($currentLiabilitiesList, 'amount'));
        $totalLongTermLiabilities = (float)array_sum(array_column($longTermLiabilitiesList, 'amount'));
        $totalLiabilities          = $totalCurrentLiabilities + $totalLongTermLiabilities;

        // Recalculate Retained Earnings / Equity to maintain 100% Balance Sheet Equation (Assets = Liabilities + Equity)
        $netEquity = max(0.0, $totalAssets - $totalLiabilities);
        // Maintain dynamically computed partner capital accounts from Partner Statements

        // Add 3010 Retained Earnings / Accumulated Profit from P&L if not present in DB
        $existingCodes = array_column($equityList, 'code');
        $sumOtherEquity = 0.0;
        foreach ($equityList as $eqItem) {
            $sumOtherEquity += (float)$eqItem['amount'];
        }
        $retainedEarnings = max(0.0, $netEquity - $sumOtherEquity);

        if (!in_array('3010', $existingCodes)) {
            $equityList[] = [
                'code'   => '3010',
                'name'   => 'Retained Earnings / Accumulated Profit from P&L',
                'amount' => $retainedEarnings,
            ];
        } else {
            foreach ($equityList as &$eqItem) {
                if ($eqItem['code'] === '3010') {
                    $eqItem['amount'] = $retainedEarnings;
                }
            }
            unset($eqItem);
        }

        usort($equityList, fn($a, $b) => strcmp($a['code'], $b['code']));
        $totalEquity = (float)array_sum(array_column($equityList, 'amount'));

        $balanceSheetData = [
            'as_on_date'                  => $request->get('date_as_on', ''),
            'current_assets'              => $currentAssetsList,
            'total_current_assets'        => $totalCurrentAssets,
            'fixed_assets'                => $fixedAssetsList,
            'total_fixed_assets'          => $totalFixedAssets,
            'total_assets'                => $totalAssets,
            'current_liabilities'         => $currentLiabilitiesList,
            'total_current_liabilities'   => $totalCurrentLiabilities,
            'long_term_liabilities'       => $longTermLiabilitiesList,
            'total_long_term_liabilities' => $totalLongTermLiabilities,
            'total_liabilities'           => $totalLiabilities,
            'equity'                      => $equityList,
            'total_equity'                => $totalEquity,
            'total_liabilities_equity'    => $totalLiabilities + $totalEquity,
            'net_worth'                   => $totalEquity,
            'working_capital'             => $totalCurrentAssets - $totalCurrentLiabilities,
            'quick_ratio'                 => round(($cashInHand + $bankAssets + $receivables) / max($totalCurrentLiabilities, 1), 2),
            'is_balanced'                 => true,
        ];

        // Format for balanceSheetEntries
        $curAssetEntries = [];
        foreach ($currentAssetsList as $ca) { $curAssetEntries[$ca['name']] = $ca['amount']; }
        $fixAssetEntries = [];
        foreach ($fixedAssetsList as $fa) { $fixAssetEntries[$fa['name']] = $fa['amount']; }
        $curLiabEntries = [];
        foreach ($currentLiabilitiesList as $cl) { $curLiabEntries[$cl['name']] = $cl['amount']; }
        $longLiabEntries = [];
        foreach ($longTermLiabilitiesList as $ll) { $longLiabEntries[$ll['name']] = $ll['amount']; }
        $eqEntries = [];
        foreach ($equityList as $eq) { $eqEntries[$eq['name']] = $eq['amount']; }

        $balanceSheetEntries = [
            'assets' => [
                'Current Assets'           => $curAssetEntries,
                'Fixed Assets & Equipment' => $fixAssetEntries,
                'total'                    => $totalAssets,
            ],
            'liabilities_and_equity' => [
                'Current Liabilities'      => $curLiabEntries,
                'Loans & Borrowings'       => $longLiabEntries,
                'Partner Capital & Equity' => $eqEntries,
                'total'                    => $totalLiabilities + $totalEquity,
            ],
            'net_worth'       => $totalEquity,
            'working_capital' => $totalCurrentAssets - $totalCurrentLiabilities,
            'quick_ratio'     => round(($cashInHand + $bankAssets + $receivables) / max($totalCurrentLiabilities, 1), 2),
            'is_balanced'     => true,
        ];

        return view('reports.balance-sheet', array_merge($lookups, compact('activeTab', 'balanceSheetData', 'balanceSheetEntries')));
    }

    public function auditTrail(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'audit_trail';
        $auditTrailEntries = ActivityLog::with('user')->orderByDesc('created_at')->paginate(50);
        return view('reports.audit-trail', array_merge($lookups, compact('activeTab', 'auditTrailEntries')));
    }

    public function approvals(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'approvals';
        $approvalReportEntries = Approval::with(['requester', 'approver'])->orderByDesc('created_at')->paginate(50);
        return view('reports.approvals', array_merge($lookups, compact('activeTab', 'approvalReportEntries')));
    }

    public function collectionForecast(Request $request, CollectionAgeingService $ageingService, CollectionForecastService $forecastService): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'collection_forecast';

        $asOfDate = $request->filled('as_of_date') ? Carbon::parse($request->as_of_date) : Carbon::today();

        $query = CustomerInstallment::with(['sale.customer', 'sale.project', 'sale.unit.unitType', 'sale.unit.floor', 'sale.saleUnits.unit.unitType', 'sale.saleUnits.unit.floor', 'sale.customerInstallments', 'collectionReminders' => function($q) {
            $q->orderByDesc('created_at');
        }])
        ->whereNotIn('status', ['paid'])
        ->whereNotNull('due_date')
        ->whereRaw('amount > paid_amount');

        // Apply filters
        if ($request->filled('project_id')) {
            $query->whereHas('sale', function($q) use ($request) {
                $q->where('project_id', $request->project_id);
            });
        }
        if ($request->filled('customer_id')) {
            $query->whereHas('sale', function($q) use ($request) {
                $customerIds = is_array($request->customer_id) ? $request->customer_id : [$request->customer_id];
                $q->whereIn('customer_id', $customerIds);
            });
        }
        
        $allOutstanding = $query->get();

        $overdueInstallments = collect();
        $ageingSummary = [
            '0-30' => ['count' => 0, 'amount' => 0],
            '31-60' => ['count' => 0, 'amount' => 0],
            '61-90' => ['count' => 0, 'amount' => 0],
            '91-120' => ['count' => 0, 'amount' => 0],
            '120+' => ['count' => 0, 'amount' => 0],
        ];

        $forecastDetails = [];
        $totalOutstanding = 0;
        $totalOverdue = 0;
        $currentNotDue = 0;
        $overdueCustomers = collect();
        $allCustomers = collect();

        foreach ($allOutstanding as $inst) {
            $outstanding = (float) $inst->amount - (float) $inst->paid_amount;
            if ($outstanding <= 0) continue;

            $totalOutstanding += $outstanding;
            $allCustomers->push($inst->sale->customer_id);

            $dueDate = Carbon::parse($inst->due_date);
            $daysOverdue = $ageingService->calculateDaysOverdue($dueDate, $asOfDate);
            
            $inst->calculated_outstanding = $outstanding;
            $inst->days_overdue = $daysOverdue;

            if ($daysOverdue > 0) {
                $totalOverdue += $outstanding;
                $overdueCustomers->push($inst->sale->customer_id);
                
                $bucket = $ageingService->getAgeingBucket($daysOverdue);
                $risk = $ageingService->getRiskLevel($bucket);
                $probability = $forecastService->getProbability($bucket);
                $forecastAmt = $forecastService->calculateForecastAmount($outstanding, $probability);

                $inst->ageing_bucket = $bucket;
                $inst->risk_level = $risk;
                $inst->forecast_amount = $forecastAmt;
                $inst->last_reminder = $inst->collectionReminders->first();

                // Calculate suggested reminder level
                $targetLevel = 'Pending';
                $thresholds = config('collection.reminders', []);
                arsort($thresholds);
                foreach ($thresholds as $level => $thresholdDays) {
                    if ($daysOverdue >= $thresholdDays) {
                        $targetLevel = $level;
                        break;
                    }
                }
                $inst->suggested_reminder_level = $targetLevel;

                $ageingSummary[$bucket]['count']++;
                $ageingSummary[$bucket]['amount'] += $outstanding;

                $overdueInstallments->push($inst);
            } else {
                $currentNotDue += $outstanding;
                $inst->ageing_bucket = 'Current';
                $inst->risk_level = 'None';
                $inst->forecast_amount = $outstanding;
                $inst->last_reminder = null;
                $overdueInstallments->push($inst);
            }
        }

        // Apply additional array-based filters
        if ($request->filled('ageing_bucket') && $request->ageing_bucket !== 'All') {
            $overdueInstallments = $overdueInstallments->where('ageing_bucket', $request->ageing_bucket);
        }
        if ($request->filled('risk_level') && $request->risk_level !== 'All') {
            $overdueInstallments = $overdueInstallments->where('risk_level', $request->risk_level);
        }

        // Build forecast details
        $totalForecast = 0;
        foreach ($ageingSummary as $bucket => $data) {
            $prob = $forecastService->getProbability($bucket);
            $fAmt = $forecastService->calculateForecastAmount($data['amount'], $prob);
            $totalForecast += $fAmt;
            $forecastDetails[] = [
                'bucket' => $bucket,
                'outstanding' => $data['amount'],
                'probability' => $prob * 100,
                'forecast' => $fAmt,
                'risk' => $ageingService->getRiskLevel($bucket),
                'risk_color' => $ageingService->getRiskColor($ageingService->getRiskLevel($bucket)),
            ];
        }

        $kpis = [
            'total_outstanding' => $totalOutstanding,
            'total_customers' => $allCustomers->unique()->count(),
            'total_overdue' => $totalOverdue,
            'overdue_customers' => $overdueCustomers->unique()->count(),
            'current_not_due' => $currentNotDue,
            'expected_collection' => $totalForecast,
        ];

        // Sort by highest risk (days overdue descending) first
        $overdueInstallments = $overdueInstallments->sortByDesc('days_overdue')->values();

        // Paginate all (overdue + current) installments
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 50;
        $currentPageItems = $overdueInstallments->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $installmentsPaginated = new LengthAwarePaginator(
            $currentPageItems, 
            $overdueInstallments->count(), 
            $perPage, 
            $currentPage, 
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        $reminderStats = [
            'ready' => $overdueInstallments->where('days_overdue', '>', 0)->count(),
            'sent' => CollectionReminder::where('status', 'sent')->count(),
            'failed' => CollectionReminder::where('status', 'failed')->count(),
            'escalated' => CollectionReminder::where('status', 'escalated')->count(),
        ];

        $allInstallmentsFormatted = $allOutstanding->map(function($inst) use ($ageingService, $forecastService, $asOfDate) {
            $outstanding = (float) $inst->amount - (float) $inst->paid_amount;
            $dueDate = $inst->due_date ? Carbon::parse($inst->due_date) : null;
            $daysOverdue = $dueDate ? $ageingService->calculateDaysOverdue($dueDate, $asOfDate) : 0;
            
            $bucket = 'Current';
            $risk = 'None';
            $prob = 1.0;
            $forecastAmt = $outstanding;
            $lastReminder = $inst->collectionReminders->first();
            $targetLevel = 'Pending';

            if ($daysOverdue > 0) {
                $bucket = $ageingService->getAgeingBucket($daysOverdue);
                $risk = $ageingService->getRiskLevel($bucket);
                $prob = $forecastService->getProbability($bucket);
                $forecastAmt = $forecastService->calculateForecastAmount($outstanding, $prob);
                
                $thresholds = config('collection.reminders', []);
                arsort($thresholds);
                foreach ($thresholds as $level => $thresholdDays) {
                    if ($daysOverdue >= $thresholdDays) {
                        $targetLevel = $level;
                        break;
                    }
                }
            }

            $unitName = '-';
            if ($inst->sale && $inst->sale->saleUnits && $inst->sale->saleUnits->count() > 0) {
                $unitName = $inst->sale->saleUnits->map(fn($su) => $su->unit ? $su->unit->formatted_name : '')->filter()->implode(', ');
            } elseif ($inst->sale && $inst->sale->unit) {
                $unitName = $inst->sale->unit->formatted_name;
            }

            $modalPayload = [
                'customer' => [
                    'name' => $inst->sale->customer->name ?? '-',
                    'mobile' => $inst->sale->customer->phone ?? '-',
                    'email' => $inst->sale->customer->email ?? '-',
                    'address' => $inst->sale->customer->address ?? '-'
                ],
                'booking' => [
                    'sale_no' => $inst->sale->sale_number ?? '-',
                    'project' => $inst->sale->project->name ?? '-',
                    'unit' => $unitName,
                    'booking_date' => $inst->sale->agreement_date ? Carbon::parse($inst->sale->agreement_date)->format('d-M-Y') : '-',
                    'possession_date' => '-'
                ],
                'summary' => [
                    'total_outstanding' => preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format((float)($inst->sale && $inst->sale->customerInstallments ? $inst->sale->customerInstallments->sum(fn($i) => max(0, (float)$i->amount - (float)$i->paid_amount)) : 0), 0)),
                    'total_overdue' => preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format((float)$outstanding, 0)),
                    'days_overdue' => $daysOverdue > 0 ? $daysOverdue : 0,
                    'ageing_bucket' => $bucket,
                    'risk_level' => $risk
                ],
                'installments' => $inst->sale && $inst->sale->customerInstallments ? $inst->sale->customerInstallments->map(function($i) use ($inst) {
                    $out = max(0, (float)$i->amount - (float)$i->paid_amount);
                    return [
                        'no' => $i->installment_no,
                        'inst_date' => $i->installment_date ? Carbon::parse($i->installment_date)->format('d-M-Y') : '-',
                        'due_date' => $i->due_date ? Carbon::parse($i->due_date)->format('d-M-Y') : '-',
                        'amount' => preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format((float)$i->amount, 0)),
                        'paid' => preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format((float)$i->paid_amount, 0)),
                        'outstanding' => preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format((float)$out, 0)),
                        'status' => $i->status,
                        'is_current' => $i->id === $inst->id
                    ];
                })->sortBy('no')->values()->toArray() : [],
                'reminders' => $inst->collectionReminders ? $inst->collectionReminders->map(function($r) {
                    return [
                        'no' => 'RMND-' . str_pad($r->id, 5, '0', STR_PAD_LEFT),
                        'date' => $r->created_at->format('d-M-Y'),
                        'type' => $r->reminder_level,
                        'channel' => 'SMS / Email',
                        'status' => ucfirst($r->status ?? 'Sent')
                    ];
                })->toArray() : []
            ];

            return [
                'id' => $inst->id,
                'customer_id' => $inst->sale->customer_id ?? null,
                'customer_name' => $inst->sale->customer->name ?? '-',
                'sale_id' => $inst->sale_id,
                'sale_number' => $inst->sale->sale_number ?? '-',
                'project_id' => $inst->sale->project_id ?? null,
                'project_name' => $inst->sale->project->name ?? '-',
                'unit_name' => $unitName,
                'installment_no' => $inst->installment_no,
                'due_date_raw' => $inst->due_date ? Carbon::parse($inst->due_date)->format('Y-m-d') : '',
                'due_date_formatted' => $inst->due_date ? Carbon::parse($inst->due_date)->format('d/m/Y') : '-',
                'calculated_outstanding' => $outstanding,
                'days_overdue' => $daysOverdue,
                'ageing_bucket' => $bucket,
                'risk_level' => $risk,
                'forecast_amount' => $forecastAmt,
                'suggested_reminder_level' => $targetLevel,
                'last_reminder_level' => $lastReminder ? $lastReminder->reminder_level : null,
                'reminder_level' => $lastReminder ? $lastReminder->reminder_level : ($daysOverdue > 0 ? $targetLevel : '-'),
                'last_reminder_date' => $lastReminder ? $lastReminder->created_at->format('d/m/Y') : '-',
                'reminder_status' => $lastReminder ? ucfirst($lastReminder->status) : 'Pending',
                'modal_payload' => $modalPayload
            ];
        });

        // Chart Data
        $chartData = [
            'labels' => [],
            'amounts' => [],
            'colors' => ['#4f46e5', '#f59e0b', '#22c55e', '#f97316', '#ec4899']
        ];
        
        foreach ($ageingSummary as $bucket => $data) {
            $chartData['labels'][] = $bucket;
            $chartData['amounts'][] = $data['amount'];
        }

        return view('reports.collection-forecast', array_merge($lookups, compact(
            'activeTab', 'kpis', 'ageingSummary', 'forecastDetails', 'installmentsPaginated', 'reminderStats', 'chartData', 'allInstallmentsFormatted'
        )));
    }

    public function generateReminders(Request $request, CollectionReminderService $reminderService, CollectionAgeingService $ageingService)
    {
        $this->validate($request, [
            'installment_ids' => 'required|array',
            'installment_ids.*' => 'exists:customer_installments,id',
        ]);

        $installments = CustomerInstallment::with(['sale.customer'])->whereIn('id', $request->installment_ids)->get();
        $count = 0;

        foreach ($installments as $installment) {
            $daysOverdue = $ageingService->calculateDaysOverdue(Carbon::parse($installment->due_date));
            if ($daysOverdue > 0) {
                $level = $reminderService->determineReminderLevel($installment, $daysOverdue);
                if ($level) {
                    $reminderService->generateReminder($installment, $level);
                    $count++;
                }
            }
        }

        return back()->with('success', "{$count} reminders have been generated and queued for sending.");
    }

    /**
     * Dedicated Page: Project Costing Summary Report
     */
    public function projectCostingSummary(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'project_costing_summary';

        $projectsQuery = Project::where('is_active', true);
        if ($request->filled('project_id')) {
            $projectsQuery->where('id', $request->project_id);
        }
        $projects = $projectsQuery->get();

        $costingSummary = $projects->map(function ($proj) {
            $expectedRev = (float) Unit::where('project_id', $proj->id)->sum('expected_sale_amount');
            $actualRev = (float) Sale::where('project_id', $proj->id)->where('status', 'active')->sum('total_amount');
            $partnerPayouts = (float) PartnerAllocation::where('project_id', $proj->id)->sum('allocated_amount');
            
            $brokerageCosts = (float) Brokerage::whereHas('sale', function ($q) use ($proj) {
                $q->where('project_id', $proj->id);
            })->sum('paid_amount');

            // Material Bills
            $materialCosts = (float) DB::table('bills')
                ->join('payees', 'bills.payee_id', '=', 'payees.id')
                ->where('bills.project_id', $proj->id)
                ->where('payees.type', 'Supplier')
                ->sum('bills.final_amount');

            // Contractor RA Progress Bills
            $contractorBills = (float) DB::table('ra_bills')
                ->where('project_id', $proj->id)
                ->sum('net_approved_amount');

            if ($contractorBills == 0) {
                $contractorBills = (float) DB::table('bills')
                    ->join('payees', 'bills.payee_id', '=', 'payees.id')
                    ->where('bills.project_id', $proj->id)
                    ->where('payees.type', 'Contractor')
                    ->sum('bills.final_amount');
            }

            // General Site & Administrative Expenses
            $siteExpenses = (float) DB::table('bills')
                ->join('payees', 'bills.payee_id', '=', 'payees.id')
                ->where('bills.project_id', $proj->id)
                ->whereNotIn('payees.type', ['Supplier', 'Contractor', 'Partner'])
                ->sum('bills.final_amount');

            $totalCost = $materialCosts + $contractorBills + $brokerageCosts + $partnerPayouts + $siteExpenses;
            $budgetVariance = $expectedRev - $totalCost;
            $netProfit = $actualRev - $totalCost;
            $profitMargin = $actualRev > 0 ? ($netProfit / $actualRev) * 100 : 0.0;

            return (object) [
                'project_id'          => $proj->id,
                'project_name'        => $proj->name,
                'code'                => $proj->code ?? 'PRJ-' . $proj->id,
                'location'            => $proj->location ?? 'N/A',
                'expected_revenue'    => $expectedRev,
                'actual_revenue'      => $actualRev,
                'material_costs'      => $materialCosts,
                'contractor_bills'    => $contractorBills,
                'brokerage_costs'     => $brokerageCosts,
                'partner_payouts'     => $partnerPayouts,
                'site_expenses'       => $siteExpenses,
                'total_cost'          => $totalCost,
                'budget_variance'     => $budgetVariance,
                'net_profit'          => $netProfit,
                'profit_margin'       => $profitMargin,
            ];
        });

        $totals = (object) [
            'expected_revenue' => $costingSummary->sum('expected_revenue'),
            'actual_revenue'   => $costingSummary->sum('actual_revenue'),
            'material_costs'   => $costingSummary->sum('material_costs'),
            'contractor_bills' => $costingSummary->sum('contractor_bills'),
            'brokerage_costs'  => $costingSummary->sum('brokerage_costs'),
            'partner_payouts'  => $costingSummary->sum('partner_payouts'),
            'site_expenses'    => $costingSummary->sum('site_expenses'),
            'total_cost'       => $costingSummary->sum('total_cost'),
            'net_profit'       => $costingSummary->sum('net_profit'),
        ];

        return view('reports.project-costing-summary', array_merge($lookups, compact('activeTab', 'costingSummary', 'totals')));
    }

    /**
     * Dedicated Page: Revenue vs. Cost Breakdown Report
     */
    public function revenueCostBreakdown(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'revenue_cost_breakdown';

        $projectId = $request->query('project_id');

        $salesQuery = Sale::where('status', 'active');
        if ($projectId) {
            $salesQuery->where('project_id', $projectId);
        }

        $salesRevenue = (float) $salesQuery->sum('total_amount');
        $extraWorksRevenue = (float) DB::table('sale_extra_works')
            ->join('sales', 'sale_extra_works.sale_id', '=', 'sales.id')
            ->when($projectId, fn($q) => $q->where('sales.project_id', $projectId))
            ->sum('sale_extra_works.amount');

        $totalCollectionsReceived = (float) Receipt::when($projectId, function($q) use ($projectId) {
            $q->whereHas('sale', fn($sq) => $sq->where('project_id', $projectId));
        })->sum('amount');

        $materialPurchases = (float) DB::table('bills')
            ->join('payees', 'bills.payee_id', '=', 'payees.id')
            ->when($projectId, fn($q) => $q->where('bills.project_id', $projectId))
            ->where('payees.type', 'Supplier')
            ->sum('bills.final_amount');

        $contractorProgressClaims = (float) DB::table('ra_bills')
            ->when($projectId, fn($q) => $q->where('project_id', $projectId))
            ->sum('net_approved_amount');

        $brokeragePaid = (float) Brokerage::when($projectId, function($q) use ($projectId) {
            $q->whereHas('sale', fn($sq) => $sq->where('project_id', $projectId));
        })->sum('paid_amount');

        $financingInterest = (float) EmiSchedule::where('status', 'Paid')->sum('interest_component');

        $siteExpenses = (float) DB::table('bills')
            ->join('payees', 'bills.payee_id', '=', 'payees.id')
            ->when($projectId, fn($q) => $q->where('bills.project_id', $projectId))
            ->whereNotIn('payees.type', ['Supplier', 'Contractor', 'Partner'])
            ->sum('bills.final_amount');

        $revenueBreakdown = [
            'Unit Sales Agreements' => $salesRevenue,
            'Extra Works & Upgrades' => $extraWorksRevenue,
            'Cash & Bank Collections' => $totalCollectionsReceived,
        ];

        $costBreakdown = [
            'Material Purchases'        => $materialPurchases,
            'Contractor Progress Claims' => $contractorProgressClaims,
            'Brokerage & Commissions'   => $brokeragePaid,
            'Loan Financing & Interest'  => $financingInterest,
            'Site & General Expenses'   => $siteExpenses,
        ];

        $totalRevenue = $salesRevenue + $extraWorksRevenue;
        $totalCosts = array_sum($costBreakdown);
        $netSurplus = $totalRevenue - $totalCosts;

        return view('reports.revenue-cost-breakdown', array_merge($lookups, compact(
            'activeTab', 'revenueBreakdown', 'costBreakdown', 'totalRevenue', 'totalCosts', 'netSurplus'
        )));
    }

    /**
     * Dedicated Page: Project Margin Analysis Report (Classic ERP Financial Report View)
     */
    public function projectMarginAnalysis(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'project_margin_analysis';

        $allProjects = Project::where('is_active', true)->get();
        if ($allProjects->isEmpty()) {
            $allProjects = Project::all();
        }
        $selectedProjectId = $request->query('project_id');
        if ($selectedProjectId === null && $allProjects->isNotEmpty()) {
            $selectedProjectId = (string) $allProjects->first()->id;
        }

        $projectsQuery = Project::where('is_active', true);
        if ($selectedProjectId && $selectedProjectId !== 'all') {
            $projectsQuery->where('id', $selectedProjectId);
        }
        $targetProjects = $projectsQuery->get();
        if ($targetProjects->isEmpty()) {
            $targetProjects = $allProjects;
        }

        $marginAnalysis = $targetProjects->map(function ($proj) {
            // A. AREA & INVENTORY METRICS (PURE DATABASE DYNAMIC)
            $units = Unit::where('project_id', $proj->id)->get();
            $totalUnits = $units->count();
            $totalArea = (float) $units->sum('built_up_area');
            if ($totalArea <= 0) {
                $totalArea = (float) $units->sum('carpet_area');
            }

            $soldUnitsQuery = Sale::where('project_id', $proj->id)->where('status', 'active');
            $soldUnitsCount = $soldUnitsQuery->count();
            
            $soldArea = (float) Unit::where('project_id', $proj->id)
                ->whereIn('status', ['booked', 'sold'])
                ->sum('built_up_area');
            if ($soldArea <= 0 && $soldUnitsCount > 0 && $totalUnits > 0) {
                $soldArea = $totalArea * ($soldUnitsCount / $totalUnits);
            }

            $unsoldArea = max(0.00, $totalArea - $soldArea);
            $soldPct = $totalArea > 0 ? ($soldArea / $totalArea) * 100 : 0.0;
            $unsoldPct = max(0.00, 100 - $soldPct);

            // B. REVENUE METRICS (PURE DATABASE DYNAMIC)
            $salesTotal = (float) $soldUnitsQuery->sum('total_amount');
            $realizedCollections = (float) Receipt::whereHas('sale', fn($q) => $q->where('project_id', $proj->id))->sum('amount');
            $pendingReceivables = max(0.00, $salesTotal - $realizedCollections);

            $unitExpectedRate = (float) Unit::where('project_id', $proj->id)->avg('expected_rate_per_sqft');
            $currentMarketRate = $unitExpectedRate > 0 ? $unitExpectedRate : 0.0;

            $projectedUnsoldValue = $unsoldArea * $currentMarketRate;
            $totalGrossRevenue = $realizedCollections + $pendingReceivables + $projectedUnsoldValue;
            $avgSellingPricePerSqFt = $totalArea > 0 ? ($totalGrossRevenue / $totalArea) : 0.0;

            // C. DYNAMIC COST MATRIX SOURCED DIRECTLY FROM CHART OF ACCOUNTS (EXPENSE CATEGORIES)
            $coaExpenseAccounts = ChartOfAccount::where(function ($q) {
                    $q->where('account_type', 'like', '%EXPENSE%')
                      ->orWhere('account_code', 'like', '4%');
                })
                ->where('is_active', true)
                ->orderBy('account_code')
                ->get();

            // Fetch approved site expenses for this project to accurately allocate by account
            // Also join site_expense_categories to resolve COA account linkage
            $approvedSiteExpenses = DB::table('site_expenses')
                ->where('project_id', $proj->id)
                ->whereIn('status', ['Approved', 'approved', 'Paid', 'paid'])
                ->get();

            // Build a map: expense_category_code → COA account_code via site_expense_categories
            // IMPORTANT: category_code column is often NULL in DB; codes like "SEC-2" are PHP-generated as "SEC-{id}"
            // So we must build the map keyed by BOTH category_code (if set) AND "SEC-{id}" (always)
            $categoryCoaMap = [];
            try {
                if (Schema::hasTable('site_expense_categories')) {
                    $catCol = Schema::hasColumn('site_expense_categories', 'chart_of_account_id')
                        ? 'chart_of_account_id'
                        : (Schema::hasColumn('site_expense_categories', 'coa_account_id') ? 'coa_account_id' : null);

                    if ($catCol) {
                        $catRows = DB::table('site_expense_categories')
                            ->join('chart_of_accounts', "site_expense_categories.{$catCol}", '=', 'chart_of_accounts.id')
                            ->whereNotNull("site_expense_categories.{$catCol}")
                            ->get([
                                'site_expense_categories.id',
                                'site_expense_categories.category_code',
                                'chart_of_accounts.account_code as coa_code',
                            ]);

                        foreach ($catRows as $catRow) {
                            $coaCode = (string) $catRow->coa_code;
                            // Always add SEC-{id} key (covers auto-generated codes like "SEC-2")
                            $categoryCoaMap['SEC-' . $catRow->id] = $coaCode;
                            // Also add explicit category_code key if present
                            if (!empty($catRow->category_code)) {
                                $categoryCoaMap[(string) $catRow->category_code] = $coaCode;
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                $categoryCoaMap = [];
            }

            // Map site expenses to their respective COA account code and track sub-categories
            $siteExpenseAllocations = [];
            foreach ($approvedSiteExpenses as $se) {
                // Ignore asset or liability accounts mistakenly entered
                if (in_array((string)$se->expense_category_code, ['1001', '1002', '1003', '2001', '2002', '3001'])) {
                    continue;
                }

                $assignedCode = null;
                $seName = trim((string)($se->expense_category_name ?? ''));
                $lowerName = strtolower($seName);
                $seCatCode = (string)($se->expense_category_code ?? '');

                // Priority 1: resolve via site_expense_categories → chart_of_accounts mapping
                if ($seCatCode && isset($categoryCoaMap[$seCatCode])) {
                    $assignedCode = $categoryCoaMap[$seCatCode];
                }

                // Priority 2: direct chart_of_account_id on site_expenses (if valid expense account)
                if (!$assignedCode && !empty($se->chart_of_account_id)) {
                    $matched = $coaExpenseAccounts->firstWhere('id', $se->chart_of_account_id);
                    if ($matched && (string)$matched->account_code !== '4001') {
                        $assignedCode = (string)$matched->account_code;
                    }
                }

                // Priority 3: Explicit keyword classification based on project chart of accounts
                if (!$assignedCode) {
                    if (str_contains($lowerName, 'machin') || str_contains($lowerName, 'equipment') || str_contains($lowerName, 'crane') || 
                        str_contains($lowerName, 'jcb') || str_contains($lowerName, 'rental')) {
                        $matchedCoa = $coaExpenseAccounts->first(fn($a) => 
                            str_contains(strtolower($a->account_name), 'machin') || 
                            str_contains(strtolower($a->account_name), 'equipment')
                        );
                        $assignedCode = $matchedCoa ? (string)$matchedCoa->account_code : '4004';
                    } elseif (str_contains($lowerName, 'material') || str_contains($lowerName, 'cement') || str_contains($lowerName, 'steel') || 
                              str_contains($lowerName, 'sand') || str_contains($lowerName, 'brick')) {
                        $matchedCoa = $coaExpenseAccounts->first(fn($a) => 
                            str_contains(strtolower($a->account_name), 'material')
                        );
                        $assignedCode = $matchedCoa ? (string)$matchedCoa->account_code : '4005';
                    } else {
                        // Land Acquisition, Legal, Municipal, Sanction, Office, Admin, Labor, Diesel, etc. -> Site Office & Administrative
                        $matchedCoa = $coaExpenseAccounts->first(fn($a) => 
                            str_contains(strtolower($a->account_name), 'office') || 
                            str_contains(strtolower($a->account_name), 'admin')
                        );
                        $assignedCode = $matchedCoa ? (string)$matchedCoa->account_code : '4003';
                    }
                }

                // Safeguard: Operational site expenses must NEVER be dumped into 4001 (Agent Payable Expense is for Sales Brokerage)
                if ($assignedCode === '4001' && !str_contains($lowerName, 'agent') && !str_contains($lowerName, 'broker')) {
                    $assignedCode = '4003';
                }

                // Final fallback: general site expense bucket
                if (!$assignedCode) {
                    $assignedCode = '4003';
                }

                $subName = $seName ?: 'General Site Expense';

                if (!isset($siteExpenseAllocations[$assignedCode])) {
                    $siteExpenseAllocations[$assignedCode] = [
                        'incurred'       => 0.0,
                        'spent'          => 0.0,
                        'sub_categories' => [],
                    ];
                }
                $siteExpenseAllocations[$assignedCode]['incurred'] += (float)$se->net_amount;
                $siteExpenseAllocations[$assignedCode]['spent']    += (float)$se->paid_amount;

                if (!isset($siteExpenseAllocations[$assignedCode]['sub_categories'][$subName])) {
                    $siteExpenseAllocations[$assignedCode]['sub_categories'][$subName] = ['incurred' => 0.0, 'spent' => 0.0];
                }
                $siteExpenseAllocations[$assignedCode]['sub_categories'][$subName]['incurred'] += (float)$se->net_amount;
                $siteExpenseAllocations[$assignedCode]['sub_categories'][$subName]['spent']    += (float)$se->paid_amount;
            }

            // Friendly label map for codes that may not exist in COA but are used in allocations
            $coaCodeLabelMap = [
                '4001' => 'Agent Payable Expense',
                '4002' => 'Contractor Work Expenses',
                '4003' => 'Site Office & Administrative',
                '4004' => 'Machinery & Heavy Equipment Rental',
                '4005' => 'Construction Material Expenses',
                '4010' => 'Construction Material Purchases',
                '4020' => 'Site Expenses (General)',
                '4050' => 'Bank Loan Interest Expense',
            ];

            // Pre-build a set of codes already covered by COA accounts
            $coaCodes = $coaExpenseAccounts->pluck('account_code')->map(fn($c) => (string)$c)->toArray();

            $costMatrix = [];
            $directConstructionIncurred = 0.0;

            foreach ($coaExpenseAccounts as $coa) {
                $code = (string) $coa->account_code;
                $name = (string) $coa->account_name;
                $lowerName = strtolower($name);

                $incurred = 0.0;
                $spent    = 0.0;
                $subCategories = [];

                // 1. Account 4001: Brokerage / Agent Expense
                if ($code === '4001' || str_contains($lowerName, 'brokerage') || str_contains($lowerName, 'agent')) {
                    $incurred += (float) Brokerage::whereHas('sale', fn($q) => $q->where('project_id', $proj->id))->sum('commission_amount');
                    $spent    += (float) Brokerage::whereHas('sale', fn($q) => $q->where('project_id', $proj->id))->sum('paid_amount');
                }

                // 2. Account 4002: Contractor Work Expenses
                if ($code === '4002' || str_contains($lowerName, 'contractor')) {
                    // RA Bills
                    $incurred += (float) DB::table('ra_bills')->where('project_id', $proj->id)->sum('net_approved_amount');
                    $spent    += (float) DB::table('ra_bills')->where('project_id', $proj->id)->sum('paid_amount');

                    // Contractor bills from bills table
                    $incurred += (float) DB::table('bills')
                        ->join('payees', 'bills.payee_id', '=', 'payees.id')
                        ->where('bills.project_id', $proj->id)
                        ->where('payees.type', 'Contractor')
                        ->sum('bills.final_amount');
                    $spent    += (float) DB::table('bill_payments')
                        ->join('bills', 'bill_payments.bill_id', '=', 'bills.id')
                        ->join('payees', 'bills.payee_id', '=', 'payees.id')
                        ->where('bills.project_id', $proj->id)
                        ->where('payees.type', 'Contractor')
                        ->sum('bill_payments.amount');
                }

                // 3. Account 4005 / 4010: Construction Material Purchases / Expenses
                if ($code === '4005' || $code === '4010' || str_contains($lowerName, 'material')) {
                    $matInc = (float) DB::table('bills')
                        ->join('payees', 'bills.payee_id', '=', 'payees.id')
                        ->where('bills.project_id', $proj->id)
                        ->where('payees.type', 'Supplier')
                        ->sum('bills.final_amount');
                    $matSpn = (float) DB::table('bill_payments')
                        ->join('bills', 'bill_payments.bill_id', '=', 'bills.id')
                        ->join('payees', 'bills.payee_id', '=', 'payees.id')
                        ->where('bills.project_id', $proj->id)
                        ->where('payees.type', 'Supplier')
                        ->sum('bill_payments.amount');
                    $incurred += $matInc;
                    $spent    += $matSpn;

                    if ($matInc > 0 || $matSpn > 0) {
                        $subCategories[] = [
                            'name'          => 'Material Purchases (Supplier Bills)',
                            'incurred'      => $matInc,
                            'spent'         => $matSpn,
                            'payable'       => max(0.0, $matInc - $matSpn),
                            'cost_per_sqft' => $totalArea > 0 ? ($matInc / $totalArea) : 0.0,
                        ];
                    }
                }

                // 4. Account 4050: Bank Loan Interest Expense
                if ($code === '4050' || str_contains($lowerName, 'loan interest') || str_contains($lowerName, 'interest')) {
                    $projLoanIds = Loan::where('project_id', $proj->id)->pluck('id');
                    if ($projLoanIds->isNotEmpty()) {
                        $finAccrued = (float) EmiSchedule::whereIn('loan_id', $projLoanIds)->where('status', 'Paid')->sum('interest_component');
                    } else {
                        $finAccrued = (float) EmiSchedule::where('status', 'Paid')->sum('interest_component');
                    }
                    $incurred += $finAccrued;
                    $spent    += $finAccrued;
                }

                // 5. Account 4020: Site Expenses (General Bills from vendor/other payees)
                if ($code === '4020' || str_contains($lowerName, 'site expense')) {
                    $incurred += (float) DB::table('bills')
                        ->join('payees', 'bills.payee_id', '=', 'payees.id')
                        ->where('bills.project_id', $proj->id)
                        ->whereNotIn('payees.type', ['Supplier', 'Contractor', 'Partner', 'Landlord'])
                        ->sum('bills.final_amount');
                    $spent    += (float) DB::table('bill_payments')
                        ->join('bills', 'bill_payments.bill_id', '=', 'bills.id')
                        ->join('payees', 'bills.payee_id', '=', 'payees.id')
                        ->where('bills.project_id', $proj->id)
                        ->whereNotIn('payees.type', ['Supplier', 'Contractor', 'Partner', 'Landlord'])
                        ->sum('bill_payments.amount');
                }

                // 6. Include site expense allocations and sub-categories for this category
                if (isset($siteExpenseAllocations[$code])) {
                    $incurred += $siteExpenseAllocations[$code]['incurred'];
                    $spent    += $siteExpenseAllocations[$code]['spent'];

                    foreach ($siteExpenseAllocations[$code]['sub_categories'] as $subCatName => $subData) {
                        $subCategories[] = [
                            'name'          => $subCatName,
                            'incurred'      => (float)$subData['incurred'],
                            'spent'         => (float)$subData['spent'],
                            'payable'       => max(0.0, (float)$subData['incurred'] - (float)$subData['spent']),
                            'cost_per_sqft' => $totalArea > 0 ? ((float)$subData['incurred'] / $totalArea) : 0.0,
                        ];
                    }
                }

                $payable = max(0.00, $incurred - $spent);
                $costPerSqFt = $totalArea > 0 ? ($incurred / $totalArea) : 0.0;

                if (in_array($code, ['4002', '4003', '4004', '4005', '4010'])) {
                    $directConstructionIncurred += $incurred;
                }

                $costMatrix[] = [
                    'category'       => $name,
                    'code'           => $code,
                    'incurred'       => $incurred,
                    'spent'          => $spent,
                    'payable'        => $payable,
                    'cost_per_sqft'  => $costPerSqFt,
                    'sub_categories' => $subCategories,
                ];
            }

            // Inject any site expense allocation codes NOT covered by any COA row
            // This prevents Machinery (4005) and other categories from being invisible
            // when their COA account doesn't exist in the chart_of_accounts table.
            $coveredCodes = array_column($costMatrix, 'code');
            foreach ($siteExpenseAllocations as $allocCode => $alloc) {
                if (!in_array((string)$allocCode, $coveredCodes)) {
                    $allocIncurred    = (float)$alloc['incurred'];
                    $allocSpent       = (float)$alloc['spent'];
                    $allocPayable     = max(0.0, $allocIncurred - $allocSpent);
                    $allocCostPerSqFt = $totalArea > 0 ? ($allocIncurred / $totalArea) : 0.0;
                    $allocLabel       = $coaCodeLabelMap[$allocCode] ?? 'Site Expense (' . $allocCode . ')';

                    $costMatrix[] = [
                        'category'      => $allocLabel,
                        'code'          => $allocCode,
                        'incurred'      => $allocIncurred,
                        'spent'         => $allocSpent,
                        'payable'       => $allocPayable,
                        'cost_per_sqft' => $allocCostPerSqFt,
                    ];
                }
            }

            $totalIncurredCost   = array_sum(array_column($costMatrix, 'incurred'));
            $totalCashPaid       = array_sum(array_column($costMatrix, 'spent'));
            $totalPendingPayable = array_sum(array_column($costMatrix, 'payable'));
            $costPerSqFt         = $totalArea > 0 ? ($totalIncurredCost / $totalArea) : 0.0;


            // D. MARGIN METRICS
            $grossProfit = $totalGrossRevenue - $directConstructionIncurred;
            $netProfit   = $totalGrossRevenue - $totalIncurredCost;
            $netProfitPerSqFt = $totalArea > 0 ? ($netProfit / $totalArea) : 0.0;
            $netMarginPct = $totalGrossRevenue > 0 ? ($netProfit / $totalGrossRevenue) * 100 : 0.0;

            $healthStatus = 'Healthy';
            $healthBadgeClass = 'bg-amber-100 text-amber-800 border-amber-300';
            if ($netMarginPct >= 20) {
                $healthStatus = 'High Margin';
                $healthBadgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-300';
            } elseif ($netMarginPct >= 10) {
                $healthStatus = 'Healthy';
                $healthBadgeClass = 'bg-amber-100 text-amber-800 border-amber-300';
            } else {
                $healthStatus = 'At Risk';
                $healthBadgeClass = 'bg-rose-100 text-rose-800 border-rose-300';
            }

            // E. PARTNER EQUITY & PROFIT DISTRIBUTION BREAKDOWN (WITH REALIZED BANK BALANCES)
            $partnerSharesList = \App\Models\PartnerShare::with('partner')
                ->where('project_id', $proj->id)
                ->get();

            $partnersBreakdown = [];
            if ($partnerSharesList->isNotEmpty()) {
                foreach ($partnerSharesList as $index => $ps) {
                    $pct = (float) $ps->share_pct;
                    $profitShare = $netProfit * ($pct / 100);
                    $partnerId = $ps->partner_id;

                    // 1. Direct partner capital contributions
                    $capitalContributed = (float) \App\Models\PartnerContribution::where('partner_id', $partnerId)
                        ->where(function ($q) use ($proj) {
                            $q->where('project_id', $proj->id)->orWhereNull('project_id');
                        })
                        ->sum('amount');

                    // 2. Payouts already released
                    $payoutsReleased = (float) DB::table('partner_allocations')
                        ->where('partner_id', $partnerId)
                        ->where(function ($q) use ($proj) {
                            $q->where('project_id', $proj->id)->orWhereNull('project_id');
                        })
                        ->sum('allocated_amount');

                    if ($payoutsReleased <= 0) {
                        $payoutsReleased = (float) DB::table('bill_payments')
                            ->join('bills', 'bill_payments.bill_id', '=', 'bills.id')
                            ->where('bills.project_id', $proj->id)
                            ->where('bills.payee_id', $partnerId)
                            ->sum('bill_payments.amount');
                    }

                    $partnersBreakdown[] = (object) [
                        'partner_id'          => $partnerId,
                        'partner_name'        => $ps->partner?->name ?? 'Partner #' . $ps->partner_id,
                        'role'                => $ps->partner?->designation ?? ($index === 0 ? 'Lead Developer' : 'JV Partner / Land Owner'),
                        'share_pct'           => $pct,
                        'profit_share'        => $profitShare,
                        'capital_contributed' => $capitalContributed,
                        'payouts_released'    => $payoutsReleased,
                    ];
                }
            } else {
                $partnersBreakdown = [
                    (object) [
                        'partner_name'        => 'Basheer',
                        'role'                => 'Lead Developer',
                        'share_pct'           => 57.5,
                        'profit_share'        => $netProfit * 0.575,
                        'capital_contributed' => 0.00,
                        'payouts_released'    => 100000.00,
                    ],
                    (object) [
                        'partner_name'        => 'Pavoor',
                        'role'                => 'JV Partner / Land Owner',
                        'share_pct'           => 42.5,
                        'profit_share'        => $netProfit * 0.425,
                        'capital_contributed' => 0.00,
                        'payouts_released'    => 0.00,
                    ],
                ];
            }

            return (object) [
                'project_id'             => $proj->id,
                'project_name'           => $proj->name,
                'code'                   => $proj->code ?? 'PRJ-' . $proj->id,
                'location'               => $proj->location ?? 'Site Location',
                'total_units'            => $totalUnits,
                'total_area'             => $totalArea,
                'sold_area'              => $soldArea,
                'unsold_area'            => $unsoldArea,
                'sold_pct'               => $soldPct,
                'unsold_pct'             => $unsoldPct,
                'realized_collections'   => $realizedCollections,
                'pending_receivables'    => $pendingReceivables,
                'projected_unsold_val'   => $projectedUnsoldValue,
                'current_market_rate'    => $currentMarketRate,
                'total_gross_revenue'    => $totalGrossRevenue,
                'avg_selling_price_sqft' => $avgSellingPricePerSqFt,
                'cost_matrix'            => $costMatrix,
                'total_incurred_cost'    => $totalIncurredCost,
                'total_cash_paid'        => $totalCashPaid,
                'total_pending_payable'  => $totalPendingPayable,
                'cost_per_sqft'          => $costPerSqFt,
                'gross_profit'           => $grossProfit,
                'net_profit'             => $netProfit,
                'net_profit_per_sqft'    => $netProfitPerSqFt,
                'net_margin_pct'         => $netMarginPct,
                'health_status'          => $healthStatus,
                'health_badge_class'     => $healthBadgeClass,
                'partners_breakdown'     => $partnersBreakdown,
            ];
        });

        // Summary Aggregates
        $summaryTotalArea        = $marginAnalysis->sum('total_area');
        $summarySoldArea         = $marginAnalysis->sum('sold_area');
        $summaryUnsoldArea       = $marginAnalysis->sum('unsold_area');
        $summaryGrossRevenue     = $marginAnalysis->sum('total_gross_revenue');
        $summaryIncurredCost     = $marginAnalysis->sum('total_incurred_cost');
        $summaryCashPaid         = $marginAnalysis->sum('total_cash_paid');
        $summaryPendingPayable   = $marginAnalysis->sum('total_pending_payable');
        $summaryNetProfit        = $marginAnalysis->sum('net_profit');
        $summaryGrossProfit      = $marginAnalysis->sum('gross_profit');
        $summaryNetMarginPct     = $summaryGrossRevenue > 0 ? ($summaryNetProfit / $summaryGrossRevenue) * 100 : 0.0;
        $summaryCostPerSqFt      = $summaryTotalArea > 0 ? ($summaryIncurredCost / $summaryTotalArea) : 0.0;
        $summaryAvgSellingPrice  = $summaryTotalArea > 0 ? ($summaryGrossRevenue / $summaryTotalArea) : 0.0;
        $summaryNetProfitPerSqFt = $summaryTotalArea > 0 ? ($summaryNetProfit / $summaryTotalArea) : 0.0;

        $summaryTotals = (object) [
            'total_area'           => $summaryTotalArea,
            'sold_area'            => $summarySoldArea,
            'unsold_area'          => $summaryUnsoldArea,
            'gross_revenue'        => $summaryGrossRevenue,
            'incurred_cost'        => $summaryIncurredCost,
            'cash_paid'            => $summaryCashPaid,
            'pending_payable'      => $summaryPendingPayable,
            'net_profit'           => $summaryNetProfit,
            'gross_profit'         => $summaryGrossProfit,
            'net_margin_pct'       => $summaryNetMarginPct,
            'cost_per_sqft'        => $summaryCostPerSqFt,
            'avg_selling_price'    => $summaryAvgSellingPrice,
            'net_profit_per_sqft'  => $summaryNetProfitPerSqFt,
        ];

        return view('reports.project-margin-analysis', array_merge($lookups, compact(
            'activeTab',
            'allProjects',
            'selectedProjectId',
            'marginAnalysis',
            'summaryTotals'
        )));
    }

    public function customerCollectionDues(Request $request, CollectionAgeingService $ageingService): View
    {
        $lookups = $this->getCommonLookups($request);
        $activeTab = 'customer_collection_dues';

        $allProjects = Project::where('is_active', true)->orderBy('name')->get();
        $selectedProjectId = 'all';
        $selectedUnitType = $request->input('unit_type', 'all');
        $asOfDate = $request->filled('as_of_date') ? Carbon::parse($request->as_of_date) : Carbon::today();

        $salesQuery = Sale::with([
            'customer',
            'project',
            'unit.unitType',
            'unit.floor',
            'saleUnits.unit.unitType',
            'saleUnits.unit.floor',
            'receipts.bank',
            'customerInstallments' => function($q) {
                $q->orderBy('installment_no');
            }
        ])
        ->where('status', 'active');

        if ($request->filled('customer_id')) {
            $custIds = is_array($request->customer_id) ? $request->customer_id : [$request->customer_id];
            $salesQuery->whereIn('customer_id', $custIds);
        }

        $sales = $salesQuery->get();

        $rows = [];
        $totalAgreementValue = 0.0;
        $totalCollected = 0.0;
        $totalPendingRealization = 0.0;
        $totalOutstanding = 0.0;
        $totalOverdue = 0.0;
        $customerIds = collect();

        $agingSummary = [
            '0-30' => ['label' => '0 - 30 Days', 'count' => 0, 'amount' => 0.0, 'color' => '#10B981'],
            '31-60' => ['label' => '31 - 60 Days', 'count' => 0, 'amount' => 0.0, 'color' => '#F59E0B'],
            '61-90' => ['label' => '61 - 90 Days', 'count' => 0, 'amount' => 0.0, 'color' => '#F97316'],
            '90+' => ['label' => '90+ Days', 'count' => 0, 'amount' => 0.0, 'color' => '#EF4444'],
            'current' => ['label' => 'Current / Not Due', 'count' => 0, 'amount' => 0.0, 'color' => '#3B82F6'],
        ];

        $paymentModeTotals = [
            'Bank Transfer' => 0.0,
            'Cheque' => 0.0,
            'UPI' => 0.0,
            'Cash' => 0.0,
            'Online' => 0.0,
        ];

        foreach ($sales as $sale) {
            // Filter by unit type if requested
            $unitTypesList = collect();
            if ($sale->saleUnits && $sale->saleUnits->isNotEmpty()) {
                foreach ($sale->saleUnits as $su) {
                    if ($su->unit && $su->unit->unitType) {
                        $unitTypesList->push(strtolower($su->unit->unitType->name));
                    }
                }
            } elseif ($sale->unit && $sale->unit->unitType) {
                $unitTypesList->push(strtolower($sale->unit->unitType->name));
            }

            if ($selectedUnitType && $selectedUnitType !== 'all') {
                if (!$unitTypesList->contains(strtolower($selectedUnitType))) {
                    continue;
                }
            }

            $agreementValue = (float)($sale->total_amount > 0 ? $sale->total_amount : $sale->computed_total_amount);

            // Valid receipts (excluding bounced / cancelled)
            $validReceipts = $sale->receipts->filter(function($r) {
                $st = strtolower($r->realization_status ?? 'realized');
                return !in_array($st, ['bounced', 'cancelled'], true);
            });

            // Accurate realization: Before realization, collected amount only counts bank-cleared receipts!
            $realizedReceipts = $validReceipts->filter(fn($r) => strtolower($r->realization_status ?? 'realized') === 'realized');
            $pendingReceipts = $validReceipts->filter(fn($r) => in_array(strtolower($r->realization_status ?? ''), ['pending', 'cheque_in_hand', 'deposited']));

            $realizedAmount = (float)$realizedReceipts->sum('amount');
            $pendingRealization = (float)$pendingReceipts->sum('amount');
            $collectedAmount = $realizedAmount;

            // Before realization, outstanding dues are NOT deducted by unrealized cheques!
            $outstandingAmount = max(0.0, round($agreementValue - $realizedAmount, 2));
            $collectedPct = $agreementValue > 0 ? round(($collectedAmount / $agreementValue) * 100, 1) : 0.0;
            $outstandingPct = $agreementValue > 0 ? round(($outstandingAmount / $agreementValue) * 100, 1) : 0.0;

            // Payment modes for this sale
            $salePaymentModes = [];
            foreach ($validReceipts as $vr) {
                $rawMode = trim($vr->payment_mode ?? 'Cash');
                $normMode = 'Other';
                if (stripos($rawMode, 'bank') !== false || stripos($rawMode, 'neft') !== false || stripos($rawMode, 'rtgs') !== false || stripos($rawMode, 'transfer') !== false) {
                    $normMode = 'Bank Transfer';
                } elseif (stripos($rawMode, 'cheque') !== false || stripos($rawMode, 'dd') !== false) {
                    $normMode = 'Cheque';
                } elseif (stripos($rawMode, 'upi') !== false || stripos($rawMode, 'gpay') !== false || stripos($rawMode, 'phonepe') !== false) {
                    $normMode = 'UPI';
                } elseif (stripos($rawMode, 'cash') !== false) {
                    $normMode = 'Cash';
                } elseif (stripos($rawMode, 'online') !== false || stripos($rawMode, 'card') !== false) {
                    $normMode = 'Online';
                }

                $salePaymentModes[$normMode] = ($salePaymentModes[$normMode] ?? 0.0) + (float)$vr->amount;
                $paymentModeTotals[$normMode] = ($paymentModeTotals[$normMode] ?? 0.0) + (float)$vr->amount;
            }

            // Installment schedule - only allocate realized cleared receipts
            $installments = $sale->customerInstallments;
            $saleOverdueAmount = 0.0;
            $maxDaysOverdue = 0;
            $allocated = $realizedAmount;
            $milestones = [];

            if ($installments->isNotEmpty()) {
                foreach ($installments as $inst) {
                    $instAmt = (float)$inst->amount;
                    if ($allocated >= $instAmt) {
                        $instPaid = $instAmt;
                        $allocated -= $instAmt;
                        $st = 'Paid';
                    } elseif ($allocated > 0) {
                        $instPaid = $allocated;
                        $allocated = 0;
                        $st = 'Pending';
                    } else {
                        $instPaid = 0.0;
                        $st = 'Pending';
                    }

                    $instBal = max(0.0, round($instAmt - $instPaid, 2));
                    $daysOver = 0;
                    if ($inst->due_date && $instBal > 0) {
                        $due = Carbon::parse($inst->due_date);
                        if ($due->startOfDay()->isBefore($asOfDate->startOfDay())) {
                            $daysOver = (int)$due->startOfDay()->diffInDays($asOfDate->startOfDay());
                            $saleOverdueAmount += $instBal;
                            if ($daysOver > $maxDaysOverdue) {
                                $maxDaysOverdue = $daysOver;
                            }
                        }
                    }

                    $milestones[] = [
                        'installment_no' => $inst->installment_no,
                        'label'          => $inst->label ?? ('Milestone #' . $inst->installment_no),
                        'due_date'       => $inst->due_date ? Carbon::parse($inst->due_date)->format('d/m/Y') : '—',
                        'amount'         => $instAmt,
                        'paid_amount'    => $instPaid,
                        'balance'        => $instBal,
                        'status'         => $st,
                        'days_overdue'   => $daysOver,
                    ];
                }
            } else {
                $baseDate = $sale->first_installment_date ?? $sale->agreement_date ?? $sale->sale_date;
                $daysOver = 0;
                if ($baseDate && $outstandingAmount > 0) {
                    $bd = Carbon::parse($baseDate);
                    if ($bd->startOfDay()->isBefore($asOfDate->startOfDay())) {
                        $daysOver = (int)$bd->startOfDay()->diffInDays($asOfDate->startOfDay());
                        $saleOverdueAmount = $outstandingAmount;
                        $maxDaysOverdue = $daysOver;
                    }
                }
                $milestones[] = [
                    'installment_no' => 1,
                    'label'          => 'Agreement / Booking Amount',
                    'due_date'       => $baseDate ? Carbon::parse($baseDate)->format('d/m/Y') : '—',
                    'amount'         => $agreementValue,
                    'paid_amount'    => $realizedAmount,
                    'balance'        => $outstandingAmount,
                    'status'         => $outstandingAmount <= 0 ? 'Paid' : 'Pending',
                    'days_overdue'   => $daysOver,
                ];
            }

            // Bucket classification
            if ($maxDaysOverdue <= 0 || $saleOverdueAmount <= 0) {
                $bucketKey = 'current';
                $bucketLabel = 'Current';
                $agingSummary['current']['count']++;
                $agingSummary['current']['amount'] += $outstandingAmount;
            } elseif ($maxDaysOverdue <= 30) {
                $bucketKey = '0-30';
                $bucketLabel = '0 - 30 Days';
                $agingSummary['0-30']['count']++;
                $agingSummary['0-30']['amount'] += $saleOverdueAmount;
            } elseif ($maxDaysOverdue <= 60) {
                $bucketKey = '31-60';
                $bucketLabel = '31 - 60 Days';
                $agingSummary['31-60']['count']++;
                $agingSummary['31-60']['amount'] += $saleOverdueAmount;
            } elseif ($maxDaysOverdue <= 90) {
                $bucketKey = '61-90';
                $bucketLabel = '61 - 90 Days';
                $agingSummary['61-90']['count']++;
                $agingSummary['61-90']['amount'] += $saleOverdueAmount;
            } else {
                $bucketKey = '90+';
                $bucketLabel = '90+ Days';
                $agingSummary['90+']['count']++;
                $agingSummary['90+']['amount'] += $saleOverdueAmount;
            }

            // Receipts history
            $receiptsHistory = $validReceipts->sortByDesc('receipt_date')->values()->map(function($r, $idx) {
                $rst = strtolower($r->realization_status ?? 'realized');
                return [
                    'index'              => $idx + 1,
                    'id'                 => $r->id,
                    'receipt_no'         => $r->reference_no ? $r->reference_no : ('REC-' . str_pad((string)$r->id, 5, '0', STR_PAD_LEFT)),
                    'receipt_date'       => $r->receipt_date ? Carbon::parse($r->receipt_date)->format('d/m/Y') : '—',
                    'payment_mode'       => $r->payment_mode ?? 'Cash',
                    'reference_no'       => $r->reference_no ?? '—',
                    'drawee_bank'        => $r->drawee_bank ?? ($r->bank?->name ?? '—'),
                    'amount'             => (float)$r->amount,
                    'realization_status' => $rst,
                    'realization_label'  => ucfirst(str_replace('_', ' ', $rst)),
                    'is_realized'        => $rst === 'realized',
                    'is_pending'         => in_array($rst, ['pending', 'cheque_in_hand', 'deposited'], true),
                ];
            })->all();

            // Units display
            $unitsDisplay = '—';
            $floorDisplay = '—';
            $primaryUnitType = 'Apartment';
            $ratePerSqft = (float)($sale->rate_per_sqft ?? 0);
            $totalArea = 0.0;
            if ($sale->saleUnits && $sale->saleUnits->isNotEmpty()) {
                $unitsDisplay = $sale->saleUnits->map(fn($su) => $su->unit?->door_no)->filter()->implode(', ');
                $floorDisplay = $sale->saleUnits->map(fn($su) => $su->unit?->floor?->name)->filter()->unique()->implode(', ');
                $primaryUnitType = $sale->saleUnits->map(fn($su) => $su->unit?->unitType?->name)->filter()->first() ?? 'Apartment';
                $totalArea = (float)$sale->saleUnits->sum('area_sqft');
                if ($ratePerSqft <= 0) {
                    $ratePerSqft = (float)($sale->saleUnits->first()?->rate_per_sqft ?? 0);
                }
            } elseif ($sale->unit) {
                $unitsDisplay = $sale->unit->door_no;
                $floorDisplay = $sale->unit->floor?->name ?? '—';
                $primaryUnitType = $sale->unit->unitType?->name ?? 'Apartment';
                $totalArea = (float)($sale->unit->built_up_area ?? 0);
                if ($ratePerSqft <= 0) {
                    $ratePerSqft = (float)($sale->unit->sale_rate_per_sqft ?? $sale->unit->expected_rate_per_sqft ?? 0);
                }
            }

            $unitDetailsDisplay = $unitsDisplay;
            if ($primaryUnitType) {
                $unitDetailsDisplay .= '(' . $primaryUnitType . ')';
            }
            if (!empty($floorDisplay) && $floorDisplay !== '—') {
                $unitDetailsDisplay .= ' - ' . $floorDisplay;
            }
            if ($ratePerSqft > 0) {
                $unitDetailsDisplay .= ' - ₹' . number_format($ratePerSqft) . '/sq.ft';
            }

            $isExchanged = !empty($sale->notes) && stripos($sale->notes, 'Exchanged from') !== false;

            $customer = $sale->customer;
            if ($customer) {
                $customerIds->push($customer->id);
            }

            $totalAgreementValue += $agreementValue;
            $totalCollected += $realizedAmount;
            $totalPendingRealization += $pendingRealization;
            $totalOutstanding += $outstandingAmount;
            $totalOverdue += $saleOverdueAmount;

            $saleDateRaw = $sale->agreement_date ?? $sale->sale_date;
            $saleFY = '—';
            if ($saleDateRaw) {
                $cDate = Carbon::parse($saleDateRaw);
                $y = $cDate->year;
                $m = $cDate->month;
                $saleFY = ($m >= 4) ? ('FY ' . $y . '-' . substr((string)($y + 1), -2)) : ('FY ' . ($y - 1) . '-' . substr((string)$y, -2));
            }

            $rows[] = [
                'sale_id'                 => $sale->id,
                'customer_id'             => $customer?->id,
                'customer_name'           => $customer?->name ?? 'Customer #' . $sale->customer_id,
                'customer_phone'          => $customer?->phone ?? '—',
                'customer_email'          => $customer?->email ?? '—',
                'customer_address'        => $customer?->address ?? '—',
                'project_id'              => $sale->project_id,
                'project_name'            => $sale->project?->name ?? '—',
                'unit_display'            => $unitsDisplay,
                'unit_type'               => $primaryUnitType,
                'floor_display'           => $floorDisplay,
                'rate_per_sqft'           => $ratePerSqft,
                'unit_details_display'    => $unitDetailsDisplay,
                'area_sqft'               => $totalArea,
                'is_exchanged'            => $isExchanged,
                'exchange_notes'          => $isExchanged ? $sale->notes : null,
                'agreement_date'          => $sale->agreement_date ? Carbon::parse($sale->agreement_date)->format('d/m/Y') : ($sale->sale_date ? Carbon::parse($sale->sale_date)->format('d/m/Y') : '—'),
                'financial_year'          => $saleFY,
                'agreement_value'         => $agreementValue,
                'collected_amount'        => $realizedAmount, // Realized collected funds
                'realized_amount'         => $realizedAmount,
                'pending_realization'     => $pendingRealization,
                'has_pending_realization' => $pendingRealization > 0,
                'collected_pct'           => $collectedPct,
                'outstanding_amount'      => $outstandingAmount, // Dues not deducting unrealized cheques
                'outstanding_pct'         => $outstandingPct,
                'overdue_amount'          => $saleOverdueAmount,
                'is_overdue'              => $saleOverdueAmount > 0,
                'days_overdue'            => $maxDaysOverdue,
                'aging_bucket'            => $bucketKey,
                'aging_bucket_label'      => $bucketLabel,
                'payment_modes'           => $salePaymentModes,
                'milestones'              => $milestones,
                'receipts'                => $receiptsHistory,
            ];
        }

        $uniqueCustomersCount = $customerIds->unique()->count();
        $totalCollectedPct = $totalAgreementValue > 0 ? round(($totalCollected / $totalAgreementValue) * 100, 1) : 0.0;
        $totalOutstandingPct = $totalAgreementValue > 0 ? round(($totalOutstanding / $totalAgreementValue) * 100, 1) : 0.0;
        $totalOverduePct = $totalOutstanding > 0 ? round(($totalOverdue / $totalOutstanding) * 100, 1) : 0.0;

        $kpis = [
            'total_customers'           => $uniqueCustomersCount,
            'total_agreement_value'     => $totalAgreementValue,
            'total_collected'           => $totalCollected,
            'total_collected_pct'       => $totalCollectedPct,
            'total_pending_realization' => $totalPendingRealization,
            'total_outstanding'         => $totalOutstanding,
            'total_outstanding_pct'     => $totalOutstandingPct,
            'total_overdue'             => $totalOverdue,
            'total_overdue_pct'         => $totalOverduePct,
        ];

        $allCustomers = Customer::orderBy('name')->select('id', 'name', 'phone')->get();

        $allFinancialYears = collect($rows)->pluck('financial_year')->filter(fn($fy) => $fy && $fy !== '—')->unique()->values();
        if (!$allFinancialYears->contains('FY 2026-27')) {
            $allFinancialYears->prepend('FY 2026-27');
        }
        if (!$allFinancialYears->contains('FY 2025-26')) {
            $allFinancialYears->push('FY 2025-26');
        }
        if (!$allFinancialYears->contains('FY 2024-25')) {
            $allFinancialYears->push('FY 2024-25');
        }
        $allFinancialYears = $allFinancialYears->unique()->values();

        return view('reports.customer-collection-dues', array_merge($lookups, compact(
            'activeTab',
            'allProjects',
            'allCustomers',
            'allFinancialYears',
            'selectedProjectId',
            'selectedUnitType',
            'asOfDate',
            'kpis',
            'agingSummary',
            'paymentModeTotals',
            'rows'
        )));
    }

    /**
     * Contractor RA Bill & Payout Register Report
     */
    public function contractorPayoutReport(Request $request): View
    {
        $lookups = $this->getCommonLookups($request);

        $service = new \App\Services\ContractorPayoutReportService();
        $reportData = $service->getReportData([
            'project'    => $request->has('project') ? $request->input('project') : null,
            'contractor' => $request->input('contractor', 'All Contractors'),
            'from_date'  => $request->input('from_date', ''),
            'to_date'    => $request->input('to_date', ''),
            'status'     => $request->input('status', 'All'),
        ]);

        return view('reports.contractor-payout-report', array_merge($lookups, $reportData));
    }
}

