<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleUnit extends Model
{
    use HasFactory;

    protected $table = 'sale_units';

    protected $fillable = [
        'sale_id',
        'unit_id',
        'wing',
        'rate_per_sqft',
        'area_sqft',
        'base_amount',
        'gst_type',
        'gst_percentage',
        'gst_amount',
        'line_total',
        'brokerage_type',
        'brokerage_value',
        'brokerage_amount',
    ];

    protected $casts = [
        'rate_per_sqft'    => 'decimal:2',
        'area_sqft'        => 'decimal:2',
        'base_amount'      => 'decimal:2',
        'gst_percentage'   => 'decimal:2',
        'gst_amount'       => 'decimal:2',
        'line_total'       => 'decimal:2',
        'brokerage_value'  => 'decimal:2',
        'brokerage_amount' => 'decimal:2',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
