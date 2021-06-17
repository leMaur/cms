<?php

declare(strict_types=1);

namespace Lemaur\Cms\ViewModels;

use Lemaur\Cms\Models\Tag;
use Spatie\ViewModels\ViewModel;

class TagViewModel extends ViewModel
{
    public function __construct(protected Tag $tag)
    {
    }

    public function name(): string
    {
        return $this->tag->name;
    }

    public function slug(): string
    {
        return $this->tag->slug;
    }

    public function type(): string
    {
        return $this->tag->type;
    }
}
