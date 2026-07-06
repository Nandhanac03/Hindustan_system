<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSystemScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    use HasSystemScope;

    protected $fillable = [
        'system_id',
        'code',
        'name',
        'type',
        'parent_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }
}
