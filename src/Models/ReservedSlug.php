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
        self::HOMEPAGE => '/',
        self::SITEMAP => 'sitemap.xml',
    ];

    public static function find(string $slug): string
    {
        if (strlen($slug) > 1) {
            $shortenedSlug = Str::of($slug)->explode('/')->last();
            $shortenedSlug = Str::of($shortenedSlug)->explode('.')->first();

            $collection = collect(static::$lookup)->filter(function ($item) use ($shortenedSlug) {
                $shortenedItem = Str::of($item)->explode('.')->first();

                return (bool) preg_match(sprintf('|%s|', $shortenedItem), $shortenedSlug);
            });

            if ($collection->count() === 1) {
                return $collection->flip()->first();
            }
        }

        $flippedLookup = collect(static::$lookup)->flip();
        if ($flippedLookup->keys()->contains($slug)) {
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

    public static function toSlug(string $slug): string
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
