<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyBankAccount extends Model
{
    protected $table = 'company_bank_accounts';

    protected $fillable = [
        'bank_name',
        'account_name',
        'account_number',
        'account_type',
        'ifsc_code',
        'branch_name',
        'swift_code',
        'micr_code',
        'opening_balance',
        'current_balance',
        'upi_id',
        'status',
        'is_default',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_default'      => 'boolean',
    ];

    public function setIfscCodeAttribute($value): void
    {
        $this->attributes['ifsc_code'] = $value ? strtoupper(trim((string)$value)) : null;
    }

    public function setSwiftCodeAttribute($value): void
    {
        $this->attributes['swift_code'] = $value ? strtoupper(trim((string)$value)) : null;
    }

    public function getFormattedBalanceAttribute(): string
    {
        return '₹' . number_format((float) ($this->current_balance ?? 0), 2);
    }
}
