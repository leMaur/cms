<?php

declare(strict_types=1);

namespace Lemaur\Cms\ViewModels;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\ViewModels\ViewModel;

class ImageViewModel extends ViewModel
{
    public function __construct(protected Media $model)
    {
    }

    public function url(string $conversion = ''): string
    {
        return $this->model->getFullUrl($conversion);
    }

    public function alt(): ?string
    {
        return $this->model->getCustomProperty('alt', null);
    }

    public function caption(): ?string
    {
        return $this->model->getCustomProperty('caption', null);
    }

    public function html(string $conversionName = '', array $extraAttributes = []): string
    {
        return (string) $this->model->img($conversionName, $extraAttributes)->toHtml();
    }
}
