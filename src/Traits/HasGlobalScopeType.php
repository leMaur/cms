<?php

declare(strict_types=1);

namespace Lemaur\Cms\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasGlobalScopeType
{
    public static function bootHasGlobalScopeType(): void
    {
        static::addGlobalScope(self::TYPE, function (Builder $builder): void {
            $builder->withType(self::TYPE)->reorder()->ordered();
        });

        static::saving(function ($model) {
            $model->type = self::TYPE;
        });
    }
}
