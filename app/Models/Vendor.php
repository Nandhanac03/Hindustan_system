<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSystemScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasSystemScope;

    protected $table = 'vendors';

    protected $fillable = [
        'system_id',
        'vendor_code',
        'name',
        'contact_person',
        'phone',
        'email',
        'gstin',
        'pan',
        'address',
        'bank_name',
        'account_number',
        'ifsc_code',
        'branch',
        'linked_account_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    public function linkedAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'linked_account_id');
    }

    public function siteExpenses(): HasMany
    {
        return $this->hasMany(SiteExpense::class, 'vendor_id');
    }
}
