<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    protected $fillable = [
        'system_id',
        'name',
        'type',
        'description',
        'status',
    ];
}
