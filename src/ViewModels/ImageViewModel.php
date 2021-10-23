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

    public function uuid(): string
    {
        return $this->model->uuid;
    }

    public function url(string $conversion = ''): string
    {
        return $this->model->getFullUrl($conversion);
    }

    public function alt(): ?string
    {
        return $this->model->getCustomProperty('alt');
    }

    public function caption(): ?string
    {
        return $this->model->getCustomProperty('caption');
    }

    public function width(): ?string
    {
        return $this->model->getCustomProperty('width');
    }

    public function height(): ?string
    {
        return $this->model->getCustomProperty('height');
    }

    public function html(string $conversionName = '', array $extraAttributes = []): string
    {
        return $this->model->img($conversionName, $extraAttributes)->toHtml();
    }
}
