<?php

declare(strict_types=1);

namespace Lemaur\Cms\ViewModels;

use Spatie\ViewModels\ViewModel;

/**
 * @template ModelType
 */
class ImageViewModel extends ViewModel
{
    /**
     * @param ModelType $model
     */
    public function __construct(protected mixed $model)
    {
    }

    /**
     * @return ModelType
     */
    public function toModel()
    {
        return $this->model;
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
