<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSystemScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Broker extends Model
{
    use HasSystemScope;

    protected $fillable = [
        'system_id',
        'name',
        'default_commission_pct',
        'linked_account_id',
    ];

    protected $casts = [
        'default_commission_pct' => 'float',
    ];

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    public function linkedAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'linked_account_id');
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }
}
