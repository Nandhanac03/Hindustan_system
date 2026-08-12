<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiptRealizationLog extends Model
{
    protected $table = 'receipt_realization_logs';

    protected $fillable = [
        'receipt_id',
        'old_status',
        'new_status',
        'remarks',
        'changed_by',
    ];

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class, 'receipt_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Human-readable label for the new status.
     */
    public function getNewStatusLabelAttribute(): string
    {
        return Receipt::STATUSES[$this->new_status] ?? ucfirst($this->new_status ?? '');
    }

    /**
     * Human-readable label for the old status.
     */
    public function getOldStatusLabelAttribute(): string
    {
        return Receipt::STATUSES[$this->old_status] ?? ucfirst($this->old_status ?? '—');
    }
}
