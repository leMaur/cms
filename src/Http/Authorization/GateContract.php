<?php

declare(strict_types=1);

namespace Lemaur\Cms\Http\Authorization;

use Illuminate\Database\Eloquent\Model;

interface GateContract
{
    public function __invoke(Model $model): void;
}
