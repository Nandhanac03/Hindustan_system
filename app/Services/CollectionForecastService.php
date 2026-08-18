<?php

namespace App\Services;

class CollectionForecastService
{
    /**
     * Get the probability for a given ageing bucket.
     */
    public function getProbability(string $bucket): float
    {
        $probabilities = config('collection.probabilities', [
            '0-30' => 0.90,
            '31-60' => 0.70,
            '61-90' => 0.50,
            '91-120' => 0.30,
            '120+' => 0.20,
        ]);

        return $probabilities[$bucket] ?? 0.0;
    }

    /**
     * Calculate forecast amount based on outstanding amount and probability.
     */
    public function calculateForecastAmount(float $outstandingAmount, float $probability): float
    {
        return $outstandingAmount * $probability;
    }
    
    /**
     * Get probability percentage string for UI.
     */
    public function getProbabilityPercentage(string $bucket): string
    {
        return ($this->getProbability($bucket) * 100) . '%';
    }
}
