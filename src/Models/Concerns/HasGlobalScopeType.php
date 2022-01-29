<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

use Lemaur\Cms\Models\Scopes\TypeScope;

trait HasGlobalScopeType
{
    public static function bootHasGlobalScopeType(): void
    {
        static::addGlobalScope(new TypeScope());
    }
}
