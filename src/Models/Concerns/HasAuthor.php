<?php

namespace Lemaur\Cms\Models\Concerns;

use Illuminate\Support\Facades\Auth;

trait HasAuthor
{
    public static function bootHasAuthor()
    {
        if (Auth::check()) {
            static::creating(function ($model) {
                $model->user_id = Auth::user()?->id;
            });
        }
    }
}
