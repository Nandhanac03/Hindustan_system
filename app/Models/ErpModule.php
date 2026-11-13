<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ErpModule extends Model
{
    use HasFactory;

    protected $table = 'erp_modules';

    protected $fillable = [
        'name',
        'code',
        'section',
        'icon',
        'description',
        'order',
        'status',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function subModules(): HasMany
    {
        return $this->hasMany(ErpSubModule::class, 'erp_module_id', 'id')->orderBy('order', 'asc')->orderBy('id', 'asc');
    }
}

