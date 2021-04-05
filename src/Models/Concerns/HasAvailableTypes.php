<?php

namespace Lemaur\Cms\Models\Concerns;

trait HasAvailableTypes
{
    public static function getAvailableTypes(): array
    {
        return static::distinct()
            ->select(['type'])
            ->orderBy('type', 'asc')
            ->get()
            ->pluck('type')
            ->all();
    }
}
