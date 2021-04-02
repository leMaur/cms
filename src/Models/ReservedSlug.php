<?php

namespace Lemaur\Cms\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;

class ReservedSlug
{
    public const HOMEPAGE = '@home';
    public const SITEMAP = '@sitemap';

    private static array $lookup = [
        '@home' => '/',
        '@sitemap' => 'sitemap.xml',
    ];

    public static function list(): Collection
    {
        $reflector = new ReflectionClass(static::class);

        return collect($reflector->getConstants())
            ->mapWithKeys(fn ($value, $key) => [$value => Str::lower($key)])
            ->toBase();
    }

    public static function handle(string $slug): string
    {
        $reflector = new ReflectionClass(static::class);

        if (collect($reflector->getConstants())->values()->containsStrict($slug)) {
            return static::$lookup[$slug];
        }

        return $slug;
    }
}
