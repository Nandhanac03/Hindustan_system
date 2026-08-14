<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChequeStatus extends Model
{
    protected $fillable = ['name', 'color_code', 'is_active'];
}
