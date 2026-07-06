<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSystemScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payee extends Model
{
    use HasSystemScope;

    protected $fillable = [
        'system_id',
        'type',
        'name',
        'linked_account_id',
    ];

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    public function linkedAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'linked_account_id');
    }

    public function partnerShares(): HasMany
    {
        return $this->hasMany(PartnerShare::class, 'partner_id');
    }

    public function partnerAllocations(): HasMany
    {
        return $this->hasMany(PartnerAllocation::class, 'partner_id');
    }
}
