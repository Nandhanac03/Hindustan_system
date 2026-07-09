<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSystemScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasSystemScope;

    protected $table = 'loans';

    protected $fillable = [
        'system_id',
        'project_id',
        'loan_account_no',
        'lender_name',
        'principal_amount',
        'interest_rate',
        'tenure_months',
        'start_date',
        'schedule_type',
        'outstanding_balance',
        'ledger_account_id',
        'interest_account_id',
        'status',
    ];

    protected $casts = [
        'principal_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'tenure_months' => 'integer',
        'start_date' => 'date',
        'outstanding_balance' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->system_id = auth()->user()->system_id;
            }
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function ledgerAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'ledger_account_id');
    }

    public function interestAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'interest_account_id');
    }

    public function emiSchedules(): HasMany
    {
        return $this->hasMany(EmiSchedule::class, 'loan_id')->orderBy('installment_no');
    }

    public function prepayments(): HasMany
    {
        return $this->hasMany(LoanPrepayment::class, 'loan_id')->latest();
    }

    public function getBaseEmiAttribute(): float
    {
        $principal   = (float)$this->principal_amount;
        $annualRate  = (float)$this->interest_rate;
        $monthlyRate = $annualRate / 12 / 100;
        $tenure      = (int)$this->tenure_months;

        if ($principal <= 0 || $tenure <= 0) {
            return 0.0;
        }

        if ($this->schedule_type === 'flat') {
            $totalInterest = $principal * $annualRate * ($tenure / 12) / 100;
            return round(($principal + $totalInterest) / $tenure, 2);
        }

        // Reducing balance
        if ($monthlyRate <= 0) {
            return round($principal / $tenure, 2);
        }

        $emi = $principal * $monthlyRate * pow(1 + $monthlyRate, $tenure) / (pow(1 + $monthlyRate, $tenure) - 1);
        return round($emi, 2);
    }
}
