<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSystemScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerAllocation extends Model
{
    use HasSystemScope;

    protected $fillable = [
        'system_id',
        'partner_id',
        'project_id',
        'payment_id',
        'allocated_amount',
        'date',
        'voucher_id',
    ];

    protected $casts = [
        'allocated_amount' => 'float',
        'date' => 'date',
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

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }
}
