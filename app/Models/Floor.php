<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Floor extends Model
{
    protected $fillable = [
        'project_id',
        'floor_number',
        'name',
    ];

    protected $casts = [
        'floor_number' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public static function getDoorPrefix(int $floorNumber): string
    {
        if ($floorNumber < 0) {
            return 'B' . abs($floorNumber);
        }
        // Floors above 10 use the floor number itself as prefix (e.g., 14 → "14 A1")
        if ($floorNumber > 10) {
            return (string)$floorNumber;
        }
        return match ($floorNumber) {
            0 => 'G',
            1 => 'F',
            2 => 'S',
            3 => 'T',
            4 => 'FO',
            5 => 'FI',
            6 => 'SI',
            7 => 'SE',
            8 => 'E',
            9 => 'N',
            10 => 'TE',
            default => (string)$floorNumber,
        };
    }
}
