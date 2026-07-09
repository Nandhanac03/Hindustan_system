<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmiSchedule extends Model
{
    use HasFactory;

    protected $table = 'emi_schedules';

    protected $fillable = [
        'system_id',
        'loan_id',
        'installment_no',
        'due_date',
        'emi_amount',
        'principal_component',
        'interest_component',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'emi_amount' => 'decimal:2',
        'principal_component' => 'decimal:2',
        'interest_component' => 'decimal:2',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
