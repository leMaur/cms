<?php

namespace Lemaur\Cms\ViewModels;

use Lemaur\Cms\Models\Navigation;
use Spatie\ViewModels\ViewModel;

class NavigationViewModel extends ViewModel
{
    public function __construct(private Navigation $navigation)
    {
    }

    public function name(): string
    {
        return $this->navigation->name
            ?? $this->navigation->page->toViewModel()->title();
    }

    public function slug(): string
    {
        return $this->navigation->slug
            ?? $this->navigation->page->toViewModel()->slug();
    }

    public function url(): string
    {
        return $this->navigation->url
            ?? $this->navigation->page->toViewModel()->url();
    }

    public function image(string $collection = 'navigation.image'): ImageViewModel | null
    {
        $media = $this->navigation->getFirstMedia($collection);

        if (is_null($media)) {
            return null;
        }

        return new ImageViewModel($media);
    }
}
