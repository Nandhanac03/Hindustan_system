<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\ActivityLog;
use App\Services\UnitStatusService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class EmiCollectionController extends Controller
{
    public function index(Request $request): View
    {
        $payments = Payment::with(['customer', 'booking', 'project'])
            ->latest()
            ->paginate(10);

        // Get some stats for EMI collections
        $totalReceived = Payment::where('status', 'completed')->sum('amount');
        $pendingPaymentsCount = Payment::where('status', 'pending')->count();
        $recentBookings = Booking::with(['customer', 'project', 'unit'])
            ->where('status', 'approved')
            ->latest()
            ->take(5)
            ->get();

        return view('emi-collections.index', compact('payments', 'totalReceived', 'pendingPaymentsCount', 'recentBookings'));
    }

    public function store(Request $request, UnitStatusService $statusService): JsonResponse
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'exists:bookings,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_mode' => ['required', 'in:Cash,Bank Transfer,Credit Card,UPI'],
        ]);

        $booking = Booking::with('unit')->findOrFail($validated['booking_id']);

        if ($validated['amount'] > $booking->outstanding) {
            return response()->json([
                'error' => 'Payment amount (₹' . number_format((float)$validated['amount'], 2) . ') exceeds outstanding balance (₹' . number_format($booking->outstanding, 2) . ').'
            ], 422);
        }

        $payment = DB::transaction(function () use ($validated, $booking, $statusService) {
            $payment = Payment::create([
                'receipt_number' => 'REC-' . strtoupper(bin2hex(random_bytes(4))),
                'customer_id' => $booking->customer_id,
                'project_id' => $booking->project_id,
                'booking_id' => $booking->id,
                'amount' => $validated['amount'],
                'payment_mode' => $validated['payment_mode'],
                'status' => 'completed',
                'payment_date' => now(),
            ]);

            // Allocate share of collection to partners automatically
            $partnerShares = \App\Models\PartnerShare::where('project_id', $booking->project_id)->get();
            foreach ($partnerShares as $share) {
                $allocatedAmount = (float)$payment->amount * ((float)$share->share_pct / 100);
                \App\Models\PartnerAllocation::create([
                    'system_id' => $share->system_id,
                    'partner_id' => $share->partner_id,
                    'project_id' => $booking->project_id,
                    'payment_id' => $payment->id,
                    'allocated_amount' => $allocatedAmount,
                    'date' => now(),
                ]);
            }

            $outstanding = $booking->fresh()->outstanding;

            if ($outstanding <= 0 && $booking->unit->status === 'booked') {
                $statusService->transitionTo(
                    $booking->unit,
                    'sold',
                    "Full payment cleared under Booking {$booking->booking_number}"
                );
            }

            ActivityLog::record(
                'payment.collected',
                "Collected payment ₹" . number_format((float)$validated['amount'], 2) . " via {$validated['payment_mode']} for Booking {$booking->booking_number}.",
                $payment
            );

            return $payment;
        });

        return response()->json([
            'success' => true,
            'message' => 'Installment collected successfully.',
            'payment' => $payment
        ]);
    }

    public function schedules(Request $request): View
    {
        return view('emi-collections.schedules');
    }

    public function receipts(Request $request): View
    {
        return view('emi-collections.receipts');
    }

    public function outstanding(Request $request): View
    {
        return view('emi-collections.outstanding');
    }

    public function cashBook(Request $request): View
    {
        return view('emi-collections.cash-book');
    }
}
