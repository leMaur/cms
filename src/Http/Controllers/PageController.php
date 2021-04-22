<?php

namespace Lemaur\Cms\Http\Controllers;

use Lemaur\Cms\Repositories\PageRepository;
use Lemaur\Cms\ViewModels\PageViewModel;

class PageController
{
    public function __invoke(PageRepository $page, string $slug = null): PageViewModel
    {
        return $page->find($slug)->toViewModel();
    }
}
