<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleExtraWork extends Model
{
    protected $table = 'sale_extra_works';

    protected $fillable = [
        'sale_id',
        'description',
        'amount',
        'gst_type',
        'gst_percentage',
        'gst_amount',
        'line_total',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
