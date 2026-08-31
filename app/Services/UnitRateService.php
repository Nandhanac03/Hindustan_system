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

            // Calculate amountChange if null
            if ($amountChange === null) {
                if ($isParking) {
                    $prevPrice = (float)($unit->expected_sale_amount ?? 0.0);
                    $amountChange = $rate - $prevPrice;
                    $rateDiff = $amountChange;
                } else {
                    $prevRate = (float)($unit->expected_rate_per_sqft ?? 0.0);
                    $prevPrice = (float)($unit->expected_sale_amount ?? ($prevRate * ($unit->built_up_area ?? 0.0)));
                    $newPrice = $rate * (float)($unit->built_up_area ?? 0.0);
                    $amountChange = $newPrice - $prevPrice;
                    $rateDiff = $rate - $prevRate;
                }
            } else {
                if ($isParking) {
                    $rateDiff = $amountChange;
                } else {
                    $prevRate = (float)($unit->expected_rate_per_sqft ?? 0.0);
                    $rateDiff = $rate - $prevRate;
                }
            }

            // Calculate changeDetails if null
            if ($changeDetails === null) {
                $revType = $revisionType ?: 'Base Price Adjustment';
                $changeDetails = str_replace('_', ' ', ucfirst($revType));
                if ($amountChange > 0) {
                    $changeDetails .= ' increased by +' . number_format($rateDiff, 2) . ' / sqft';
                } elseif ($amountChange < 0) {
                    $changeDetails .= ' decreased by -' . number_format(abs($rateDiff), 2) . ' / sqft';
                } else {
                    $changeDetails .= ' set to same rate';
                }
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
                'revision_type' => $revisionType ?: 'Base Price Adjustment',
                'change_details' => $changeDetails,
                'amount_change' => $amountChange,
                'effective_from' => $effectiveFrom,
                'changed_by' => Auth::id(),
                'reason' => $reason,
            ]);
        });
    }
}
