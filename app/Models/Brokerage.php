<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Brokerage extends Model
{
    use HasFactory;

    protected $table = 'brokerages';

    protected $fillable = [
        'sale_id',
        'sale_unit_id',
        'broker_id',
        'commission_type',
        'commission_percent',
        'commission_amount',
        'paid_amount',
        'status',
        'paid_date',
    ];

    protected $casts = [
        'commission_percent' => 'decimal:2',
        'commission_amount'  => 'decimal:2',
        'paid_amount'        => 'decimal:2',
        'paid_date'          => 'date',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function saleUnit(): BelongsTo
    {
        return $this->belongsTo(SaleUnit::class, 'sale_unit_id');
    }

    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }
}