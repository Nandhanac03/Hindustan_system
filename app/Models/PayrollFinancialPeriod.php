<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollFinancialPeriod extends Model
{
    protected $fillable = [
        'system_id',
        'name',
        'start_date',
        'end_date',
        'working_days',
        'is_current',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'working_days' => 'integer',
    ];

    public function leaveQuotas(): HasMany
    {
        return $this->hasMany(PayrollLeaveQuota::class, 'financial_period_id');
    }

    public function salaryHeads(): HasMany
    {
        return $this->hasMany(PayrollSalaryHead::class, 'financial_period_id');
    }
}
