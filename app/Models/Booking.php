<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'customer_id',
        'project_id',
        'unit_id',
        'sales_executive_id',
        'amount',
        'status',
        'agreement_date',
        'registration_date',
        'broker_id',
        'sale_rate_per_sqft',
        'gst_behavior',
        'gst_amount',
    ];

    protected $casts = [
        'customer_id'        => 'integer',
        'project_id'         => 'integer',
        'unit_id'            => 'integer',
        'sales_executive_id' => 'integer',
        'amount'             => 'decimal:2',
        'agreement_date'     => 'date',
        'registration_date'  => 'date',
        'sale_rate_per_sqft' => 'float',
        'gst_amount'         => 'float',
    ];

    protected $appends = [
        'outstanding',
        'expected_sale_value',
        'actual_sale_value',
        'profit_shortfall',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function salesExecutive(): BelongsTo
    {
        return $this->belongsTo(SalesExecutive::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

    public function getOutstandingAttribute(): float
    {
        $totalPaid = $this->payments()
            ->where('status', 'completed')
            ->sum('amount');

        return (float)max(0, (float)$this->amount - (float)$totalPaid);
    }

    public function getExpectedSaleValueAttribute(): float
    {
        return $this->unit ? ((float)$this->unit->bua_area * (float)$this->unit->base_rate) : 0.0;
    }

    public function getActualSaleValueAttribute(): float
    {
        return $this->unit && $this->sale_rate_per_sqft 
            ? ((float)$this->unit->bua_area * (float)$this->sale_rate_per_sqft) 
            : (float)$this->amount;
    }

    public function getProfitShortfallAttribute(): float
    {
        return $this->actual_sale_value - $this->expected_sale_value;
    }
}
