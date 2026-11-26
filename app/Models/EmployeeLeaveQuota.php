<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeLeaveQuota extends Model
{
    protected $fillable = [
        'system_id',
        'financial_period_id',
        'employee_id',
        'allowed_leave',
        'taken_leave',
    ];

    protected $casts = [
        'allowed_leave' => 'float',
        'taken_leave' => 'float',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function financialPeriod(): BelongsTo
    {
        return $this->belongsTo(PayrollFinancialPeriod::class, 'financial_period_id');
    }
}
