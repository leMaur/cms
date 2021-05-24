<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

trait HasMetaTitle
{
    public function setMetaTitleAttribute(?string $value): void
    {
        $this->extra_attributes->set('meta_title', $value);
    }

    public function getMetaTitleAttribute(): string
    {
        return (string) $this->extra_attributes->get('meta_title', $this->title ?? '');
    }
}
