<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

use Illuminate\Support\Str;
use Lemaur\Cms\Markdown;

trait HasMetaDescription
{
    public function setMetaDescriptionAttribute(?string $value): void
    {
        $this->extra_attributes->set('meta_description', $value);
    }

    public function getMetaDescriptionAttribute(): string
    {
        if (is_null($this->content)) {
            return '';
        }

        $html = Markdown::convert($this->content, config('cms.markdown.options', []));

        $metaDescription = (string) Str::of(htmlentities(strip_tags($html)))
            ->replaceMatches('/\R+|(?:\&nbsp;)+/', ' ')
            ->replaceMatches('/\s+/', ' ')
            ->trim()
            ->limit((int) config('cms.seo.meta_description_limit', 150) - 3);

        if (is_null($metaDescription)) {
            return '';
        }

        return $this->extra_attributes->get('meta_description', $metaDescription);
    }
}
