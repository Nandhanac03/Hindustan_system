<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSystemScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LedgerEntry extends Model
{
    use HasSystemScope;

    protected $fillable = [
        'system_id',
        'account_id',
        'voucher_id',
        'voucher_line_id',
        'date',
        'debit',
        'credit',
        'running_balance',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function voucherLine(): BelongsTo
    {
        return $this->belongsTo(VoucherLine::class);
    }
}
