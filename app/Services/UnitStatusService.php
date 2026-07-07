<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Unit;
use App\Models\UnitStatusLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UnitStatusService
{
    /**
     * Map from from_status to allowed array of to_statuses
     */
    private const TRANSITIONS = [
        'available' => ['blocked'],
        'blocked' => ['available', 'booked'],
        'booked' => ['sold', 'available'],
        'sold' => ['available'], // allowed only if $isResale = true
        'on_hold' => [], // no transitions allowed out of on_hold in standard map
    ];

    public function transitionTo(Unit $unit, string $toStatus, ?string $reason = null, bool $isResale = false): void
    {
        $fromStatus = $unit->status;

        if ($fromStatus === $toStatus) {
            return; // No change needed
        }

        // 1. Validate transition using the transition map
        $allowed = false;
        if (isset(self::TRANSITIONS[$fromStatus]) && in_array($toStatus, self::TRANSITIONS[$fromStatus], true)) {
            $allowed = true;

            // Extra validation for sold -> available (resale flag required)
            if ($fromStatus === 'sold' && $toStatus === 'available' && !$isResale) {
                $allowed = false;
            }
        }

        if (!$allowed) {
            throw new \InvalidArgumentException("Invalid status transition from '{$fromStatus}' to '{$toStatus}'.");
        }

        // 2. Perform database transaction with lockForUpdate for concurrency safety
        DB::transaction(function () use ($unit, $fromStatus, $toStatus, $reason) {
            // Retrieve unit and lock row for update
            $lockedUnit = Unit::where('id', $unit->id)->lockForUpdate()->first();

            if (!$lockedUnit) {
                throw new \Exception("Unit not found.");
            }

            // Verify status has not been changed concurrently by another request
            if ($lockedUnit->status !== $fromStatus) {
                throw new \Exception("Concurrency conflict: The status of unit '{$unit->unit_number}' has already been changed.");
            }

            // Update status
            $lockedUnit->status = $toStatus;
            $lockedUnit->save();

            // Write status change log
            UnitStatusLog::create([
                'unit_id' => $unit->id,
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'changed_by' => Auth::id(),
                'reason' => $reason,
            ]);

            // Record action in ActivityLog
            ActivityLog::record(
                'unit.status_changed',
                "Unit {$unit->unit_number} transitioned from '{$fromStatus}' to '{$toStatus}'. Reason: " . ($reason ?? 'N/A'),
                $lockedUnit
            );
        });

        // Sync local object state
        $unit->status = $toStatus;
    }
}
