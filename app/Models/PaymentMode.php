<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMode extends Model
{
    use HasFactory;

    protected $table = 'payment_modes';

    protected $fillable = [
        'system_id',
        'name',
        'code',
        'description',
        'requires_reference',
        'requires_bank',
        'status',
    ];

    protected $casts = [
        'requires_reference' => 'boolean',
        'requires_bank' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
