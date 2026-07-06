<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSystemScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerShare extends Model
{
    use HasSystemScope;

    protected $fillable = [
        'system_id',
        'partner_id',
        'project_id',
        'share_pct',
    ];

    protected $casts = [
        'share_pct' => 'float',
    ];

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Payee::class, 'partner_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
