<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Unit;
use App\Models\UnitRateLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UnitRateService
{
    public function updateRate(Unit $unit, float $rate, string $effectiveFrom, ?string $reason = null): void
    {
        DB::transaction(function () use ($unit, $rate, $effectiveFrom, $reason) {
            // Update units table base_rate
            $unit->update(['base_rate' => $rate]);

            // Append record to unit_rate_logs
            UnitRateLog::create([
                'unit_id' => $unit->id,
                'rate' => $rate,
                'effective_from' => $effectiveFrom,
                'changed_by' => Auth::id(),
                'reason' => $reason,
            ]);
        });
    }
}
