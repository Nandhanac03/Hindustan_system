<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSystemScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    public function unitTypes(): HasMany
    {
        return $this->hasMany(UnitType::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function siteExpenses(): HasMany
    {
        return $this->hasMany(SiteExpense::class, 'project_id');
    }

    public function getTotalFloorsAttribute(): int
    {
        return $this->floors()->count();
    }

    /**
     * Get the project display image URL with automatic multi-tier fallback.
     */
    public function getDisplayImageAttribute(): string
    {
        if (!empty($this->image_url)) {
            // 1. Direct remote URL
            if (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://')) {
                return $this->image_url;
            }

            // 2. Physical storage link check
            if (file_exists(public_path('storage/' . $this->image_url))) {
                return asset('storage/' . $this->image_url);
            }

            // 3. Storage path check
            if (file_exists(storage_path('app/public/' . $this->image_url))) {
                return asset('storage/' . $this->image_url);
            }

            // 4. Public direct path
            if (file_exists(public_path($this->image_url))) {
                return asset($this->image_url);
            }
        }

        // 5. Shipped default project asset fallback
        if (file_exists(public_path('img/default-project.jpg'))) {
            return asset('img/default-project.jpg');
        }

        // 6. Online architecture fallback
        return 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80';
    }
}
