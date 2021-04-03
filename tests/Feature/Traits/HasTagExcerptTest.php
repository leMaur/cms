<?php

namespace Lemaur\Cms\Tests\Feature\Traits;

use Lemaur\Cms\Tests\TestCase;
use Lemaur\Cms\Traits\HasTagExcerpt;
use Spatie\Tags\Tag as SpatieTag;

class HasTagExcerptTest extends TestCase
{
    /** @test */
    public function it_has_excerpt()
    {
        $topic = Tag::create([
            'name' => 'Biophilic Travel',
            'excerpt' => <<<'MD'
                Visiting interiors that inspire me in my [design practice](https://dfordesign.style/services),
                reflecting on their biophilic features,
                and celebrating the design of interiors that connect people with nature.
                MD,
        ]);

        $this->assertEquals($topic->excerpt, Tag::first()->excerpt);
    }

    /** @test */
    public function it_may_hasnt_excerpt()
    {
        $topic = Tag::create([
            'name' => 'Biophilic Travel',
        ]);

        $this->assertEmpty($topic->excerpt);
        $this->assertEmpty(Tag::first()->excerpt);

        $topic = Tag::create([
            'name' => 'Biophilic Travel',
            'excerpt' => 'Biophilic Travel',
        ]);

        $this->assertEmpty($topic->excerpt);
        $this->assertEmpty(Tag::first()->excerpt);
    }
}

class Tag extends SpatieTag
{
    use HasTagExcerpt;

    protected $table = 'tags';
}
