<?php

declare(strict_types=1);

use Lemaur\Cms\Support\Services\CacheStore;

if (! function_exists('cacheStore')) {
    function cacheStore(string $key): CacheStore {
        return new CacheStore(
            key: $key
        );
    }
}
