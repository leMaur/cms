<?php

namespace Lemaur\Cms\Models\Concerns;

use Spatie\Sitemap\Tags\Url;

trait HasSitemapExtraAttributes
{
    public static array $availableFrequencies = [
        Url::CHANGE_FREQUENCY_ALWAYS,
        Url::CHANGE_FREQUENCY_HOURLY,
        Url::CHANGE_FREQUENCY_DAILY,
        Url::CHANGE_FREQUENCY_WEEKLY,
        Url::CHANGE_FREQUENCY_MONTHLY,
        Url::CHANGE_FREQUENCY_YEARLY,
        Url::CHANGE_FREQUENCY_NEVER,
    ];

    public function setSitemapFrequencyAttribute(string $value): void
    {
        if (! in_array($value, self::$availableFrequencies, true)) {
            return;
        }

        $this->extra_attributes->set('sitemap_frequency', $value);
    }

    public function getSitemapFrequencyAttribute(): ?string
    {
        return $this->extra_attributes->get('sitemap_frequency', (string) config('cms.seo.sitemap.frequency'));
    }

    public function setSitemapPriorityAttribute(float $value): void
    {
        $value = (float) max(0.1, min(1.0, $value));

        $this->extra_attributes->set('sitemap_priority', $value);
    }

    public function getSitemapPriorityAttribute(): ?float
    {
        return $this->extra_attributes->get('sitemap_priority', (float) config('cms.seo.sitemap.priority'));
    }
}
