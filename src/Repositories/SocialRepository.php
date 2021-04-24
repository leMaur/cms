<?php

namespace Lemaur\Cms\Repositories;

use Illuminate\Support\Facades\DB;
use Lemaur\Cms\Models\Navigation;
use Lemaur\Cms\Repositories\Contracts\Findable;

class SocialRepository implements Findable
{
    public function __construct(private Navigation $navigation)
    {}

    public function find(?string $slug = null): Navigation
    {
        // @TODO: cache it
        return $this->navigation
            ->withType(Navigation::SOCIAL)
            ->where(DB::raw('LOWER(name)'), $slug)
            ->firstOrFail();
    }
}
