<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

use Illuminate\Support\Facades\Cache;

trait HasAvailableTypes
{
    public static function getAvailableTypes(): array
    {
        $cacheKey = cacheKeyGenerator('page', 'types');

        return Cache::rememberForever(
            $cacheKey,
            fn () =>
            static::query()
                ->distinct()
                ->select('type')
                ->orderBy('type', 'asc')
                ->get()
                ->pluck('type')
                ->toArray()
        );
    }
}
