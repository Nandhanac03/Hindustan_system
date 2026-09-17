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
        'entity_type',
        'entity_id',
    ];

    /**
     * Voucher relation
     */
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(JournalVoucher::class, 'voucher_id');
    }

    /**
     * Chart of account relation
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id', 'account_code');
    }

    /**
     * Customer entity relation
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'entity_id');
    }

    /**
     * Supplier entity relation
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Payee::class, 'entity_id');
    }

    /**
     * Contractor entity relation
     */
    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Payee::class, 'entity_id');
    }

    /**
     * Bank account entity relation
     */
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(CompanyBankAccount::class, 'entity_id');
    }

    /**
     * Petty cash box entity relation
     */
    public function pettyCashBox(): BelongsTo
    {
        return $this->belongsTo(PettyCashBox::class, 'entity_id');
    }

    /**
     * Agent / Broker entity relation
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Broker::class, 'entity_id');
    }

    /**
     * Partner entity relation (Payee of type Partner)
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Payee::class, 'entity_id');
    }

    /**
     * Dynamic entity accessor helper ($journalEntry->entity)
     */
    public function getEntityAttribute()
    {
        if (!$this->entity_type || !$this->entity_id) {
            return null;
        }

        return match (strtoupper($this->entity_type)) {
            'CUSTOMER'       => Customer::find($this->entity_id),
            'SUPPLIER', 
            'CONTRACTOR',
            'PARTNER'        => Payee::find($this->entity_id) ?? Vendor::find($this->entity_id),
            'BANK'           => CompanyBankAccount::find($this->entity_id) ?? Bank::find($this->entity_id),
            'PETTY_CASH',
            'PETTY_CASH_BOX' => PettyCashBox::find($this->entity_id),
            'AGENT'          => Broker::find($this->entity_id),
            default          => null,
        };
    }
}
