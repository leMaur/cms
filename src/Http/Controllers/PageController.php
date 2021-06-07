<?php

declare(strict_types=1);

namespace Lemaur\Cms\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Lemaur\Cms\Repositories\PageRepository;
use Lemaur\Cms\ViewModels\PageViewModel;

class PageController
{
    public function __invoke(PageRepository $page, ?string $slug = null): PageViewModel
    {
        Log::channel('errorlog')->alert("Hit PageController '{$slug}'");

        return $page->find($slug)->toViewModel();
    }
}
