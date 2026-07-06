<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

class Approval extends Model
{
    protected $fillable = [
        'approvable_type',
        'approvable_id',
        'requested_by',
        'approved_by',
        'status',
        'reason',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function approvable(): MorphTo
    {
        return $this->morphTo();
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approve(User $user, ?string $reason = null): void
    {
        $this->update([
            'approved_by' => $user->id,
            'approved_at' => Carbon::now(),
            'status' => 'approved',
            'reason' => $reason,
        ]);
    }

    public function reject(User $user, ?string $reason = null): void
    {
        $this->update([
            'approved_by' => $user->id,
            'approved_at' => Carbon::now(),
            'status' => 'rejected',
            'reason' => $reason,
        ]);
    }
}
