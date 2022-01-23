<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Lemaur\Cms\Support\Services\CacheStore;

if (! function_exists('cacheStore')) {
    function cacheStore(string $key): mixed {
        return new CacheStore(
            key: $key
        );
    }
}

if (! function_exists('cacheKeyGenerator')) {
    function cacheKeyGenerator(mixed ...$key): string {
        return collect($key)
            ->map(fn ($value) => (string) Str::of($value)->kebab()->lower())
            ->filter()
            ->join('.');
    }
}
