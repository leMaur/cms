<?php

namespace Lemaur\Cms\Traits;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\InteractsWithMedia as SpatieInteractsWithMedia;

trait HasSingleImage
{
    use SpatieInteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $media = (array) config('cms.media.single_image', []);

        $onlyKeepLatest = data_get($media, 'only_keep_latest', null);
        $className = (string) Str::of(get_class($this))->lower()->explode('\\')->last();

        $this
            ->addMediaCollection(vsprintf('%s.%s', [$className, data_get($media, 'name')]))
            ->acceptsMimeTypes((array) data_get($media, 'accepts_mime_types', []))
            ->when((bool) $onlyKeepLatest, function ($mediaCollection) use ($onlyKeepLatest) {
                $mediaCollection->onlyKeepLatest((int) $onlyKeepLatest);
            });
    }
}
