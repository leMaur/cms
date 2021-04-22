<?php

namespace Lemaur\Cms\Tests\Feature\Models;

use Lemaur\Cms\Models\Tag;
use Lemaur\Cms\Tests\TestCase;

class TagTest extends TestCase
{
    /** @test */
    public function it_may_has_extra_attributes(): void
    {
        $tag = Tag::create(['name' => 'New Tag']);

        // Set extra attribute
        $tag->setExtraAttribute('excerpt', 'something to say');

        // Get extra attributes
        self::assertEquals('something to say', $tag->getExtraAttribute('excerpt'));

        // Has extra attribute
        self::assertTrue($tag->hasExtraAttribute('excerpt'));

        // Forget extra attribute
        $tag->forgetExtraAttribute('excerpt');

        self::assertFalse($tag->hasExtraAttribute('excerpt'));

        // Fill extra attributes
        $tag->fillExtraAttributes(['excerpt' => 'something to say']);

        self::assertEquals('something to say', $tag->getExtraAttribute('excerpt'));
    }
}
