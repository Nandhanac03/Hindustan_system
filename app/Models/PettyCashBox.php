<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashBox extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'box_code',
        'box_name',
        'incharge_id',
        'current_balance',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function incharge()
    {
        return $this->belongsTo(User::class, 'incharge_id');
    }

    public function transactions()
    {
        return $this->hasMany(PettyCashTransaction::class);
    }
}
