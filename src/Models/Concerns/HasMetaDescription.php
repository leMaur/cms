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

    private function getDefaultMetaDescription(): string
    {
        $text = Markdown::render($this->content);

        if (is_null($text)) {
            return '';
        }

        $text = strip_tags($text);
        $text = html_entity_decode(preg_replace("/[\r\n]{2,}/", "\n\n", $text), ENT_QUOTES, 'UTF-8');
        $text = htmlentities($text);

        return (string) Str::of($text)
            ->replaceMatches('/\R+|(?:\&nbsp;)+/', ' ')
            ->replaceMatches('/\s+/', ' ')
            ->trim()
            ->limit((int) config('cms.seo.meta_description_limit', 150) - 3);
    }
}
