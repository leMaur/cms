<?php

namespace Lemaur\Cms\Models;

use Illuminate\Support\Str;
use ReflectionClass;

class ReservedSlug
{
    public const HOMEPAGE = '@home';
    public const SITEMAP = '@sitemap';

    public static function list(): array
    {
        $reflector = new ReflectionClass(static::class);

        return collect($reflector->getConstants())
            ->mapWithKeys(fn ($value, $key) => [Str::lower($key) => $value])
            ->all();
    }
}
