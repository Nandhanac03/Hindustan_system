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
        'casual_allowed',
        'medical_allowed',
        'casual_taken',
        'medical_taken',
    ];

    protected $casts = [
        'casual_allowed' => 'float',
        'medical_allowed' => 'float',
        'casual_taken' => 'float',
        'medical_taken' => 'float',
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
