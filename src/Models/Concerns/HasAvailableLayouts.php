<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

trait HasAvailableLayouts
{
    public static function getAvailableLayouts(): array
    {
        // @TODO: cache it
        return static::distinct()
            ->select('layout', 'order_column')
            ->get()
            ->pluck('layout')
            ->all();
    }
}
