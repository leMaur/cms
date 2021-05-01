<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

trait HasAvailableLayouts
{
    public static function getAvailableLayouts(): array
    {
        // @TODO: cache it
        return static::select('layout')
            ->get()
            ->pluck('layout')
            ->unique()
            ->values()
            ->all();
    }
}
