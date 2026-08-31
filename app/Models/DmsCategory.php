<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DmsCategory extends Model
{
    protected $table = 'dms_categories';

    protected $fillable = [
        'name',
        'code',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function documentTypes(): HasMany
    {
        return $this->hasMany(DmsDocumentType::class, 'dms_category_id');
    }
}
