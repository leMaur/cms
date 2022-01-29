<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

trait HasAuthor
{
    public static function bootHasAuthor(): void
    {
        $authUser = auth()->user();

        if ($authUser !== null) {
            self::creating(static fn ($model) => $model->user_id = $authUser->getKey());
        }
    }
}
