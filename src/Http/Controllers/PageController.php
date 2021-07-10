<?php

declare(strict_types=1);

namespace Lemaur\Cms\Http\Controllers;

use Lemaur\Cms\Http\Authorization\GateContract;
use Lemaur\Cms\Repositories\PageRepository;
use Lemaur\Cms\ViewModels\PageViewModel;

class PageController
{
    public function __invoke(PageRepository $page, ?string $slug = null): PageViewModel
    {
        $model = $page->find($slug);

        app(GateContract::class)($model);

        return $model->toViewModel();
    }
}
