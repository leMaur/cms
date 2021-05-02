<?php

declare(strict_types=1);

namespace Lemaur\Cms\Tests\Feature\Models;

use Illuminate\Http\UploadedFile;
use Lemaur\Cms\Models\Tag;
use Lemaur\Cms\Tests\TestCase;

class TagTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->tag = Tag::create(['name' => 'New Tag']);
    }

    /** @test */
    public function it_may_has_extra_attributes(): void
    {
        // Set extra attribute
        $this->tag->setExtraAttribute('excerpt', 'something to say');

        // Get extra attributes
        self::assertEquals('something to say', $this->tag->getExtraAttribute('excerpt'));

        // Has extra attribute
        self::assertTrue($this->tag->hasExtraAttribute('excerpt'));

        // Forget extra attribute
        $this->tag->forgetExtraAttribute('excerpt');

        self::assertFalse($this->tag->hasExtraAttribute('excerpt'));

        // Fill extra attributes
        $this->tag->fillExtraAttributes(['excerpt' => 'something to say']);

        self::assertEquals('something to say', $this->tag->getExtraAttribute('excerpt'));
    }

    /** @test */
    public function it_may_has_a_single_image(): void
    {
        $image = UploadedFile::fake()->image('image.png');
        $this->tag->addMedia($image)->toMediaCollection('tag.image');

        self::assertSame($image->name, $this->tag->getMedia('tag.image')->first()->file_name);
    }

    /** @test */
    public function it_has_a_pivot_table_name_helper(): void
    {
        $pivotTableName = Tag::pivotTableName();

        self::assertEquals('taggable', $pivotTableName);
    }

    /** @test */
    public function it_has_a_pivot_key_helper(): void
    {
        $pivotKey = Tag::pivotKey();

        self::assertEquals('tag_id', $pivotKey);
    }
}
