<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalVoucher extends Model
{
    protected $fillable = [
        'voucher_no',
        'voucher_type_id',
        'voucher_date',
        'reference_id',
        'narration',
        'is_active',
    ];

    public function voucherType(): BelongsTo
    {
        return $this->belongsTo(VoucherType::class, 'voucher_type_id');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(JournalEntry::class, 'voucher_id');
    }
}
