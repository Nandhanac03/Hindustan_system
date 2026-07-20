<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmiRescheduleLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'action_type',
        'reason',
        'old_schedule_snapshot',
        'new_schedule_snapshot',
        'performed_by',
    ];

    protected $casts = [
        'old_schedule_snapshot' => 'array',
        'new_schedule_snapshot' => 'array',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
