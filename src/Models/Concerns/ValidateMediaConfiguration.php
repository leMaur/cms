<?php

namespace Lemaur\Cms\Models\Concerns;

trait ValidateMediaConfiguration
{
    private function validateMediaConfiguration(array $config): bool
    {
        $acceptsKeys = [
            'name',
            'allowed_mime_types',
            'single_file',
            'only_keep_latest',
            'disk',
        ];

        return collect($config)->keys()->containsStrict($acceptsKeys);
    }
}
