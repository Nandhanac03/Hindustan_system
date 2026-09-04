<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SiteExpense extends Model
{
    use HasFactory;

    protected $table = 'site_expenses';

    protected $fillable = [
        'system_id',
        'voucher_number',
        'project_id',
        'floor_id',
        'tower_block_tag',
        'voucher_date',
        'payee_type',
        'payee_id',
        'casual_payee_name',
        'chart_of_account_id',
        'expense_category_code',
        'expense_category_name',
        'gross_amount',
        'cgst_amount',
        'sgst_amount',
        'igst_amount',
        'total_gst_amount',
        'net_amount',
        'payment_source_type',
        'company_bank_account_id',
        'loan_id',
        'transaction_reference_no',
        'narration',
        'attachment_path',
        'created_by',
        'status',
    ];

    protected $casts = [
        'voucher_date'      => 'date',
        'gross_amount'      => 'decimal:2',
        'cgst_amount'       => 'decimal:2',
        'sgst_amount'       => 'decimal:2',
        'igst_amount'       => 'decimal:2',
        'total_gst_amount'  => 'decimal:2',
        'net_amount'        => 'decimal:2',
    ];

    /**
     * Get Project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get Floor / Block
     */
    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class, 'floor_id');
    }

    /**
     * Get Payee / Registered Vendor
     */
    public function payee(): BelongsTo
    {
        return $this->belongsTo(Payee::class, 'payee_id');
    }

    /**
     * Get Chart Of Account
     */
    public function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }

    /**
     * Get Company Bank Account
     */
    public function companyBankAccount(): BelongsTo
    {
        return $this->belongsTo(CompanyBankAccount::class, 'company_bank_account_id');
    }

    /**
     * Get Bank Loan Account
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    /**
     * Get Creator User
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get linked DMS Documents
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Accessor for Display Name of Payee
     */
    public function getPayeeDisplayNameAttribute(): string
    {
        if ($this->payee_type === 'registered' && $this->payee) {
            return $this->payee->name;
        }

        return $this->casual_payee_name ?? 'Casual Payee';
    }

    /**
     * Accessor for Payment Source Display Name
     */
    public function getPaymentSourceDisplayNameAttribute(): string
    {
        if ($this->payment_source_type === 'bank' && $this->companyBankAccount) {
            return $this->companyBankAccount->bank_name . ' - A/c ' . substr((string)($this->companyBankAccount->account_number ?? '1001'), -4);
        }

        if ($this->payment_source_type === 'loan' && $this->loan) {
            return $this->loan->lender_name . ' - A/c ' . substr((string)($this->loan->account_number ?? '2010'), -4);
        }

        return ucfirst($this->payment_source_type ?? 'Bank') . ' Account';
    }
}
