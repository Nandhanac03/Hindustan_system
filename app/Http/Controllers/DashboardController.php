<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Approval;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Receipt;
use App\Models\Project;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // ── KPI Cards ────────────────────────────────────────────────────────
        $activeProject      = Project::where('is_active', true)->first();
        $totalProjects      = Project::count();
        $currentYear        = Carbon::now()->year;

        $unitQuery    = fn() => $activeProject ? Unit::where('project_id', $activeProject->id) : Unit::query();
        $bookingQuery = fn() => $activeProject ? Sale::where('project_id', $activeProject->id) : Sale::query();
        $paymentQuery = fn() => ($activeProject ? Receipt::where('project_id', $activeProject->id) : Receipt::query())
            ->whereNull('partner_id')
            ->where('realization_status', 'realized');

        // Customer refunds paid out of company bank account on cancelled/returned sales
        $projectSaleIds = $activeProject ? Sale::where('project_id', $activeProject->id)->pluck('id')->all() : null;

        $postedRefundVouchers = DB::table('vouchers')
            ->whereIn('type', ['Receipt', 'Payment'])
            ->where('status', 'Posted')
            ->whereNotNull('reference_no')
            ->get(['id', 'date', 'reference_no']);

        $totalRefundsPaid = 0.0;
        $monthlyRefunds   = [];
        foreach ($postedRefundVouchers as $v) {
            $refData = json_decode($v->reference_no, true);
            if (!empty($refData['allocations']) && is_array($refData['allocations'])) {
                foreach ($refData['allocations'] as $alloc) {
                    if (($alloc['type'] ?? '') === 'refund' && !empty($alloc['target_id'])) {
                        $sId = (int)$alloc['target_id'];
                        if ($projectSaleIds === null || in_array($sId, $projectSaleIds, true)) {
                            $amt = (float)($alloc['amount'] ?? 0);
                            $totalRefundsPaid += $amt;

                            if (!empty($v->date)) {
                                $vDate = Carbon::parse($v->date);
                                if ($vDate->year === $currentYear) {
                                    $m = (int)$vDate->month;
                                    $monthlyRefunds[$m] = ($monthlyRefunds[$m] ?? 0.0) + $amt;
                                }
                            }
                        }
                    }
                }
            }
        }

        $totalUnits               = $unitQuery()->count();
        $availableUnits           = $unitQuery()->where('status', 'available')->count();
        $bookedUnits              = $unitQuery()->whereIn('status', ['booked', 'sold'])->count();
        $soldUnitsCount           = $unitQuery()->where('status', 'sold')->count();
        $soldUnitsValue           = $unitQuery()->where('status', 'sold')->sum('expected_sale_amount');
        $reservedUnits            = $unitQuery()->where('status', 'reserved')->count();
        $totalCustomers           = Customer::whereHas('sales', function ($q) use ($activeProject) {
            $q->where('status', 'active');
            if ($activeProject) {
                $q->where('project_id', $activeProject->id);
            }
        })->count();
        $totalSales               = (float) $bookingQuery()->where('status', 'active')->sum('total_amount');
        $totalBookingsCount       = $bookingQuery()->where('status', 'active')->count();
        $grossRealizedCollections = (float) $paymentQuery()->sum('amount');
        $totalCollections         = max(0.00, $grossRealizedCollections - $totalRefundsPaid);
        $pendingApprovals         = Approval::where('status', 'pending')->count();

        // Outstanding = Sales - Collections
        $outstanding = max(0.00, $totalSales - $totalCollections);

        // ── Payables Breakdown (Paid Amount Only) ─────────────────────────────
        // 1. Site Expense (Paid Amount)
        // ── Total Payables (Contractor RA Bills + Site Expenses / Vendors + Brokerage) ──
        // 1. Site Expense / Vendor (Paid Amount)
        $siteExpenseQuery = \App\Models\SiteExpense::query();
        if ($activeProject) {
            $siteExpenseQuery->where('project_id', $activeProject->id);
        }
        $siteExpensePayable = (float) $siteExpenseQuery->sum('paid_amount');

        // 2. Contractor RA Bill (Paid Amount)
        $raBillQuery = \App\Models\RaBill::query();
        if ($activeProject) {
            $raBillQuery->where('project_id', $activeProject->id);
        }
        $contractorRaBillPayable = (float) $raBillQuery->whereNotIn('status', ['rejected', 'cancelled', 'draft'])->sum('paid_amount');

        // 3. Customer Refund (Paid Amount)
        $customerRefundPayable = (float) $totalRefundsPaid;

        // 4. Broker Commission (Paid Amount)
        // 3. Broker Commission (Paid Amount)
        $brokerageQuery = \App\Models\Brokerage::query();
        if ($activeProject) {
            $brokerageQuery->whereHas('sale', fn($q) => $q->where('project_id', $activeProject->id));
        }
        $brokerCommissionPayable = (float) $brokerageQuery->sum('paid_amount');

        // 5. Other Payables (Other posted payment vouchers)
        $customerRefundPayable = (float) $totalRefundsPaid;
        $otherPayables = 0.0;
        try {
            $allPostedPayments = (float) \App\Models\VoucherLine::whereHas('voucher', function ($q) {
                $q->where('type', 'Payment')->where('status', 'Posted');
            })->sum('debit');
            $otherPayables = max(0.0, $allPostedPayments - $totalRefundsPaid);
        } catch (\Throwable $e) {
            $otherPayables = 0.0;
        }

        // Total Payables (Total Paid Amount)
        $totalPayables = $siteExpensePayable + $contractorRaBillPayable + $customerRefundPayable + $brokerCommissionPayable;
        // Total Payables = Contractor Paid + Vendor/Site Expense Paid + Broker Commission Paid

        // ── Recent Units ───────────────────────────────────────────────────
        $recentUnits = $unitQuery()->with(['project', 'floor', 'unitType'])->latest()
            ->take(5)
            ->get();

        // ── Top Customers (by total payment amount / sales volume) ───────────────
        $topCustomers = Customer::whereHas('sales', function ($q) use ($activeProject) {
                $q->where('status', 'active');
                if ($activeProject) {
                    $q->where('project_id', $activeProject->id);
                }
            })
            ->withSum(
                ['receipts' => fn ($q) => ($activeProject ? $q->where('project_id', $activeProject->id) : $q)
                    ->whereNull('partner_id')
                    ->where('realization_status', 'realized')
                    ->whereHas('sale', fn ($sq) => $sq->where('status', 'active'))
                ],
                'amount'
            )
            ->withSum(
                ['sales' => fn ($q) => ($activeProject ? $q->where('project_id', $activeProject->id) : $q)->where('status', 'active')],
                'total_amount'
            )
            ->withCount(['sales' => fn ($q) => ($activeProject ? $q->where('project_id', $activeProject->id) : $q)->where('status', 'active')])
            ->orderByDesc('receipts_sum_amount')
            ->orderByDesc('sales_sum_total_amount')
            ->take(5)
            ->get();

        // ── Recent Bookings (Active sales only; excludes exchanged and cancelled) ──
        $recentBookings = $bookingQuery()
            ->where('status', 'active')
            ->with(['unit', 'customer'])
            ->latest()
            ->take(5)
            ->get();

        // ── Pending EMI Alerts (replaces Inventory Activity) ──────────────────
        $pendingEmiAlerts = \App\Models\EmiSchedule::with(['loan'])
            ->where('status', 'Due')
            ->orderBy('due_date')
            ->take(5)
            ->get()
            ->map(function ($emi) {
                $daysDiff = (int)ceil(Carbon::parse($emi->due_date)->diffInDays(Carbon::now(), false));
                if ($daysDiff > 0) {
                    $emi->due_text = 'Overdue ' . $daysDiff . ' days';
                    $emi->is_overdue = true;
                } elseif ($daysDiff === 0) {
                    $emi->due_text = 'Due Today';
                    $emi->is_overdue = false;
                } else {
                    $emi->due_text = 'Due ' . abs($daysDiff) . ' days';
                    $emi->is_overdue = false;
                }
                $emi->provider = $emi->loan->lender_name ?? 'Bank';
                return $emi;
            });

        // ── Revenue Chart (monthly bookings + payments for current year) ──────
        $monthlyBookings = $bookingQuery()->where('status', 'active')
            ->whereYear('sale_date', $currentYear)
            ->selectRaw('MONTH(sale_date) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyPayments = $paymentQuery()
            ->whereYear('receipt_date', $currentYear)
            ->selectRaw('MONTH(receipt_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Build 12-month arrays (0 if no data for that month)
        $revenueData     = [];
        $collectionsData = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenueData[]     = round(($monthlyBookings[$m] ?? 0) / 100000, 2);  // in Lakhs
            $netMonthCollected = max(0.0, ($monthlyPayments[$m] ?? 0) - ($monthlyRefunds[$m] ?? 0));
            $collectionsData[] = round($netMonthCollected / 100000, 2);
        }

        // ── Sales & Collection Trend (Financial Year Dynamic DB Data) ────────
        $now = Carbon::now();
        $fyStartYear = ($now->month >= 4) ? $now->year : ($now->year - 1);
        $fyEndYear = $fyStartYear + 1;
        $fyStartDate = Carbon::create($fyStartYear, 4, 1)->startOfDay();
        $fyEndDate = Carbon::create($fyEndYear, 3, 31)->endOfDay();

        // 1. Group active sales (bookings) by year & month in current FY
        $fyBookings = $bookingQuery()->where('status', 'active')
            ->whereBetween('sale_date', [$fyStartDate, $fyEndDate])
            ->selectRaw('YEAR(sale_date) as yr, MONTH(sale_date) as mo, SUM(total_amount) as total')
            ->groupBy('yr', 'mo')
            ->get();

        $fyBookingsMap = [];
        foreach ($fyBookings as $b) {
            $fyBookingsMap[$b->yr . '-' . (int)$b->mo] = (float)$b->total;
        }

        // 2. Group realized collections by year & month in current FY
        $fyPayments = $paymentQuery()
            ->whereBetween('receipt_date', [$fyStartDate, $fyEndDate])
            ->selectRaw('YEAR(receipt_date) as yr, MONTH(receipt_date) as mo, SUM(amount) as total')
            ->groupBy('yr', 'mo')
            ->get();

        $fyPaymentsMap = [];
        foreach ($fyPayments as $p) {
            $fyPaymentsMap[$p->yr . '-' . (int)$p->mo] = (float)$p->total;
        }

        // 3. Indian FY sequence: Apr -> Mar
        $allFyMonths = [
            ['mo' => 4,  'yr' => $fyStartYear, 'label' => 'Apr'],
            ['mo' => 5,  'yr' => $fyStartYear, 'label' => 'May'],
            ['mo' => 6,  'yr' => $fyStartYear, 'label' => 'Jun'],
            ['mo' => 7,  'yr' => $fyStartYear, 'label' => 'Jul'],
            ['mo' => 8,  'yr' => $fyStartYear, 'label' => 'Aug'],
            ['mo' => 9,  'yr' => $fyStartYear, 'label' => 'Sep'],
            ['mo' => 10, 'yr' => $fyStartYear, 'label' => 'Oct'],
            ['mo' => 11, 'yr' => $fyStartYear, 'label' => 'Nov'],
            ['mo' => 12, 'yr' => $fyStartYear, 'label' => 'Dec'],
            ['mo' => 1,  'yr' => $fyEndYear,   'label' => 'Jan'],
            ['mo' => 2,  'yr' => $fyEndYear,   'label' => 'Feb'],
            ['mo' => 3,  'yr' => $fyEndYear,   'label' => 'Mar'],
        ];

        // Display from April up to current month (at least 6 months: Apr-Sep)
        $currentFyIdx = ($now->month >= 4) ? ($now->month - 4) : ($now->month + 8);
        $maxDisplayIdx = max(5, $currentFyIdx);
        $trendMonthsList = array_slice($allFyMonths, 0, $maxDisplayIdx + 1);

        $trendCategories    = [];
        $trendRevenue       = [];
        $trendCollections   = [];
        $trendOutstanding   = [];
        $trendCollectionPct = [];

        foreach ($trendMonthsList as $item) {
            $mo = $item['mo'];
            $yr = $item['yr'];
            $key = $yr . '-' . $mo;

            $monthRevRaw = (float)($fyBookingsMap[$key] ?? 0.0);
            $monthColRaw = max(0.0, (float)($fyPaymentsMap[$key] ?? 0.0) - (float)($monthlyRefunds[$mo] ?? 0.0));
            $monthOutRaw = max(0.0, $monthRevRaw - $monthColRaw);

            // In Crores (₹ Cr) rounded to 2 decimals
            $revCr = round($monthRevRaw / 10000000, 2);
            $colCr = round($monthColRaw / 10000000, 2);
            $outCr = round($monthOutRaw / 10000000, 2);

            $trendCategories[]  = $item['label'];
            $trendRevenue[]     = $revCr;
            $trendCollections[] = $colCr;
            $trendOutstanding[] = $outCr;

            // Accurate Collection % per month
            if ($monthRevRaw > 0) {
                $trendCollectionPct[] = round(($monthColRaw / $monthRevRaw) * 100, 1);
            } else {
                $trendCollectionPct[] = 0.0;
            }
        }

        // Live Overall Collection Rate from original DB data
        $overallCollectionRatePct = ($totalSales > 0) ? round(($totalCollections / $totalSales) * 100, 1) : 0.0;
        $overallCollectionRate    = $overallCollectionRatePct . '%';

        // Calculate dynamic chart max for Y-axis (in Crores) with appropriate headroom
        $maxMetric = max(array_merge($trendRevenue, $trendCollections, $trendOutstanding, [0.0]));
        if ($maxMetric <= 5) {
            $trendChartMax = 5;
        } elseif ($maxMetric <= 10) {
            $trendChartMax = 10;
        } elseif ($maxMetric <= 20) {
            $trendChartMax = 20;
        } elseif ($maxMetric <= 30) {
            $trendChartMax = 30;
        } elseif ($maxMetric <= 50) {
            $trendChartMax = 50;
        } else {
            $trendChartMax = (int)(ceil($maxMetric / 10) * 10);
        }

        // ── Unit Status Donut ─────────────────────────────────────────────────
        $unitStatusCounts = $unitQuery()->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $donutAvailable = $unitStatusCounts['available'] ?? 0;
        $donutSold      = $unitStatusCounts['sold']      ?? ($unitStatusCounts['booked'] ?? 0);
        $donutReserved  = $unitStatusCounts['reserved']  ?? 0;

        // ── Cash Flow mini chart (last 12 months of payments) ─────────────────
        $cashFlowData = $paymentQuery()
            ->where('receipt_date', '>=', Carbon::now()->subMonths(12))
            ->selectRaw('MONTH(receipt_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $cashFlowSeries = [];
        for ($m = 1; $m <= 12; $m++) {
            $cashFlowSeries[] = round(max(0.0, ($cashFlowData[$m] ?? 0) - ($monthlyRefunds[$m] ?? 0)) / 100000, 2);
        }

        return view('dashboard', compact(
            'activeProject',
            'totalProjects',
            'totalUnits',
            'availableUnits',
            'bookedUnits',
            'soldUnitsCount',
            'soldUnitsValue',
            'reservedUnits',
            'totalCustomers',
            'totalSales',
            'totalBookingsCount',
            'totalCollections',
            'grossRealizedCollections',
            'totalRefundsPaid',
            'outstanding',
            'pendingApprovals',
            'recentUnits',
            'topCustomers',
            'recentBookings',
            'pendingEmiAlerts',
            'revenueData',
            'collectionsData',
            'donutAvailable',
            'donutSold',
            'donutReserved',
            'cashFlowSeries',
            'siteExpensePayable',
            'contractorRaBillPayable',
            'customerRefundPayable',
            'brokerCommissionPayable',
            'otherPayables',
            'totalPayables',
            'trendCategories',
            'trendRevenue',
            'trendCollections',
            'trendOutstanding',
            'trendCollectionPct',
            'overallCollectionRate',
            'trendChartMax'
        ));
    }
}
