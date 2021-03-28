<?php

namespace Lemaur\Cms\Models\Concerns;

trait HasLayout
{
    public function setLayoutAttribute($value): void
    {
        $this->extra_attributes->set('layout', $value);
    }

    public function getLayoutAttribute(): ?string
    {
        return $this->extra_attributes->get('layout');
    }
}
