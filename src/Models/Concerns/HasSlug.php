<?php

namespace Lemaur\Cms\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Lemaur\Cms\Models\ReservedSlug;
use Spatie\Sluggable\HasSlug as SpatieHasSlug;
use Spatie\Sluggable\SlugOptions;

trait HasSlug
{
    use SpatieHasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function scopeWithSlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', ReservedSlug::find($slug));
    }

    public function scopeWithoutSlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', '!=', ReservedSlug::find($slug));
    }
}
