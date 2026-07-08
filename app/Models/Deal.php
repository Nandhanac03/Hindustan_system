<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSystemScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deal extends Model
{
    use HasSystemScope;

    protected $fillable = [
        'system_id',
        'broker_id',
        'project_id',
        'booking_id',
        'sale_value',
        'commission_pct_override',
        'trigger_condition',
    ];

    protected $casts = [
        'sale_value' => 'float',
        'commission_pct_override' => 'float',
    ];

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function commissionEntries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CommissionEntry::class);
    }
}
