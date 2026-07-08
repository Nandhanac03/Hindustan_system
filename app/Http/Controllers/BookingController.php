<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Unit;
use App\Models\Customer;
use App\Models\SalesExecutive;
use App\Models\Project;
use App\Models\ActivityLog;
use App\Services\UnitStatusService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::where('is_active', true)->get();
        $selectedProject = $request->project_id ? Project::find($request->project_id) : null;

        $floors = \App\Models\Floor::when($selectedProject, fn($q) => $q->where('project_id', $selectedProject->id))->orderBy('floor_number')->get();
        $unitTypes = \App\Models\UnitType::where('is_active', true)
            ->when($selectedProject, fn($q) => $q->where(fn($sub) => $sub->whereNull('project_id')->orWhere('project_id', $selectedProject->id)))
            ->get();
        $brokers = \App\Models\Broker::orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();
        
        $query = Booking::with(['customer', 'project', 'unit.floor', 'unit.unitType', 'salesExecutive', 'broker']);

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('floor_id')) {
            $query->whereHas('unit', function ($q) use ($request) {
                $q->where('floor_id', $request->floor_id);
            });
        }

        if ($request->filled('unit_type_id')) {
            $query->whereHas('unit', function ($q) use ($request) {
                $q->where('unit_type_id', $request->unit_type_id);
            });
        }

        if ($request->filled('unit_number')) {
            $query->whereHas('unit', function ($q) use ($request) {
                $q->where('door_no', 'like', '%' . $request->unit_number . '%');
            });
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('broker_id')) {
            $query->where('broker_id', $request->broker_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('min_sqft')) {
            $query->whereHas('unit', function ($q) use ($request) {
                $q->where('built_up_area', '>=', $request->min_sqft);
            });
        }

        if ($request->filled('max_sqft')) {
            $query->whereHas('unit', function ($q) use ($request) {
                $q->where('built_up_area', '<=', $request->max_sqft);
            });
        }

        if ($request->filled('min_price')) {
            $query->where('amount', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('amount', '<=', $request->max_price);
        }

        if ($request->filled('start_date')) {
            $query->where('agreement_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('agreement_date', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', '%' . $search . '%')
                  ->orWhereHas('customer', function ($sub) use ($search) {
                      $sub->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $bookings = $query->latest()->paginate(15);

        return view('bookings.index', compact('bookings', 'projects', 'floors', 'unitTypes', 'brokers', 'customers'));
    }

    public function create(Request $request): View
    {
        $projects = Project::where('is_active', true)->get();
        $customers = Customer::orderBy('name')->get();
        $executives = SalesExecutive::orderBy('name')->get();
        $brokers = \App\Models\Broker::orderBy('name')->get();

        $selectedUnit = null;
        if ($request->has('unit_id')) {
            $selectedUnit = Unit::with(['project', 'floor', 'unitType'])->find($request->unit_id);
        }

        // Check if GST is enabled on the current logged-in user's system
        $system = Auth::user()->system;
        $gstEnabled = $system ? $system->gst_enabled : false;

        return view('bookings.create', compact('projects', 'customers', 'executives', 'brokers', 'selectedUnit', 'gstEnabled'));
    }

    public function store(Request $request, UnitStatusService $statusService): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'sales_executive_id' => ['required', 'exists:sales_executives,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'agreement_date' => ['nullable', 'date'],
            'registration_date' => ['nullable', 'date'],
            'broker_id' => ['nullable', 'exists:brokers,id'],
            'sale_rate_per_sqft' => ['required', 'numeric', 'min:0.01'],
            'gst_behavior' => ['required', 'in:none,inclusive,exclusive'],
            'gst_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $unit = Unit::findOrFail($validated['unit_id']);

        if (!in_array($unit->status, ['available', 'blocked'], true)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['unit_id' => 'The selected unit is not available for booking.']);
        }

        $booking = DB::transaction(function () use ($validated, $unit, $statusService) {
            // Create booking
            $booking = Booking::create([
                'booking_number' => 'BK-' . strtoupper(bin2hex(random_bytes(4))),
                'customer_id' => $validated['customer_id'],
                'project_id' => $unit->project_id,
                'unit_id' => $unit->id,
                'sales_executive_id' => $validated['sales_executive_id'],
                'amount' => $validated['amount'],
                'status' => 'approved',
                'agreement_date' => $validated['agreement_date'] ?: null,
                'registration_date' => $validated['registration_date'] ?: null,
                'broker_id' => $validated['broker_id'] ?: null,
                'sale_rate_per_sqft' => $validated['sale_rate_per_sqft'],
                'gst_behavior' => $validated['gst_behavior'],
                'gst_amount' => $validated['gst_amount'],
            ]);

            // If a broker is attached, let's create a deal record automatically
            if ($booking->broker_id) {
                $broker = \App\Models\Broker::find($booking->broker_id);
                $saleVal = (float)$booking->amount - ($booking->gst_behavior === 'exclusive' ? 0 : (float)$booking->gst_amount);
                $deal = \App\Models\Deal::create([
                    'system_id' => $booking->project->system_id,
                    'broker_id' => $booking->broker_id,
                    'project_id' => $booking->project_id,
                    'booking_id' => $booking->id,
                    'sale_value' => $saleVal,
                    'commission_pct_override' => $broker->default_commission_pct,
                    'trigger_condition' => 'full_collection',
                ]);

                $commAmount = round(($saleVal * ((float)$broker->default_commission_pct / 100)), 2);
                $isPaid = $booking->outstanding <= 0;
                \App\Models\CommissionEntry::create([
                    'system_id' => $booking->project->system_id,
                    'deal_id' => $deal->id,
                    'amount' => $commAmount,
                    'status' => $isPaid ? 'Payable' : 'Accrued',
                    'triggered_at' => $isPaid ? now() : null,
                ]);
            }

            // Transition unit status to booked
            if ($unit->status === 'available') {
                $statusService->transitionTo($unit, 'blocked', 'Temporarily blocked during booking process');
            }
            $statusService->transitionTo($unit, 'booked', "Booked under Booking #{$booking->booking_number}");

            // Update Unit with booking sales pricing details
            $saleAmount = (float)$booking->amount;
            $saleRate = (float)$booking->sale_rate_per_sqft;
            $unitDifference = (float)$unit->expected_sale_amount - $saleAmount;

            $unit->update([
                'sale_rate_per_sqft' => $saleRate,
                'sale_amount' => $saleAmount,
                'difference' => $unitDifference,
                'gst_behavior' => $booking->gst_behavior,
                'gst_amount' => $booking->gst_amount,
            ]);

            ActivityLog::record(
                'booking.created',
                "Created Booking #{$booking->booking_number} for customer {$booking->customer->name} on Unit {$unit->door_no} (₹" . number_format((float)$validated['amount'], 2) . ").",
                $booking
            );

            return $booking;
        });

        return redirect()->route('bookings.index')
            ->with('status', "Booking #{$booking->booking_number} created successfully.");
    }

    public function cancel(Booking $booking, UnitStatusService $statusService): RedirectResponse
    {
        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('error', 'This booking is already cancelled.');
        }

        DB::transaction(function () use ($booking, $statusService) {
            $booking->update(['status' => 'cancelled']);
            
            // Release the unit back to available
            $statusService->transitionTo($booking->unit, 'available', "Released due to Booking #{$booking->booking_number} cancellation");

            // Clear booking sales pricing details on unit release
            $booking->unit->update([
                'sale_rate_per_sqft' => null,
                'sale_amount' => null,
                'difference' => null,
                'gst_behavior' => 'none',
                'gst_amount' => 0.00,
            ]);

            ActivityLog::record(
                'booking.cancelled',
                "Cancelled Booking #{$booking->booking_number} for customer {$booking->customer->name}. Released Unit {$booking->unit->door_no}.",
                $booking
            );
        });

        return redirect()->route('bookings.index')
            ->with('status', "Booking #{$booking->booking_number} cancelled successfully. Unit is now available.");
    }

    public function resale(Booking $booking, UnitStatusService $statusService): RedirectResponse
    {
        $unit = $booking->unit;

        if ($unit->status !== 'sold') {
            return redirect()->back()->with('error', 'Only sold units can be placed for resale.');
        }

        DB::transaction(function () use ($booking, $unit, $statusService) {
            // Cancel booking/mark as resale
            $booking->update(['status' => 'cancelled']);

            // Transition sold unit to available with resale flag
            $statusService->transitionTo($unit, 'available', "Placed for resale from old Booking #{$booking->booking_number}", true);

            // Clear booking sales pricing details on unit resale
            $unit->update([
                'sale_rate_per_sqft' => null,
                'sale_amount' => null,
                'difference' => null,
                'gst_behavior' => 'none',
                'gst_amount' => 0.00,
            ]);

            ActivityLog::record(
                'booking.resale',
                "Released sold Unit {$unit->door_no} for resale. Cancelled old Booking #{$booking->booking_number}.",
                $booking
            );
        });

        return redirect()->route('bookings.index')
            ->with('status', "Unit {$unit->door_no} has been released for resale.");
    }
}
