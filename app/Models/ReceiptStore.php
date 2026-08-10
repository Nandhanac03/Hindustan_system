<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiptStore extends Model
{
    protected $table = 'receipt_stores';

    protected $fillable = [
        'receipt_id',
        'company_bank_account_id',
        'customer_id',
        'project_id',
        'unit_id',
        'receipt_date',
        'amount',
        'payment_mode',
        'reference_no',
        'remarks',
        'status',
        'created_by',
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class, 'receipt_id');
    }

    public function companyBankAccount(): BelongsTo
    {
        return $this->belongsTo(CompanyBankAccount::class, 'company_bank_account_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
