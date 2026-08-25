<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanPrepayment extends Model
{
    protected $table = 'loan_prepayments';

    protected $fillable = [
        'loan_id',
        'prepayment_amount',
        'prepayment_date',
        'reschedule_option',
        'previous_outstanding',
        'new_outstanding',
    ];

    protected $casts = [
        'prepayment_amount' => 'decimal:2',
        'prepayment_date' => 'date',
        'previous_outstanding' => 'decimal:2',
        'new_outstanding' => 'decimal:2',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
