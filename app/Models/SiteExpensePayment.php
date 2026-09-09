<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteExpensePayment extends Model
{
    use HasFactory;

    protected $table = 'site_expense_payments';

    protected $fillable = [
        'system_id',
        'site_expense_id',
        'payment_date',
        'paid_amount',
        'payment_mode',
        'payment_source_type',
        'company_bank_account_id',
        'loan_id',
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

    public function siteExpense(): BelongsTo
    {
        return $this->belongsTo(SiteExpense::class, 'site_expense_id');
    }

    public function companyBankAccount(): BelongsTo
    {
        return $this->belongsTo(CompanyBankAccount::class, 'company_bank_account_id');
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
