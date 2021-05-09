<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

trait HasAuthor
{
    public static function bootHasAuthor(): void
    {
        if (auth()->check()) {
            static::creating(function ($model) {
                $model->user_id = auth()->user()?->id;
            });
        }
    }
}
