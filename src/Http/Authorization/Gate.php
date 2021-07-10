<?php

declare(strict_types=1);

namespace Lemaur\Cms\Http\Authorization;

use Illuminate\Database\Eloquent\Model;

class Gate implements GateContract
{
    public function __invoke(Model $model): void
    {
        //
    }
}
