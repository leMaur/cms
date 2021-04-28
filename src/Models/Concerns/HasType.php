<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasType
{
    public function scopeWithType(Builder $query, string $type = null): Builder
    {
        if (is_null($type)) {
            return $query;
        }

        return $query->where('type', $type);
    }
}
