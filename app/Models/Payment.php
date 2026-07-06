<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_number', 'customer_id', 'project_id',
        'booking_id', 'amount', 'payment_mode', 'status', 'payment_date',
    ];

    protected $casts = [
        'customer_id'  => 'integer',
        'project_id'   => 'integer',
        'booking_id'   => 'integer',
        'amount'       => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
