<?php

namespace Lemaur\Cms\Traits;

use Illuminate\Support\Str;
use Lemaur\Cms\Markdown;
use Lemaur\Cms\Models\Page;

trait HasMetaDescription
{
    public function setMetaDescriptionAttribute($value): void
    {
        $this->extra_attributes->set('meta_description', $value);
    }

    public function getMetaDescriptionAttribute(): string
    {
        $html = Markdown::convert($this->content, config('cms.markdown.options', []));

        return (string) $this->extra_attributes->get('meta_description', Str::limit(strip_tags($html), Page::META_DESCRIPTION_LIMIT - 3));
    }
}
