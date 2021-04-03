<?php

namespace Lemaur\Cms\Repositories\Contracts;

use Lemaur\Cms\Models\Page;

interface Repository
{
    public function find(string | null $slug = null): Page;
}
