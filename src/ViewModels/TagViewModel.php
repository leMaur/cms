<?php

declare(strict_types=1);

namespace Lemaur\Cms\ViewModels;

use Lemaur\Cms\Models\Tag;
use Spatie\ViewModels\ViewModel;

/**
 * @template ModelType
 */
class TagViewModel extends ViewModel
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

    public function name(): string
    {
        return $this->model->name;
    }

    public function slug(): string
    {
        return $this->model->slug;
    }

    public function type(): string
    {
        return $this->model->type;
    }
}
