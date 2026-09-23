<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteExpenseCategory extends Model
{
    use HasFactory;

    protected $table = 'site_expense_categories';

    protected $fillable = [
        'category_code',
        'category_name',
        'chart_of_account_id',
        'project_id',
        'description',
        'status',
    ];

    /**
     * Relationship: SiteExpenseCategory belongs to a parent Chart of Account (COA).
     */
    public function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }

    /**
     * Relationship: SiteExpenseCategory belongs to a Project (optional).
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Accessor for category attribute to ensure compatibility with generic category syntax.
     */
    public function getCategoryAttribute(): ?string
    {
        return $this->category_name;
    }

    /**
     * Mutator for category attribute.
     */
    public function setCategoryAttribute(?string $value): void
    {
        $this->attributes['category_name'] = $value;
    }

    /**
     * Accessor for name attribute.
     */
    public function getNameAttribute(): ?string
    {
        return $this->category_name;
    }

    /**
     * Accessor for code attribute.
     */
    public function getCodeAttribute(): ?string
    {
        return $this->category_code;
    }
}
