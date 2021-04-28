<?php

declare(strict_types=1);

namespace Lemaur\Cms\Repositories\Contracts;

interface Findable
{
    public function find(?string $slug = null);
}
