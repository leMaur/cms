<?php

namespace Lemaur\Cms\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia as SpatieInteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasMediaCollections
{
    use SpatieInteractsWithMedia;

    public function registerMediaConversions(Media $media = null): void
    {
        if ($this->mediaConversions === []) {
            $config = config('cms.media_conversions', []);

            foreach ($this->mediaConversionConfiguration as $name => $key) {
                foreach(Arr::wrap($key) as $configKey) {
                    $mediaConfig = data_get($config, $configKey, []);

                    $this
                        ->addMediaConversion($configKey)
                        ->fit(Manipulations::FIT_CROP, (int) data_get($mediaConfig, 'width'), (int) data_get($mediaConfig, 'height'))
                        ->performOnCollections($this->getMediaCollectionName($name));
                }
            }
        }
    }

    public function registerMediaCollections(): void
    {
        if ($this->mediaCollections === []) {
            if (! property_exists($this, 'mediaConfiguration')) {
                throw new InvalidArgumentException('Property "mediaConfiguration" not defined.');
            }

            $config = config('cms.media', []);

            foreach ($this->mediaConfiguration as $name => $key) {
                $media = data_get($config, $key, []);
                $onlyKeepLatest = data_get($media, 'only_keep_latest', null);

                $this
                    ->addMediaCollection($this->getMediaCollectionName($name))
                    ->acceptsMimeTypes((array) data_get($media, 'accepts_mime_types', []))
                    ->when((bool) $onlyKeepLatest, function ($mediaCollection) use ($onlyKeepLatest) {
                        $mediaCollection->onlyKeepLatest((int) $onlyKeepLatest);
                    });
            }
        }
    }

//    private function getHeightFromRatioAndWidth(string $ratio, int $width): int
//    {
//        [$denominator, $numerator] = explode(':', $ratio, 2);
//
//        return ((int) $numerator * $width) / (int) $denominator;
//    }

    private function getMediaCollectionName(string $name): string
    {
        $className = (string) Str::of(get_class($this))->lower()->explode('\\')->last();

        return sprintf('%s.%s', $className, $name);
    }
}
