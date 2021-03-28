<?php

namespace Lemaur\Cms\Traits;

trait HasExcerpt
{
    public function setExcerptAttribute($value): void
    {
        $this->extra_attributes->set('excerpt', $value);
    }

    public function getExcerptAttribute(): ?string
    {
        return $this->extra_attributes->get('excerpt');
    }
}
