<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitStatusLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'unit_id',
        'from_status',
        'to_status',
        'changed_by',
        'reason',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now();
        });

        static::updating(function ($model) {
            throw new \Exception('UnitStatusLog records are append-only and cannot be updated.');
        });

        static::deleting(function ($model) {
            throw new \Exception('UnitStatusLog records are append-only and cannot be deleted.');
        });
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
