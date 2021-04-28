<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;

class ReservedSlug
{
    public const HOMEPAGE = '@home';
    public const SITEMAP = '@sitemap';

    private static array $slugs = [
        self::HOMEPAGE => '/',
        self::SITEMAP => 'sitemap.xml',
    ];

    public static function toReserved(string $slug): string
    {
        // @TODO: cache it?
        if (strlen($slug) > 1) {
            $shortenedSlug = Str::of($slug)->explode('/')->last();
            $shortenedSlug = Str::of($shortenedSlug)->explode('.')->first(); // ignore extension

            $collection = collect(static::$slugs)->filter(function ($item) use ($shortenedSlug) {
                $shortenedItem = Str::of($item)->explode('.')->first(); // ignore extension

                return (bool) preg_match(sprintf('|%s|', $shortenedItem), $shortenedSlug);
            });

            if ($collection->count() === 1) {
                return $collection->flip()->first();
            }
        }

        $flippedLookup = collect(static::$slugs)->flip();
        if ($flippedLookup->keys()->contains($slug)) {
            return $flippedLookup->get($slug);
        }

        return $slug;
    }

    public static function toSlug(string $slug): string
    {
        // @TODO: cache it?
        $reflector = new ReflectionClass(static::class);

        if (collect($reflector->getConstants())->values()->containsStrict($slug)) {
            return static::$slugs[$slug];
        }

        return $slug;
    }

    public static function list(): Collection
    {
        // @TODO: cache it?
        $reflector = new ReflectionClass(static::class);

        return collect($reflector->getConstants())
            ->mapWithKeys(fn ($value, $key) => [$value => Str::lower($key)])
            ->toBase();
    }

    public static function isReserved(string $slug): bool
    {
        // @TODO: cache it?
        return collect(static::$slugs)->keys()->containsStrict($slug);
    }
}
