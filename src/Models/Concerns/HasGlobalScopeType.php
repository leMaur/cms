<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

use Illuminate\Support\Str;
use Lemaur\Cms\Models\Scopes\TypeScope;

trait HasGlobalScopeType
{
    public static function bootHasGlobalScopeType(): void
    {
        static::addGlobalScope(new TypeScope());
    }

    public static function getType(): string
    {
        return Str::snake(class_basename(static::class));
    }
}
