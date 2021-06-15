<?php

declare(strict_types=1);

namespace Lemaur\Cms\Support;

use Illuminate\Support\HtmlString;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use Lemaur\Cms\Support\Parsers\BladeComponentParser;

class Markdown
{
    public static function convert(?string $markdown = null): ?string
    {
        if (is_null($markdown)) {
            return null;
        }

        $options = config('cms.markdown.options', []);

        $environment = Environment::createCommonMarkEnvironment();
        $environment->addInlineParser(new BladeComponentParser());

        $converter = new CommonMarkConverter($options, $environment);

        return (string) new HtmlString($converter->convertToHtml($markdown));
    }
}
