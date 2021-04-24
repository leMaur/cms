<?php

namespace Lemaur\Cms\Repositories\Contracts;

interface Findable
{
    public function find(?string $slug = null);
}
