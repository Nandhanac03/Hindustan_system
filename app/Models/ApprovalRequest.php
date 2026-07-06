<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'priority', 'status',
        'title', 'description', 'metadata', 'requester_name',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
