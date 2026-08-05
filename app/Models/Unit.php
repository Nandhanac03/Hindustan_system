<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'hindustan_units';

    protected $fillable = [
        'project_id',
        'floor_id',
        'unit_type_id',
        'door_no',
        'unit_number',
        'built_up_area',
        'carpet_area',
        'expected_rate_per_sqft',
        'expected_sale_amount',
        'sale_rate_per_sqft',
        'sale_amount',
        'difference',
        'gst_behavior',
        'gst_amount',
        'status',
        'is_active',
    ];

    protected $casts = [
        'built_up_area'          => 'decimal:2',
        'carpet_area'            => 'decimal:2',
        'expected_rate_per_sqft' => 'decimal:2',
        'expected_sale_amount'   => 'decimal:2',
        'sale_rate_per_sqft'     => 'decimal:2',
        'sale_amount'            => 'decimal:2',
        'difference'             => 'decimal:2',
        'gst_amount'             => 'decimal:2',
        'is_active'              => 'boolean',
    ];

    protected $appends = [
        'unit_number',
    ];

    public function getUnitNumberAttribute(): string
    {
        return (string) ($this->door_no ?? '');
    }

    public function getFormattedNameAttribute(): string
    {
        $door = trim(explode(',', $this->door_no ?? '')[0]);
        if (!$door) {
            return '';
        }

        $type = '';
        if ($this->unitType && $this->unitType->name) {
            $tName = strtolower($this->unitType->name);
            if ($tName === 'flat') {
                $type = 'Apartment';
            } elseif (str_contains($tName, 'parking')) {
                $type = 'Parking';
            } else {
                $type = ucfirst($tName);
            }
        }

        $floor = '';
        if ($this->floor && $this->floor->name) {
            $fName = trim($this->floor->name);
            if (preg_match('/^(floor|fl)\b/i', $fName)) {
                $floor = preg_replace('/^(floor|fl)\b/i', 'Floor', $fName);
            } elseif (is_numeric($fName)) {
                $floor = 'Floor ' . $fName;
            } else {
                $floor = ucfirst($fName);
            }
        }

        $typeStr = $type ? "({$type})" : '';
        $floorStr = $floor ? " - {$floor}" : '';
        return "{$door}{$typeStr}{$floorStr}";
    }

    public function setUnitNumberAttribute($value): void
    {
        $this->attributes['door_no'] = $value;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function unitType()
    {
        return $this->belongsTo(UnitType::class);
    }

    public function rateLogs(): HasMany
    {
        return $this->hasMany(UnitRateLog::class)->orderBy('id', 'desc');
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

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class)->latestOfMany();
    }

    public function saleUnits(): HasMany
    {
        return $this->hasMany(SaleUnit::class, 'unit_id');
    }

    public function sale(): HasOne
    {
        return $this->hasOne(Sale::class, 'unit_id')->where('status', 'active');
    }
}