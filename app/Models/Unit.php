<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Unit extends Model
{
    protected $fillable = [
        'project_id',
        'floor_id',
        'unit_type_id',
        'unit_number',
        'bua_area',
        'carpet_area',
        'area_unit',
        'facing',
        'status',
        'base_rate',
        'is_active',
    ];

    protected $casts = [
        'bua_area' => 'float',
        'carpet_area' => 'float',
        'base_rate' => 'float',
        'is_active' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }

    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class);
    }

    public function rateLogs(): HasMany
    {
        return $this->hasMany(UnitRateLog::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(UnitStatusLog::class);
    }

    public function latestRateLog(): HasOne
    {
        return $this->hasOne(UnitRateLog::class)->latestOfMany();
    }

    public function latestStatusLog(): HasOne
    {
        return $this->hasOne(UnitStatusLog::class)->latestOfMany();
    }
}
