<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollSalaryHead extends Model
{
    protected $fillable = [
        'system_id',
        'financial_period_id',
        'from_date',
        'to_date',
        'name',
        'month',
        'year',
        'payment_date',
        'total_employees',
        'total_gross',
        'total_deductions',
        'total_net',
        'status',
        'notes',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'payment_date' => 'date',
        'month' => 'integer',
        'year' => 'integer',
        'total_employees' => 'integer',
        'total_gross' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'total_net' => 'decimal:2',
    ];

    public function financialPeriod(): BelongsTo
    {
        return $this->belongsTo(PayrollFinancialPeriod::class, 'financial_period_id');
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(PayrollSalary::class, 'salary_head_id');
    }

    public function recalculateTotals(): void
    {
        $rows = $this->salaries()->get();
        $this->update([
            'total_employees' => $rows->count(),
            'total_gross' => (float) $rows->sum('gross_salary'),
            'total_deductions' => (float) $rows->sum('deductions'),
            'total_net' => (float) $rows->sum('net_salary'),
        ]);
    }
}
