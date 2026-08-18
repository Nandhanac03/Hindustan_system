<?php

namespace App\Services;

use App\Models\CustomerInstallment;
use Carbon\Carbon;

class CollectionAgeingService
{
    /**
     * Get the ageing bucket for a given number of overdue days.
     */
    public function getAgeingBucket(int $daysOverdue): string
    {
        if ($daysOverdue <= 30) {
            return '0-30';
        } elseif ($daysOverdue <= 60) {
            return '31-60';
        } elseif ($daysOverdue <= 90) {
            return '61-90';
        } elseif ($daysOverdue <= 120) {
            return '91-120';
        } else {
            return '120+';
        }
    }

    /**
     * Get the risk level based on the ageing bucket.
     */
    public function getRiskLevel(string $bucket): string
    {
        $levels = config('collection.risk_levels', [
            '0-30' => 'Low',
            '31-60' => 'Medium',
            '61-90' => 'High',
            '91-120' => 'Critical',
            '120+' => 'Severe',
        ]);

        return $levels[$bucket] ?? 'Unknown';
    }

    /**
     * Get the risk level color for UI.
     */
    public function getRiskColor(string $risk): string
    {
        return match(strtolower($risk)) {
            'low' => 'text-emerald-600 bg-emerald-50 border-emerald-200',
            'medium' => 'text-amber-600 bg-amber-50 border-amber-200',
            'high' => 'text-orange-600 bg-orange-50 border-orange-200',
            'critical' => 'text-red-600 bg-red-50 border-red-200',
            'severe' => 'text-rose-700 bg-rose-100 border-rose-300',
            default => 'text-slate-600 bg-slate-50 border-slate-200',
        };
    }

    public function calculateDaysOverdue(Carbon $dueDate, Carbon $asOfDate = null): int
    {
        $asOfDate = $asOfDate ?? Carbon::today();
        
        // If due date is strictly in the future, it's not overdue
        if ($dueDate->startOfDay()->isAfter($asOfDate->startOfDay())) {
            return 0;
        }

        return (int) $dueDate->startOfDay()->diffInDays($asOfDate->startOfDay());
    }
}
