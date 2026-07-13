<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sale_number', 'project_id', 'unit_id', 'customer_id', 'broker_id',
        'rate_per_sqft', 'sale_amount', 'gst_applicable', 'gst_type', 'gst_percentage',
        'gst_amount', 'base_amount', 'total_amount', 'sale_date', 'agreement_date', 'registration_date',
        'status', 'original_sale_id', 'is_resale', 'cancellation_reason', 'cancelled_at',
        'cancellation_fee', 'refund_amount',
        'broker_involved', 'payment_plan', 'emi_plan_type', 'remaining_balance',
        'notes', 'created_by', 'bank_id',
    ];

    protected $casts = [
        'gst_applicable' => 'boolean',
        'is_resale' => 'boolean',
        'broker_involved' => 'boolean',
        'sale_date' => 'date',
        'agreement_date' => 'date',
        'registration_date' => 'date',
        'cancelled_at' => 'datetime',
    ];

    public function receipts()
    {
        return $this->hasMany(Receipt::class)->orderByDesc('receipt_date');
    }

    public function brokerage()
    {
        return $this->hasOne(Brokerage::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function broker()
    {
        return $this->belongsTo(Broker::class, 'broker_id');
    }

    public function originalSale()
    {
        return $this->belongsTo(Sale::class, 'original_sale_id');
    }

    public function statusLogs()
    {
        return $this->hasMany(SaleStatusLog::class)->orderByDesc('created_at');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function getBankNameAttribute()
    {
        return $this->bank?->bank_name;
    }
}