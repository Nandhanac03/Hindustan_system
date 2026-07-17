<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';

    protected $fillable = [
        'system_id',
        'project_id',
        'unit_id',
        'customer_id',
        'broker_id',
        'agreement_date',
        'registration_date',
        'sale_number',
        'sale_date',
        'rate_per_sqft',
        'sale_amount',
        'gst_type',
        'gst_percentage',
        'gst_amount',
        'base_amount',
        'total_amount',
        'payment_plan',
        'emi_type',
        'emi_installment_count',
        'emi_frequency',
        'first_installment_date',
        'remaining_balance',
        'status',
        'notes',
        'cancellation_reason',
        'cancellation_fee',
        'refund_amount',
    ];

    protected $casts = [
        'rate_per_sqft'     => 'decimal:2',
        'sale_amount'       => 'decimal:2',
        'gst_percentage'    => 'decimal:2',
        'gst_amount'        => 'decimal:2',
        'base_amount'       => 'decimal:2',
        'total_amount'      => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'cancellation_fee'  => 'decimal:2',
        'refund_amount'     => 'decimal:2',
        'sale_date'         => 'date',
        'agreement_date'    => 'date',
        'registration_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

    public function saleUnits(): HasMany
    {
        return $this->hasMany(SaleUnit::class, 'sale_id');
    }

    public function extraWorks(): HasMany
    {
        return $this->hasMany(SaleExtraWork::class, 'sale_id');
    }

    public function getComputedSaleAmountAttribute(): float
    {
        return (float) $this->saleUnits->sum('base_amount');
    }

    public function getComputedGstAmountAttribute(): float
    {
        return (float) $this->saleUnits->sum('gst_amount');
    }

    public function getComputedTotalAmountAttribute(): float
    {
        return (float) $this->saleUnits->sum('line_total');
    }

    public function getComputedBrokerageAmountAttribute(): float
    {
        return (float) $this->saleUnits->sum('brokerage_amount');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class, 'sale_id');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(SaleStatusLog::class, 'sale_id')->latest();
    }

    public function emiSchedules(): HasMany
    {
        return $this->hasMany(EmiSchedule::class, 'sale_id')->orderBy('installment_no');
    }

    public function brokerage(): HasOne
    {
        return $this->hasOne(Brokerage::class, 'sale_id');
    }
}