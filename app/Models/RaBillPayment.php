<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaBillPayment extends Model
{
    protected $table = 'ra_bill_payments';

    protected $fillable = [
        'system_id',
        'ra_bill_id',
        'payment_date',
        'paid_amount',
        'payment_mode',
        'company_bank_account_id',
        'reference_no',
        'voucher_id',
        'status',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'paid_amount'  => 'decimal:2',
    ];

    public function raBill(): BelongsTo
    {
        return $this->belongsTo(RaBill::class, 'ra_bill_id');
    }

    public function companyBankAccount(): BelongsTo
    {
        return $this->belongsTo(CompanyBankAccount::class, 'company_bank_account_id');
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }
}
