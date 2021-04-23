<?php

namespace Lemaur\Cms\ViewModels;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\ViewModels\ViewModel;

class ImageViewModel extends ViewModel
{
    public function __construct(private Media $media)
    {
    }

    public function url(string $conversion = ''): string
    {
        return $this->media->getFullUrl($conversion);
    }

    public function alt(): string | null
    {
        return $this->media->getCustomProperty('alt', null);
    }

    public function caption(): string | null
    {
        return $this->media->getCustomProperty('caption', null);
    }
}
