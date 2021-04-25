<?php

namespace Lemaur\Cms\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;
use Lemaur\Cms\Models\ReservedSlug;
use Spatie\Sluggable\HasSlug as SpatieHasSlug;
use Spatie\Sluggable\SlugOptions;

trait HasSlug
{
    use SpatieHasSlug;

    public function getSlugOptions(): SlugOptions
    {
        if (! property_exists($this, 'slugFrom')) {
            throw new InvalidArgumentException('Property "slugFrom" not defined.');
        }

        return SlugOptions::create()
            ->generateSlugsFrom($this->slugFrom)
            ->saveSlugsTo('slug');
    }

    public function scopeWithSlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', ReservedSlug::toReserved($slug));
    }

    public function scopeWithoutSlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', '!=', ReservedSlug::toReserved($slug));
    }
}
