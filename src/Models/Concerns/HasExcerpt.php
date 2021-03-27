<?php

namespace Lemaur\Cms\Models\Concerns;

use Illuminate\Support\Str;

trait HasExcerpt
{
    public function setExcerptAttribute($value): void
    {
        $this->extra_attributes->set('excerpt', $value);
    }

    public function getExcerptAttribute(): string
    {
        return (string) $this->extra_attributes->get('excerpt', Str::limit($this->content, self::EXCERPT_LIMIT - 3));
    }
}
