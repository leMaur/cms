<?php

namespace Lemaur\Cms\Repositories;

use Lemaur\Cms\Models\Navigation;
use Lemaur\Cms\Repositories\Contracts\Findable;

class SocialRepository implements Findable
{
    public function find(?string $slug = null): Navigation
    {
        // @TODO: cache it
        return Navigation::query()
            ->withType(Navigation::SOCIAL)
            ->where('slug', $slug)
            ->firstOrFail();
    }
}
