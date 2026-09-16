<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalVoucher extends Model
{
    protected $table = 'journal_vouchers';

    protected $fillable = [
        'voucher_no',
        'voucher_type_id',
        'voucher_date',
        'reference_id',
        'reference_no',
        'narration',
        'status',
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

    public function getTotalDebitAttribute(): float
    {
        return (float) $this->entries->sum('debit_amount');
    }

    public function getTotalCreditAttribute(): float
    {
        return (float) $this->entries->sum('credit_amount');
    }
}
