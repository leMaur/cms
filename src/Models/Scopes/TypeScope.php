<?php

namespace Lemaur\Cms\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TypeScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->withType($model::TYPE);
    }
}
