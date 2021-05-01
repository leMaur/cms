<?php

declare(strict_types=1);

namespace Lemaur\Cms\Traits;

use Illuminate\Support\Str;
use Lemaur\Cms\Markdown;

trait HasMetaDescription
{
    public function setMetaDescriptionAttribute(string $value): void
    {
        $this->extra_attributes->set('meta_description', $value);
    }

    public function getMetaDescriptionAttribute(): string
    {
        if (is_null($this->content)) {
            return '';
        }

        $html = Markdown::convert($this->content, config('cms.markdown.options', []));

        $metaDescription = (string) Str::of(strip_tags($html))
            ->replace("\n", ' ')
            ->trim()
            ->limit((int) config('cms.seo.meta_description_limit', 150) - 3);

        return $this->extra_attributes->get('meta_description', $metaDescription, '');
    }
}
