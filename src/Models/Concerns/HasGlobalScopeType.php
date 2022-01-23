<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasGlobalScopeType
{
    public static function bootHasGlobalScopeType(): void
    {
        static::addGlobalScope(self::TYPE, fn (Builder $builder) => $builder->withType(self::TYPE));

        /*
         * Even if its totally fine registering model events in a closure
         * (see https://laravel.com/docs/8.x/eloquent#events-using-closures),
         * avoid this technique if you plan to use Laravel Octane.
         *
         * Instead move this snippet in its own observer class.
         * (see https://laravel.com/docs/8.x/eloquent#observers)
         */
        static::saving(fn ($model) => $model->type = self::TYPE);
    }
}
