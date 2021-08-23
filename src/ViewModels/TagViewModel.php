<?php

declare(strict_types=1);

namespace Lemaur\Cms\ViewModels;

use Lemaur\Cms\Models\Tag;
use Spatie\ViewModels\ViewModel;

class TagViewModel extends ViewModel
{
    public function __construct(protected Tag $model)
    {
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
