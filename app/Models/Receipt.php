<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Receipt extends Model
{
    /**
     * Instruments that require explicit realization (do NOT credit bank balance on entry).
     */
    public const DEFERRED_MODES = ['CHEQUE', 'Cheque', 'DD', 'Demand Draft (DD)'];

    /**
     * Valid realization status values with human-readable labels.
     */
    public const STATUSES = [
        'pending'        => '⏳ Pending',
        'cheque_in_hand' => '🖐 Cheque in Hand',
        'deposited'      => '🏦 Deposited',
        'realized'       => '✅ Realized',
        'bounced'        => '❌ Bounced',
        'cancelled'      => '🚫 Cancelled',
    ];

    /**
     * Badge color classes for each status (Tailwind / custom CSS).
     */
    public const STATUS_COLORS = [
        'pending'        => 'warning',
        'cheque_in_hand' => 'info',
        'deposited'      => 'primary',
        'realized'       => 'success',
        'bounced'        => 'danger',
        'cancelled'      => 'secondary',
    ];

    protected $fillable = [
        'sale_id', 'customer_id', 'project_id', 'unit_id',
        'receipt_date', 'amount', 'payment_mode', 'reference_no', 'bank_id',
        'company_bank_account_id', 'is_allocated',
        'remarks', 'created_by', 'partner_id',
        // Realization fields
        'realization_status',
        'cheque_date',
        'drawee_bank',
        'realized_at',
        'realized_by',
    ];

    protected $casts = [
        'receipt_date'  => 'date',
        'cheque_date'   => 'date',
        'realized_at'   => 'datetime',
        'is_allocated'  => 'boolean',
    ];

    protected $appends = [
        'bank_name',
        'realization_status_label',
        'realization_status_color',
    ];

    // ─────────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────────

    public function companyBankAccount(): BelongsTo
    {
        return $this->belongsTo(CompanyBankAccount::class, 'company_bank_account_id');
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
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

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Payee::class, 'partner_id');
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function realizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'realized_by');
    }

    public function realizationLogs(): HasMany
    {
        return $this->hasMany(ReceiptRealizationLog::class, 'receipt_id')->latest();
    }

    // ─────────────────────────────────────────────
    // Computed Attributes
    // ─────────────────────────────────────────────

    public function getBankNameAttribute(): ?string
    {
        return $this->bank?->bank_name;
    }

    public function getRealizationStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->realization_status] ?? ucfirst($this->realization_status ?? 'pending');
    }

    public function getRealizationStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->realization_status] ?? 'secondary';
    }

    // ─────────────────────────────────────────────
    // Status Helper Methods
    // ─────────────────────────────────────────────

    /**
     * Whether this instrument is a cheque/DD that requires deferred realization.
     */
    public function isChequeInstrument(): bool
    {
        return in_array($this->payment_mode, self::DEFERRED_MODES, true);
    }

    /**
     * Whether the cheque/instrument has been bank-cleared and balance credited.
     */
    public function isRealized(): bool
    {
        return $this->realization_status === 'realized';
    }

    /**
     * Whether the instrument is still awaiting bank clearance.
     */
    public function isPending(): bool
    {
        return in_array($this->realization_status, ['pending', 'cheque_in_hand', 'deposited'], true);
    }

    /**
     * Whether the instrument is in a terminal (non-reversible) state.
     */
    public function isTerminal(): bool
    {
        return in_array($this->realization_status, ['realized', 'bounced', 'cancelled'], true);
    }

    // ─────────────────────────────────────────────
    // Business Logic — Realization
    // ─────────────────────────────────────────────

    /**
     * Mark this receipt as Realized. Credits the company bank account balance.
     *
     * @throws \Exception If already in a terminal state.
     */
    public function realize(int $userId, ?string $remarks = null): self
    {
        if ($this->isTerminal()) {
            throw new \Exception("Cannot realize a receipt that is already {$this->realization_status}.");
        }

        DB::transaction(function () use ($userId, $remarks) {
            $oldStatus = $this->realization_status;

            $this->update([
                'realization_status' => 'realized',
                'realized_at'        => now(),
                'realized_by'        => $userId,
            ]);

            // Credit the company bank account balance NOW
            if ($this->company_bank_account_id) {
                CompanyBankAccount::lockForUpdate()
                    ->findOrFail($this->company_bank_account_id)
                    ->increment('current_balance', $this->amount);
            }

            // Log the status change
            ReceiptRealizationLog::create([
                'receipt_id'  => $this->id,
                'old_status'  => $oldStatus,
                'new_status'  => 'realized',
                'remarks'     => $remarks ?? 'Cheque cleared by bank.',
                'changed_by'  => $userId,
            ]);
        });

        return $this->fresh();
    }

    /**
     * Mark this receipt as Bounced. Does NOT affect bank balance.
     *
     * @throws \Exception If already in a terminal state.
     */
    public function markBounced(int $userId, ?string $remarks = null): self
    {
        if ($this->isTerminal()) {
            throw new \Exception("Cannot mark as bounced — receipt is already {$this->realization_status}.");
        }

        DB::transaction(function () use ($userId, $remarks) {
            $oldStatus = $this->realization_status;

            $this->update([
                'realization_status' => 'bounced',
            ]);

            // Log the status change
            ReceiptRealizationLog::create([
                'receipt_id'  => $this->id,
                'old_status'  => $oldStatus,
                'new_status'  => 'bounced',
                'remarks'     => $remarks ?? 'Cheque dishonoured by bank.',
                'changed_by'  => $userId,
            ]);
        });

        return $this->fresh();
    }

    /**
     * Advance the status (e.g., pending → cheque_in_hand → deposited).
     */
    public function advanceStatus(string $newStatus, int $userId, ?string $remarks = null): self
    {
        $allowed = ['pending', 'cheque_in_hand', 'deposited', 'cancelled'];
        if (!in_array($newStatus, $allowed, true)) {
            throw new \InvalidArgumentException("Use realize() or markBounced() for terminal statuses.");
        }

        DB::transaction(function () use ($newStatus, $userId, $remarks) {
            $oldStatus = $this->realization_status;
            $this->update(['realization_status' => $newStatus]);

            ReceiptRealizationLog::create([
                'receipt_id'  => $this->id,
                'old_status'  => $oldStatus,
                'new_status'  => $newStatus,
                'remarks'     => $remarks,
                'changed_by'  => $userId,
            ]);
        });

        return $this->fresh();
    }

    // ─────────────────────────────────────────────
    // Static: Partner Allocation
    // ─────────────────────────────────────────────

    public static function allocateToPartners(self $receipt): void
    {
        if ($receipt->partner_id !== null) {
            return;
        }

        $shares = \App\Models\PartnerShare::where('project_id', $receipt->project_id)->get();
        foreach ($shares as $share) {
            $partnerAmount = $receipt->amount * ($share->share_pct / 100);

            self::create([
                'sale_id'            => $receipt->sale_id,
                'customer_id'        => $receipt->customer_id,
                'project_id'         => $receipt->project_id,
                'unit_id'            => $receipt->unit_id,
                'receipt_date'       => $receipt->receipt_date,
                'amount'             => $partnerAmount,
                'payment_mode'       => $receipt->payment_mode,
                'reference_no'       => $receipt->reference_no,
                'bank_id'            => $receipt->bank_id,
                'remarks'            => "Share of collection ({$share->share_pct}%) from receipt #{$receipt->id}",
                'created_by'         => $receipt->created_by,
                'partner_id'         => $share->partner_id,
                'realization_status' => $receipt->realization_status,
            ]);
        }
    }
}