<?php

namespace Lemaur\Cms\Traits;

use Spatie\MediaLibrary\InteractsWithMedia as SpatieInteractsWithMedia;

trait InteractsWithMedia
{
    use SpatieInteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(get_class($this));
    }
}
