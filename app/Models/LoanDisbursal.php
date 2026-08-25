<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSystemScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanDisbursal extends Model
{
    use HasSystemScope;

    protected $table = 'loan_disbursals';

    protected $fillable = [
        'system_id',
        'loan_id',
        'disbursal_no',
        'disbursal_date',
        'amount',
        'reference_no',
        'transaction_no',
        'disbursal_type',
        'status',
        'remarks',
        'created_by',
        'posted_by',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'disbursal_date' => 'date',
        'amount' => 'decimal:2',
        'cancelled_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->system_id = auth()->user()->system_id;
                $model->created_by = auth()->id();
            }
        });
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function poster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function canceller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
}
