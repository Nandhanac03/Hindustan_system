<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Projects breakdown
        $projects = Project::withCount(['units' => function ($q) {
            $q->where('is_active', true);
        }])->get();

        foreach ($projects as $proj) {
            $proj->available_count = Unit::where('project_id', $proj->id)->where('status', 'available')->count();
            $proj->sold_count      = Unit::where('project_id', $proj->id)->whereIn('status', ['sold', 'booked'])->count();
            $proj->reserved_count  = Unit::where('project_id', $proj->id)->where('status', 'reserved')->count();
            
            // Financials
            $proj->sales_sum       = Booking::where('project_id', $proj->id)->where('status', 'approved')->sum('amount');
            $proj->collection_sum  = Payment::where('project_id', $proj->id)->where('status', 'completed')->sum('amount');
            $proj->outstanding     = max(0, $proj->sales_sum - $proj->collection_sum);
        }

        // 2. Customer ledger summary
        $customers = Customer::withSum(
                ['payments' => fn ($q) => $q->where('status', 'completed')],
                'amount'
            )
            ->withCount('bookings')
            ->orderByDesc('payments_sum_amount')
            ->take(10)
            ->get();

        return view('reports.index', compact('projects', 'customers'));
    }
}
