<?php

namespace Lemaur\Cms\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasUser
{
    public function user(): BelongsTo
    {
        return $this->belongsTo((string) config('cms.users.model'), 'user_id');
    }
}
