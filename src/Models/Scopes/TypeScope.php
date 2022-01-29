<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Lemaur\Cms\Models\Contracts\Typeable;

class TypeScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if ($model instanceof Typeable) {
            $builder->where('type', $model::getType());
        }
    }
}
