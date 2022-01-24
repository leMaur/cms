<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

use Illuminate\Support\Facades\Cache;
use Lemaur\Cms\Models\ReservedSlug;

trait HasAvailableParents
{
    public static function getAvailableParents(): array
    {
        $cacheKey = cacheKeyGenerator('page', 'parents');

        return Cache::tags(['cms', 'page'])->rememberForever(
            $cacheKey,
            fn () =>
            static::query()
                ->select('slug')
                ->whereNull('parent')
                ->get()
                ->pluck('slug')
                ->map(fn ($slug) => ReservedSlug::toSlug($slug))
                ->toArray()
        );
    }
}
