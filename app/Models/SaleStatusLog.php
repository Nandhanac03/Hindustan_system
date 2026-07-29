<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleStatusLog extends Model
{
    protected $fillable = [
        'sale_id', 'from_status', 'to_status', 'event_type', 'reason', 'performed_by', 'snapshot_data',
    ];

    protected $casts = [
        'snapshot_data' => 'array',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}