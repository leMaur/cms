<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

use Illuminate\Support\Str;
use Lemaur\Markdown\Markdown;

trait HasMetaDescription
{
    public function setMetaDescriptionAttribute(?string $value): void
    {
        $this->extra_attributes->set('meta_description', $value);
    }

    public function getMetaDescriptionAttribute(): string
    {
        return (string) $this->extra_attributes->get('meta_description', $this->getDefaultMetaDescription());
    }

    protected function getDefaultMetaDescription(): string
    {
        $html = Markdown::render($this->content);

        if ($html->isEmpty()) {
            return '';
        }

        $html = strip_tags($html->toHtml());
        $html = html_entity_decode(preg_replace("/[\r\n]{2,}/", "\n\n", $html), ENT_QUOTES, 'UTF-8');
        $html = htmlentities($html);

        return (string) Str::of($html)
            ->replaceMatches('/\R+|(?:\&nbsp;)+/', ' ')
            ->replaceMatches('/\s+/', ' ')
            ->trim()
            ->limit((int) config('cms.seo.meta_description_limit', 150) - 3);
    }
}
