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

        $unitQuery    = fn() => $activeProject ? Unit::where('project_id', $activeProject->id) : Unit::query();
        $bookingQuery = fn() => $activeProject ? Sale::where('project_id', $activeProject->id) : Sale::query();
        $paymentQuery = fn() => $activeProject ? Receipt::where('project_id', $activeProject->id) : Receipt::query();

        $totalUnits         = $unitQuery()->count();
        $availableUnits     = $unitQuery()->where('status', 'available')->count();
        $bookedUnits        = $unitQuery()->whereIn('status', ['booked', 'sold'])->count();
        $soldUnitsCount     = $unitQuery()->where('status', 'sold')->count();
        $soldUnitsValue     = $unitQuery()->where('status', 'sold')->sum('expected_sale_amount');
        $reservedUnits      = $unitQuery()->where('status', 'reserved')->count();
        $totalCustomers     = Customer::count();
        $totalSales         = (float) $bookingQuery()->where('status', 'active')->sum('total_amount');
        $totalCollections   = (float) $paymentQuery()->sum('amount');
        $pendingApprovals   = Approval::where('status', 'pending')->count();

        // Outstanding = Sales - Collections
        $outstanding = max(0.00, $totalSales - $totalCollections);

        // ── Recent Units ───────────────────────────────────────────────────
        $recentUnits = $unitQuery()->with(['project', 'floor', 'unitType'])->latest()
            ->take(5)
            ->get();

        // ── Top Customers (by total payment amount) ───────────────────────────
        $topCustomers = Customer::withSum(
                ['receipts' => fn ($q) => $activeProject ? $q->where('project_id', $activeProject->id) : $q],
                'amount'
            )
            ->withCount(['sales' => fn ($q) => $activeProject ? $q->where('status', 'active')->where('project_id', $activeProject->id) : $q->where('status', 'active')])
            ->orderByDesc('receipts_sum_amount')
            ->take(5)
            ->get();

        // ── Recent Bookings (replaces Approvals) ──────────────────────────────
        $recentBookings = $bookingQuery()->with(['unit', 'customer'])
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
        $currentYear = Carbon::now()->year;

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
            $collectionsData[] = round(($monthlyPayments[$m] ?? 0) / 100000, 2);
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
            $cashFlowSeries[] = round(($cashFlowData[$m] ?? 0) / 100000, 2);
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
            'totalCollections',
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
            'cashFlowSeries'
        ));
    }
}
