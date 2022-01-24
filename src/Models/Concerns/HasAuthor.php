<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

trait HasAuthor
{
    public static function bootHasAuthor(): void
    {
        /*
         * Even if its totally fine registering model events in a closure
         * (see https://laravel.com/docs/8.x/eloquent#events-using-closures),
         * avoid this technique if you plan to use Laravel Octane.
         *
         * Instead move this snippet in its own observer class.
         * (see https://laravel.com/docs/8.x/eloquent#observers)
         */

        $authUser = auth()->user();

        if ($authUser !== null) {
            static::creating(fn ($model) => $model->user_id = $authUser->getKey());
        }
    }
}
