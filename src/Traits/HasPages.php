<?php

namespace Lemaur\Cms\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasPages
{
    public function pages(): HasMany
    {
        return $this->hasMany((string) config('cms.pages.model'), 'user_id');
    }
}
