<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
        'bank_name',
        'ifsc_code',
        'status',
    ];

    public function setIfscCodeAttribute($value)
    {
        $this->attributes['ifsc_code'] = strtoupper($value);
    }
}
