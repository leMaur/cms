<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

use Illuminate\Support\Facades\Auth;

trait HasAuthor
{
    public static function bootHasAuthor(): void
    {
        if (Auth::check()) {
            static::creating(function ($model) {
                $model->user_id = Auth::user()?->id;
            });
        }
    }
}
