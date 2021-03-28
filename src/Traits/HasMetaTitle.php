<?php

namespace Lemaur\Cms\Traits;

trait HasMetaTitle
{
    public function setMetaTitleAttribute($value): void
    {
        $this->extra_attributes->set('meta_title', $value);
    }

    public function getMetaTitleAttribute(): string
    {
        return (string) $this->extra_attributes->get('meta_title', $this->title);
    }
}
