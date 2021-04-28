<?php

declare(strict_types=1);

namespace Lemaur\Cms;

use League\CommonMark\EnvironmentInterface;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class Markdown extends GithubFlavoredMarkdownConverter
{
    public static function convert(string $markdown = null, array $config = [], EnvironmentInterface $environment = null): ?string
    {
        if (is_null($markdown)) {
            return null;
        }

        return (new static($config, $environment))->convertToHtml($markdown);
    }
}
