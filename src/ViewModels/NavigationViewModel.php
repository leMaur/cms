<?php

declare(strict_types=1);

namespace Lemaur\Cms\ViewModels;

use Lemaur\Cms\Models\Navigation;
use Spatie\ViewModels\ViewModel;

class NavigationViewModel extends ViewModel
{
    public function __construct(protected Navigation $model)
    {
    }

    public function name(): string
    {
        return $this->model->name && ! $this->model->page
            ? $this->model->name : $this->model->page->toViewModel()->title();
    }

    public function slug(): string
    {
        return $this->model->slug && !$this->model->page
            ? $this->model->slug : $this->model->page->toViewModel()->slug();
    }

    public function url(): string
    {
        return $this->model->url && !$this->model->page
            ? $this->model->url : $this->model->page->toViewModel()->url();
    }

    public function image(string $collection = 'navigation.image'): ?ImageViewModel
    {
        $media = $this->model->getFirstMedia($collection);

        if (is_null($media)) {
            return null;
        }

        return new ImageViewModel($media);
    }

    public function group(): string | null
    {
        return $this->model->name && $this->model->page
            ? $this->model->name : null;
    }
}
