<?php

namespace Lemaur\Cms\Traits;

use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

trait HasTagOfType
{
    public static function bootHasTagOfType(): void
    {
        static::addGlobalScope(self::TYPE, function (Builder $builder): void {
            $builder->withType(self::TYPE)->reorder()->ordered();
        });

        static::saving(function ($model) {
            $model->type = self::TYPE;
        });
    }
}
