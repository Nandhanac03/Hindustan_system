<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RaBill extends Model
{
    protected $table = 'ra_bills';

    protected $fillable = [
        'system_id',
        'ra_bill_number',
        'contractor_id',
        'contractor_name',
        'project_id',
        'unit_id',
        'unit_name',
        'submit_date',
        'gross_amount',
        'additional_amount',
        'verified_date',
        'engineer_name',
        'correction_amount',
        'net_approved_amount',
        'due_date',
        'paid_amount',
        'balance_amount',
        'status',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'submit_date'         => 'date',
        'verified_date'       => 'date',
        'due_date'            => 'date',
        'gross_amount'        => 'decimal:2',
        'correction_amount'   => 'decimal:2',
        'net_approved_amount' => 'decimal:2',
        'paid_amount'         => 'decimal:2',
        'balance_amount'      => 'decimal:2',
    ];

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Payee::class, 'contractor_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(RaBillPayment::class, 'ra_bill_id')->orderBy('payment_date', 'asc');
    }

    public function recalculateBalances(): void
    {
        $totalPaid = $this->payments()->sum('paid_amount');
        $netApproved = (float) $this->net_approved_amount;
        $balance = max(0, $netApproved - $totalPaid);

        $status = 'pending';
        if ($totalPaid >= $netApproved && $netApproved > 0) {
            $status = 'cleared';
        } elseif ($totalPaid > 0) {
            $status = 'partially_paid';
        } elseif ($this->verified_date) {
            $status = 'pending';
        }

        $this->update([
            'paid_amount'    => $totalPaid,
            'balance_amount' => $balance,
            'status'         => $status,
        ]);
    }
}
