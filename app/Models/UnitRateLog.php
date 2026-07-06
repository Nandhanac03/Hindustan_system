<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitRateLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'unit_id',
        'rate',
        'effective_from',
        'changed_by',
        'reason',
    ];

    protected $casts = [
        'rate' => 'float',
        'effective_from' => 'date',
    ];

    protected $appends = [
        'previous_rate',
    ];

    public function getPreviousRateAttribute(): float
    {
        $prev = self::where('unit_id', $this->unit_id)
            ->where('id', '<', $this->id)
            ->orderBy('id', 'desc')
            ->first();

        return $prev ? (float)$prev->rate : 0.0;
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now();
        });

        static::updating(function ($model) {
            throw new \Exception('UnitRateLog records are append-only and cannot be updated.');
        });

        static::deleting(function ($model) {
            throw new \Exception('UnitRateLog records are append-only and cannot be deleted.');
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
