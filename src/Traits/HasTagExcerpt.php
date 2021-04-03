<?php

namespace Lemaur\Cms\Traits;

trait HasTagExcerpt
{
    public function getExcerptAttribute(): string
    {
        return $this->translate('name', 'excerpt', false);
    }

    public function setExcerptAttribute(string | null $value = null): void
    {
        if ($value === $this->name) {
            $value = '';
        }

        $this->setTranslation('name', 'excerpt', $value);
    }
}
