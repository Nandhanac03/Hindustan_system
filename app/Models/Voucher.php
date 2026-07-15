<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSystemScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    use HasSystemScope;

    protected $fillable = [
        'system_id',
        'voucher_number',
        'type', // Receipt, Payment, Contra, Journal, Sales, Purchase
        'date',
        'narration',
        'reference_no',
        'created_by',
        'edited_by',
        'status', // Draft, Posted, Cancelled
        'reversal_of_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function lines(): HasMany
    {
        return $this->hasMany(VoucherLine::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }
}
