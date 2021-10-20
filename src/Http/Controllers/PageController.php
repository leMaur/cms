<?php

declare(strict_types=1);

namespace Lemaur\Cms\Http\Controllers;

use Illuminate\Contracts\Support\Responsable;
use Lemaur\Cms\Http\Authorization\GateContract;
use Lemaur\Cms\Http\Responses\ResponseContract;
use Lemaur\Cms\Repositories\PageRepository;

class PageController
{
    public function __invoke(PageRepository $page, ?string $slug = null): Responsable
    {
        $resource = $page->find($slug);

        app(GateContract::class)($resource);

        return app(ResponseContract::class)($resource)->toViewModel();
    }
}
