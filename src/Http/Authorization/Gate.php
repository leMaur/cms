<?php

declare(strict_types=1);

namespace Lemaur\Cms\Http\Authorization;

use Lemaur\Cms\Models\Page;

class Gate implements GateContract
{
    public function __invoke(Page $page): void
    {
        //
    }
}
