<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSalaryRevision extends Model
{
    protected $fillable = [
        'employee_id',
        'structure_name',
        'effective_from',
        'effective_to',
        'revision_label',
        'is_current',
        'base_salary',
        'gross_salary',
        'total_allowances',
        'total_deductions',
        'net_salary',
        'notes',
        'breakdown',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_current' => 'boolean',
        'base_salary' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'total_allowances' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'breakdown' => 'array',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
