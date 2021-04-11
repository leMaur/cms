<?php

namespace Lemaur\Cms;

use League\CommonMark\EnvironmentInterface;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class Markdown extends GithubFlavoredMarkdownConverter
{
    public static function convert(string $markdown, array $config = [], EnvironmentInterface $environment = null): string
    {
        return (new static($config, $environment))->convertToHtml($markdown);
    }
}
