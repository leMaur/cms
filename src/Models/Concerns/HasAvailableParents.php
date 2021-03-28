<?php

namespace Lemaur\Cms\Models\Concerns;

trait HasAvailableParents
{
    public static function getAvailableParents(): array
    {
        return static::select(['id', 'slug'])
            ->whereNull('parent')
            ->orderBy('slug', 'asc')
            ->get()
            ->pluck('slug')
            ->all();
    }
}
