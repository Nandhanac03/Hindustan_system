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
        $totalProjects      = Project::count();
        $totalUnits         = Unit::count();
        $availableUnits     = Unit::where('status', 'available')->count();
        $bookedUnits        = Unit::whereIn('status', ['booked', 'sold'])->count();
        $reservedUnits      = Unit::where('status', 'reserved')->count();
        $totalCustomers     = Customer::count();
        $totalSales         = Booking::where('status', 'approved')->sum('amount');
        $totalCollections   = Payment::where('status', 'completed')->sum('amount');
        $pendingApprovals   = ApprovalRequest::where('status', 'pending')->count();

        // Outstanding = Sales - Collections
        $outstanding = max(0, $totalSales - $totalCollections);

        // ── Recent Projects ───────────────────────────────────────────────────
        $recentProjects = Project::latest()
            ->take(5)
            ->get();

        // ── Top Customers (by total payment amount) ───────────────────────────
        $topCustomers = Customer::withSum(
                ['payments' => fn ($q) => $q->where('status', 'completed')],
                'amount'
            )
            ->withCount('bookings')
            ->orderByDesc('payments_sum_amount')
            ->take(5)
            ->get();

        // ── Pending Approvals (latest 5) ──────────────────────────────────────
        $approvalRequests = ApprovalRequest::where('status', 'pending')
            ->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')")
            ->latest()
            ->take(5)
            ->get();

        // ── Recent Activity ───────────────────────────────────────────────────
        $activityLogs = ActivityLog::latest()->take(6)->get();

        // ── Revenue Chart (monthly bookings + payments for current year) ──────
        $currentYear = Carbon::now()->year;

        $monthlyBookings = Booking::where('status', 'approved')
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyPayments = Payment::where('status', 'completed')
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
        $unitStatusCounts = Unit::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $donutAvailable = $unitStatusCounts['available'] ?? 0;
        $donutSold      = $unitStatusCounts['sold']      ?? ($unitStatusCounts['booked'] ?? 0);
        $donutReserved  = $unitStatusCounts['reserved']  ?? 0;

        // ── Cash Flow mini chart (last 12 months of payments) ─────────────────
        $cashFlowData = Payment::where('status', 'completed')
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
            'totalProjects',
            'totalUnits',
            'availableUnits',
            'bookedUnits',
            'reservedUnits',
            'totalCustomers',
            'totalSales',
            'totalCollections',
            'outstanding',
            'pendingApprovals',
            'recentProjects',
            'topCustomers',
            'approvalRequests',
            'activityLogs',
            'revenueData',
            'collectionsData',
            'donutAvailable',
            'donutSold',
            'donutReserved',
            'cashFlowSeries',
        ));
    }
}
