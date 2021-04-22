<?php

namespace Lemaur\Cms\Http\Controllers;

use Lemaur\Cms\ViewModels\PageViewModel;
use Lemaur\Cms\Repositories\PageRepository;

class PageController
{
    public function __invoke(PageRepository $page, string $slug = null): PageViewModel
    {
        return $page->find($slug)->toViewModel();
    }
}
