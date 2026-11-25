<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollLeaveQuota extends Model
{
    protected $fillable = [
        'system_id',
        'financial_period_id',
        'leave_type',
        'annual_quota',
        'monthly_accrual',
        'carry_forward',
        'max_carry_forward',
        'description',
        'status',
    ];

    protected $casts = [
        'annual_quota' => 'decimal:1',
        'monthly_accrual' => 'decimal:2',
        'carry_forward' => 'boolean',
        'max_carry_forward' => 'decimal:1',
    ];

    public function financialPeriod(): BelongsTo
    {
        return $this->belongsTo(PayrollFinancialPeriod::class, 'financial_period_id');
    }
}
