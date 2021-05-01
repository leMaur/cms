<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

use Lemaur\Cms\Models\ReservedSlug;

trait HasAvailableParents
{
    public static function getAvailableParents(): array
    {
        // @TODO: cache it
        return static::select('slug')
            ->whereNull('parent')
            ->get()
            ->pluck('slug')
            ->map(fn ($slug) => ReservedSlug::toSlug($slug))
            ->all();
    }
}
