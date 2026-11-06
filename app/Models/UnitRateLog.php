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
        'revision_type',
        'change_details',
        'amount_change',
        'effective_from',
        'changed_by',
        'reason',
    ];

    protected $casts = [
        'rate' => 'float',
        'amount_change' => 'float',
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

        if ($prev) {
            return (float)$prev->rate;
        }

        // If no prior log exists, deduce the previous rate from amount_change if available
        if ($this->amount_change != 0 && $this->unit) {
            $isParking = $this->unit->unitType && (strtolower($this->unit->unitType->name) === 'parking' || strtolower($this->unit->unitType->category) === 'parking');
            if ($isParking) {
                $oldPrice = (float)$this->rate - (float)$this->amount_change;
                return $oldPrice > 0 ? (float)$oldPrice : 0.0;
            }

            if ((float)($this->unit->built_up_area ?? 0) > 0) {
                $rateDiff = (float)$this->amount_change / (float)$this->unit->built_up_area;
                $oldRate = (float)$this->rate - $rateDiff;
                return $oldRate > 0 ? (float)$oldRate : 0.0;
            }
        }

        return 0.0;
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
