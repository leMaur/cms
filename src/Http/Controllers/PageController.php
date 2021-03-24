<?php

namespace Lemaur\Cms\Http\Controllers;

use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\View;
use Lemaur\Cms\Repositories\PageRepository;

class PageController
{
    public function __invoke(PageRepository $page, string $slug = null): ViewContract
    {
        $data = $page->find($slug);

        return View::make('cms::base', [
            'content' => $data->content,
        ]);
    }
}
