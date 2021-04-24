<?php

namespace Lemaur\Cms\Tests\Feature\ViewModels;

use Lemaur\Cms\Models\Tag;
use Lemaur\Cms\Tests\TestCase;

class TagViewModelTest extends TestCase
{
    private Tag $tag;

    public function setUp(): void
    {
        parent::setUp();

        $this->tag = Tag::create([
            'name' => 'My Topic',
            'type' => 'topic',
        ]);
    }

    /** @test */
    public function it_has_name(): void
    {
        self::assertEquals('My Topic', $this->tag->toViewModel()->name());
    }

    /** @test */
    public function it_has_slug(): void
    {
        self::assertEquals('my-topic', $this->tag->toViewModel()->slug());
    }

    /** @test */
    public function it_has_type(): void
    {
        self::assertEquals('topic', $this->tag->toViewModel()->type());
    }
}
