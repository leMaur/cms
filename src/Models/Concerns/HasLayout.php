<?php

namespace Lemaur\Cms\Models\Concerns;

trait HasLayout
{
    public static function bootHasLayout(): void
    {
        static::saving(function ($model) {
            if ($model->layout === '') {
                $model->extra_attributes->set('layout', 'page');
            }
        });
    }

    public function setLayoutAttribute($value): void
    {
        $this->extra_attributes->set('layout', $value);
    }

    public function getLayoutAttribute(): ?string
    {
        return $this->extra_attributes->get('layout');
    }
}
