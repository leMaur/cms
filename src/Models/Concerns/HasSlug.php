<?php

declare(strict_types=1);

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
            ->generateSlugsFrom($this->slugFrom)
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    protected function generateSlugOnUpdate(): void
    {
        $this->slugOptions = $this->getSlugOptions();

        if (! $this->slugOptions->generateSlugsOnUpdate) {
            return;
        }

        if ($this->slugOptions->preventOverwrite) {
            if ($this->{$this->slugOptions->slugField} !== null) {
                return;
            }
        }

        if (ReservedSlug::isReserved($this->slug)) {
            return;
        }

        $this->addSlug();
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
