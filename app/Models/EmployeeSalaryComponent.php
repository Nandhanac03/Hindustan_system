<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSalaryComponent extends Model
{
    protected $fillable = [
        'employee_id',
        'salary_component_id',
        'name',
        'description',
        'type',
        'source',
        'calculation_type',
        'configured_value',
        'calculated_amount',
    ];

    protected $casts = [
        'configured_value' => 'decimal:2',
        'calculated_amount' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function masterComponent(): BelongsTo
    {
        return $this->belongsTo(SalaryComponent::class, 'salary_component_id');
    }
}
