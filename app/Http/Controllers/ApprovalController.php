<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        // Fetch all pending approvals with morph relations pre-loaded
        $approvals = Approval::where('status', 'pending')
            ->with('requester', 'approver')
            ->latest()
            ->get()
            ->filter(function ($approval) use ($user) {
                // If user is Owner, they can approve anything
                if ($user->hasRole('Owner')) {
                    return true;
                }
                
                // If it is a discount or cancellation request, only Owner can approve
                $type = strtolower(class_basename($approval->approvable_type));
                if ($type === 'booking' && $user->hasRole('Owner')) {
                    return true;
                }
                
                // If it is an expense or refund approval, Accountant can approve
                if (($type === 'expense' || $type === 'refund') && $user->hasPermissionTo('expenses.approve')) {
                    return true;
                }
                
                return false;
            });

        return view('approvals.index', compact('approvals'));
    }

    public function approve(Request $request, Approval $approval): RedirectResponse
    {
        $user = Auth::user();
        
        $approval->approve($user, $request->input('reason'));
        
        // Log action in activity logs
        ActivityLog::record(
            'Approval Approved',
            "Approved request #{$approval->id} ({$approval->approvable_type}) with reason: " . ($request->input('reason') ?? 'N/A'),
            $approval
        );

        return redirect()->route('approvals.index')
            ->with('status', 'Request approved successfully.');
    }

    public function reject(Request $request, Approval $approval): RedirectResponse
    {
        $user = Auth::user();
        
        $approval->reject($user, $request->input('reason'));
        
        // Log action in activity logs
        ActivityLog::record(
            'Approval Rejected',
            "Rejected request #{$approval->id} ({$approval->approvable_type}) with reason: " . ($request->input('reason') ?? 'N/A'),
            $approval
        );

        return redirect()->route('approvals.index')
            ->with('status', 'Request rejected successfully.');
    }
}
