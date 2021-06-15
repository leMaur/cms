<?php

declare(strict_types=1);

namespace Lemaur\Cms\Support\Parsers;

use League\CommonMark\Inline\Element\HtmlInline;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\InlineParserContext;

class BladeComponentParser implements InlineParserInterface
{
    public function getCharacters(): array
    {
        return ['::'];
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();

        $previousChar = $cursor->peek(-1);

        if ($previousChar !== null && $previousChar !== ' ') {
            return false;
        }

        $previousState = $cursor->saveState();

        $cursor->advance();

        $handle = $cursor->match('/^[A-Za-z\-\.]+/');

        if (empty($handle)) {
            $cursor->restoreState($previousState);

            return false;
        }

        $component = 'components.' . $handle;

        $view = view($component)->render();

        $inlineContext->getContainer()->appendChild(new HtmlInline($view));

        return true;
    }
}
