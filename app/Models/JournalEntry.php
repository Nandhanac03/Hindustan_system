<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntry extends Model
{
    protected $fillable = [
        'voucher_id',
        'account_id',
        'debit_amount',
        'credit_amount',
        'line_narration',
    ];

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(JournalVoucher::class, 'voucher_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id', 'account_code');
    }
}
