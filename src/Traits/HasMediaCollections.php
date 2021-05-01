<?php

declare(strict_types=1);

namespace Lemaur\Cms\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia as SpatieInteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\ImageFactory;

trait HasMediaCollections
{
    use SpatieInteractsWithMedia;

    public function registerMediaConversions(Media $media = null): void
    {
        if ($this->mediaConversions === []) {
            $config = (array) config('cms.media_conversions', []);

            foreach ($this->mediaConversionConfiguration ?? [] as $name => $key) {
                foreach (Arr::wrap($key) as $configKey) {
                    $mediaConfig = (array) data_get($config, $configKey, []);

                    $width = (int) data_get($mediaConfig, 'width');
                    $height = (int) data_get($mediaConfig, 'height');

                    if (! is_null($media) && (bool) data_get($mediaConfig, 'aspect_ratio')) {
                        $ratio = (string) data_get($mediaConfig, 'aspect_ratio');
                        [$width, $height] = $this->getDimensionsFromAspectRatio($ratio, $media);
                    }

                    $this
                        ->addMediaConversion($configKey)
                        ->fit(Manipulations::FIT_CROP, $width, $height)
                        ->performOnCollections($this->getMediaCollectionName($name));
                }
            }
        }
    }

    public function registerMediaCollections(): void
    {
        if ($this->mediaCollections === []) {
            if (! property_exists($this, 'mediaConfiguration')) {
                return;
            }

            $config = (array) config('cms.media', []);

            foreach ($this->mediaConfiguration as $name => $key) {
                $media = data_get($config, $key, []);
                $onlyKeepLatest = data_get($media, 'only_keep_latest');

                $this
                    ->addMediaCollection($this->getMediaCollectionName($name))
                    ->acceptsMimeTypes((array) data_get($media, 'accepts_mime_types', []))
                    ->when((bool) $onlyKeepLatest, function (MediaCollection $mediaCollection) use ($onlyKeepLatest) {
                        $mediaCollection->onlyKeepLatest((int) $onlyKeepLatest);
                    });
            }
        }
    }

    private function getDimensionsFromAspectRatio(string $ratio, Media $media): array
    {
        [$denominator, $numerator] = array_map('intval', explode(':', $ratio, 2));

        $method = $denominator > $numerator ? 'getHeightFromAspectRatio' : 'getWidthFromAspectRatio';

        return $this->{$method}($denominator, $numerator, $media);
    }

    private function getWidthFromAspectRatio(int $denominator, int $numerator, Media $media): array
    {
        $height = ImageFactory::load($media->getPath())->getHeight();

        return [($denominator * $height) / $numerator, $height];
    }

    private function getHeightFromAspectRatio(int $denominator, int $numerator, Media $media): array
    {
        $width = ImageFactory::load($media->getPath())->getWidth();

        return [$width, ($numerator * $width) / $denominator];
    }

    private function getMediaCollectionName(string $name): string
    {
        $className = (string) Str::of(get_class($this))->lower()->explode('\\')->last();

        return sprintf('%s.%s', $className, Str::lower($name));
    }
}
