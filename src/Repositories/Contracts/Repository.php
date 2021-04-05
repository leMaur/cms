<?php

namespace Lemaur\Cms\Repositories\Contracts;

use Lemaur\Cms\Models\ViewModels\PageViewModel;

interface Repository
{
    public function find(string | null $slug = null): PageViewModel;
}
