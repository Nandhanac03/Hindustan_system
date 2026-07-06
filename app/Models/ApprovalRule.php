<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalRule extends Model
{
    protected $fillable = [
        'module',
        'min_role',
        'threshold_amount',
        'is_active',
    ];

    protected $casts = [
        'threshold_amount' => 'float',
        'is_active' => 'boolean',
    ];

    public function appliesTo(?float $amount): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->threshold_amount === null) {
            return true;
        }

        if ($amount === null) {
            return false;
        }

        return $amount >= $this->threshold_amount;
    }
}
