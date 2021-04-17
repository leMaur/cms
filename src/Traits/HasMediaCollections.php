<?php

namespace Lemaur\Cms\Traits;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Spatie\MediaLibrary\InteractsWithMedia as SpatieInteractsWithMedia;

trait HasMediaCollections
{
    use SpatieInteractsWithMedia;

    public function registerMediaCollections(): void
    {
        if ($this->mediaCollections === []) {
            if (! property_exists($this, 'mediaConfiguration')) {
                throw new InvalidArgumentException('Property "mediaConfiguration" not defined.');
            }

            $config = config('cms.media', []);

            foreach ($this->mediaConfiguration as $name => $key) {
                $media = data_get($config, $key, []);

                $className = (string) Str::of(get_class($this))->lower()->explode('\\')->last();
                $onlyKeepLatest = data_get($media, 'only_keep_latest', null);

                $this
                    ->addMediaCollection(sprintf('%s.%s', $className, $name))
                    ->acceptsMimeTypes((array) data_get($media, 'accepts_mime_types', []))
                    ->when((bool) $onlyKeepLatest, function ($mediaCollection) use ($onlyKeepLatest) {
                        $mediaCollection->onlyKeepLatest((int) $onlyKeepLatest);
                    });
            }
        }
    }
}
