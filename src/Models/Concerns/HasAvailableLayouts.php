<?php

namespace Lemaur\Cms\Models\Concerns;

trait HasAvailableLayouts
{
    public static function getAvailableLayouts(): array
    {
        return static::distinct()
            ->select('extra_attributes->layout')
            ->whereNotNull('extra_attributes->layout')
            ->orderBy('extra_attributes->layout', 'asc')
            ->get()
            ->pluck('extra_attributes->layout')
            ->all();
}
}
