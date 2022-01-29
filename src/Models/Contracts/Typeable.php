<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Contracts;

interface Typeable
{
    public static function getType(): string;
}
