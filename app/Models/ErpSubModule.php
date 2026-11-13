<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ErpSubModule extends Model
{
    use HasFactory;

    protected $table = 'erp_sub_modules';

    protected $fillable = [
        'erp_module_id',
        'name',
        'code',
        'route_name',
        'actions',
        'description',
        'order',
        'status',
    ];

    protected $casts = [
        'erp_module_id' => 'integer',
        'order' => 'integer',
        'actions' => 'array',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(ErpModule::class, 'erp_module_id', 'id');
    }
}

