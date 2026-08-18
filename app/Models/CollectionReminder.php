<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'sale_id',
        'installment_id',
        'reminder_level',
        'channel',
        'message',
        'scheduled_at',
        'sent_at',
        'status',
        'response',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function installment()
    {
        return $this->belongsTo(CustomerInstallment::class, 'installment_id');
    }
}
