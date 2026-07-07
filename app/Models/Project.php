<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSystemScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasSystemScope;

    protected $fillable = [
        'system_id',
        'name',
        'code',
        'location',
        'city',
        'state_or_emirate',
        'country',
        'rera_number',
        'total_floors',
        'start_date',
        'expected_completion_date',
        'status',
        'description',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'expected_completion_date' => 'date',
        'is_active' => 'boolean',
        'total_floors' => 'integer',
    ];

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    public function floors(): HasMany
    {
        return $this->hasMany(Floor::class)->orderBy('floor_number');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function partnerShares(): HasMany
    {
        return $this->hasMany(PartnerShare::class);
    }

    public function partnerAllocations(): HasMany
    {
        return $this->hasMany(PartnerAllocation::class);
    }
}
