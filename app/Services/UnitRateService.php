<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Unit;
use App\Models\UnitRateLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UnitRateService
{
    public function updateRate(Unit $unit, float $rate, string $effectiveFrom, ?string $reason = null, ?string $revisionType = 'Base Price Adjustment', ?string $changeDetails = null, ?float $amountChange = null): void
    {
        DB::transaction(function () use ($unit, $rate, $effectiveFrom, $reason, $revisionType, $changeDetails, $amountChange) {
            $isParking = $unit->unitType && (strtolower($unit->unitType->name) === 'parking' || strtolower($unit->unitType->category) === 'parking');

            if ($isParking) {
                $expectedSale = $rate;
                $expectedRate = null;
            } else {
                $expectedRate = $rate;
                $expectedSale = $unit->built_up_area ? ((float)$unit->built_up_area * $rate) : null;
            }

            // Update units table expected_rate_per_sqft and calculate expected_sale_amount / difference
            $difference = null;
            if ($expectedSale !== null && $unit->sale_amount !== null) {
                $difference = (float)$expectedSale - (float)$unit->sale_amount;
            }
            $unit->update([
                'expected_rate_per_sqft' => $expectedRate,
                'expected_sale_amount' => $expectedSale,
                'difference' => $difference,
            ]);

            // Append record to unit_rate_logs
            UnitRateLog::create([
                'unit_id' => $unit->id,
                'rate' => $rate,
                'revision_type' => $revisionType,
                'change_details' => $changeDetails,
                'amount_change' => $amountChange,
                'effective_from' => $effectiveFrom,
                'changed_by' => Auth::id(),
                'reason' => $reason,
            ]);
        });
    }
}
