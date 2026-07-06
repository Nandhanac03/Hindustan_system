<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Scopes\SystemScope;

trait HasSystemScope
{
    public static function bootHasSystemScope(): void
    {
        static::addGlobalScope(new SystemScope());
    }
}
