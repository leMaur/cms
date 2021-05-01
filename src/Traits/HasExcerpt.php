<?php

declare(strict_types=1);

namespace Lemaur\Cms\Traits;

trait HasExcerpt
{
    public function setExcerptAttribute(?string $value): void
    {
        $this->extra_attributes->set('excerpt', $value);
    }

    public function getExcerptAttribute(): ?string
    {
        return $this->extra_attributes->get('excerpt');
    }
}
