<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

use Illuminate\Support\Facades\Cache;

trait HasAvailableLayouts
{
    public static function getAvailableLayouts(): array
    {
        $cacheKey = cacheKeyGenerator('page.layouts');

        return Cache::rememberForever($cacheKey, fn () =>
            static::query()
                ->select('layout')
                ->get()
                ->pluck('layout')
                ->unique()
                ->values()
                ->toArray()
        );
    }
}
