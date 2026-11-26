<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficeRent extends Model
{
    protected $fillable = [
        'system_id',
        'office_location',
        'landlord_name',
        'rent_month',
        'rent_year',
        'rent_period_label',
        'gross_rent_amount',
        'tds_amount',
        'net_payable_amount',
        'payment_status',
        'company_bank_account_id',
        'payment_reference',
        'voucher_date',
        'payment_date',
        'journal_voucher_id',
        'payment_voucher_id',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'gross_rent_amount' => 'decimal:2',
        'tds_amount' => 'decimal:2',
        'net_payable_amount' => 'decimal:2',
        'voucher_date' => 'date',
        'payment_date' => 'date',
    ];

    public function companyBankAccount(): BelongsTo
    {
        return $this->belongsTo(CompanyBankAccount::class, 'company_bank_account_id');
    }

    public function journalVoucher(): BelongsTo
    {
        return $this->belongsTo(JournalVoucher::class, 'journal_voucher_id');
    }

    public function paymentVoucher(): BelongsTo
    {
        return $this->belongsTo(JournalVoucher::class, 'payment_voucher_id');
    }
}
