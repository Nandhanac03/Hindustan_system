<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class System extends Model
{
    protected $fillable = [
        'name',
        'code',
        'country',
        'currency_code',
        'gst_enabled',
        'vat_enabled',
        'timezone',
        'is_active',
    ];

    protected $casts = [
        'gst_enabled' => 'boolean',
        'vat_enabled' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
