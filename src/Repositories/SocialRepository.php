<?php

declare(strict_types=1);

namespace Lemaur\Cms\Repositories;

use Illuminate\Support\Facades\Cache;
use Lemaur\Cms\Models\Navigation;
use Lemaur\Cms\Repositories\Contracts\Findable;

class SocialRepository implements Findable
{
    public function find(?string $slug = null): Navigation
    {
        $cacheKey = cacheKeyGenerator('navigation', 'social', $slug);

        return Cache::rememberForever($cacheKey, fn () =>
            Navigation::query()
                ->withType(Navigation::SOCIAL)
                ->where('slug', $slug)
                ->firstOrFail()
        );
    }
}
