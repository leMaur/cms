<?php

namespace Lemaur\Cms\Models\Concerns;

trait HasAvailableLayouts
{
    public static function getAvailableLayouts(): array
    {
        // @TODO: cache it
        return static::distinct()
            ->select('layout')
            ->orderBy('layout', 'asc')
            ->get()
            ->pluck('layout')
            ->all();
    }
}
