<?php

namespace Lemaur\Cms\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;

class ReservedSlug
{
    public const HOMEPAGE = '@home';
    public const SITEMAP = '@sitemap';

    public static function list(): Collection
    {
        $reflector = new ReflectionClass(static::class);

        return collect($reflector->getConstants())
            ->mapWithKeys(fn ($value, $key) => [$value => Str::lower($key)])
            ->toBase();
    }
}
