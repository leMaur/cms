<?php

namespace Lemaur\Cms\Models\Concerns;

trait HasAvailableParents
{
    public static function getAvailableParents(): array
    {
        // @TODO: cache it
        return static::distinct()
            ->select('slug')
            ->whereNull('parent')
            ->orderBy('slug', 'asc')
            ->get()
            ->pluck('slug')
            ->all();
    }
}
