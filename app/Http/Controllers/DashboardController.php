<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ApprovalRequest;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
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
        $bookingQuery = fn() => $activeProject ? Booking::where('project_id', $activeProject->id) : Booking::query();
        $paymentQuery = fn() => $activeProject ? Payment::where('project_id', $activeProject->id) : Payment::query();

        $totalUnits         = $unitQuery()->count();
        $availableUnits     = $unitQuery()->where('status', 'available')->count();
        $bookedUnits        = $unitQuery()->whereIn('status', ['booked', 'sold'])->count();
        $soldUnitsCount     = $unitQuery()->where('status', 'sold')->count();
        $soldUnitsValue     = $unitQuery()->where('status', 'sold')->sum('expected_sale_amount');
        $reservedUnits      = $unitQuery()->where('status', 'reserved')->count();
        $totalCustomers     = Customer::count();
        $totalSales         = $bookingQuery()->where('status', 'approved')->sum('amount');
        $totalCollections   = $paymentQuery()->where('status', 'completed')->sum('amount');
        $pendingApprovals   = ApprovalRequest::where('status', 'pending')->count();

        // Outstanding = Sales - Collections
        $outstanding = max(0, $totalSales - $totalCollections);

        // ── Recent Units ───────────────────────────────────────────────────
        $recentUnits = $unitQuery()->with(['project', 'floor', 'unitType'])->latest()
            ->take(5)
            ->get();

        // ── Top Customers (by total payment amount) ───────────────────────────
        $topCustomers = Customer::withSum(
                ['payments' => fn ($q) => $activeProject ? $q->where('status', 'completed')->where('project_id', $activeProject->id) : $q->where('status', 'completed')],
                'amount'
            )
            ->withCount(['bookings' => fn ($q) => $activeProject ? $q->where('project_id', $activeProject->id) : $q])
            ->orderByDesc('payments_sum_amount')
            ->take(5)
            ->get();

        // ── Recent Bookings (replaces Approvals) ──────────────────────────────
        $recentBookings = $bookingQuery()->with(['unit', 'customer'])
            ->latest()
            ->take(5)
            ->get();

        // ── Inventory Activity (replaces ActivityLog) ─────────────────────────
        $inventoryActivity = $unitQuery()->with('floor')->latest('updated_at')->take(6)->get();

        // ── Revenue Chart (monthly bookings + payments for current year) ──────
        $currentYear = Carbon::now()->year;

        $monthlyBookings = $bookingQuery()->where('status', 'approved')
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyPayments = $paymentQuery()->where('status', 'completed')
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
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
        $cashFlowData = $paymentQuery()->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
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
            'inventoryActivity',
            'revenueData',
            'collectionsData',
            'donutAvailable',
            'donutSold',
            'donutReserved',
            'cashFlowSeries',
        ));
    }
}
