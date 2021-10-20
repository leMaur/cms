<?php

declare(strict_types=1);

namespace Lemaur\Cms\Http\Responses;

use Lemaur\Cms\Models\Page;
use Lemaur\Cms\ViewModels\Contracts\Viewable;

class Response implements ResponseContract
{
    public function __invoke(Page $page): Viewable
    {
        return $page;
    }
}
