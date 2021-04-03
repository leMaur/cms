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

    public static function find(string $slug): string
    {
        if (strlen($slug) > 1) {
            $slug = trim($slug, '/');
        }

        $flippedLookup = collect(static::$lookup)->flip();

        if ($flippedLookup->keys()->containsStrict($slug)) {
            return $flippedLookup->get($slug);
        }

        return $slug;
    }

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

    public static function isReserved(string $slug): bool
    {
        return collect(static::$lookup)->keys()->containsStrict($slug);
    }
}
