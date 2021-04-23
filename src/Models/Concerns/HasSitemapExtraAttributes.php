<?php

namespace Lemaur\Cms\Models\Concerns;

use Spatie\Sitemap\Tags\Url;

trait HasSitemapExtraAttributes
{
    public function setSitemapFrequencyAttribute(string $value): void
    {
        // @TODO: export this list to a separate class
        $availableFrequencies = [
            Url::CHANGE_FREQUENCY_ALWAYS,
            Url::CHANGE_FREQUENCY_HOURLY,
            Url::CHANGE_FREQUENCY_DAILY,
            Url::CHANGE_FREQUENCY_WEEKLY,
            Url::CHANGE_FREQUENCY_MONTHLY,
            Url::CHANGE_FREQUENCY_YEARLY,
            Url::CHANGE_FREQUENCY_NEVER,
        ];

        if (! in_array($value, $availableFrequencies, true)) {
            return;
        }

        $this->extra_attributes->set('sitemap_frequency', $value);
    }

    public function getSitemapFrequencyAttribute(): ?string
    {
        return $this->extra_attributes->get('sitemap_frequency', Url::CHANGE_FREQUENCY_YEARLY);
    }

    public function setSitemapPriorityAttribute(float $value): void
    {
        $value = (float) max(0.1, min(1.0, $value));

        $this->extra_attributes->set('sitemap_priority', $value);
    }

    public function getSitemapPriorityAttribute(): ?float
    {
        return $this->extra_attributes->get('sitemap_priority', 0.8);
    }
}
