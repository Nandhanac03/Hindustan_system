<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_id',
        'project_id',
        'lender_name',
        'principal_amount',
        'interest_rate',
        'tenure_months',
        'start_date',
        'schedule_type',
        'outstanding_balance',
        'ledger_account_id',
        'interest_account_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'principal_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function emiSchedules()
    {
        return $this->hasMany(EmiSchedule::class);
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
