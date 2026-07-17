<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanInterestLog extends Model
{
    protected $fillable = [
        'loan_id',
        'old_interest_rate',
        'new_interest_rate',
        'interest_period',
        'reason',
    ];

    protected $casts = [
        'old_interest_rate' => 'decimal:2',
        'new_interest_rate' => 'decimal:2',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
}
