<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

trait HasAvailableParents
{
    public static function getAvailableParents(): array
    {
        // @TODO: cache it
        return static::distinct()
            ->select('slug', 'order_column')
            ->whereNull('parent')
            ->get()
            ->pluck('slug')
            ->all();
    }
}
