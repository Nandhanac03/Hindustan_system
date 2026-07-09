<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brokerage extends Model
{
    protected $fillable = [
        'sale_id', 'broker_id', 'commission_type', 'commission_percent',
        'commission_amount', 'paid_amount', 'status', 'remarks',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function broker()
    {
        return $this->belongsTo(Broker::class, 'broker_id');
    }

    public function getBalanceDueAttribute(): float
    {
        return round($this->commission_amount - $this->paid_amount, 2);
    }
}