<?php

namespace Lemaur\Cms\Tests\Feature\Traits;

use Lemaur\Cms\Tests\TestCase;
use Lemaur\Cms\Traits\HasTagOfType;
use Spatie\Tags\Tag;

class HasTagOfTypeTest extends TestCase
{
    /** @test */
    public function it_add_a_global_scope()
    {
        $topic = Topic::create(['name' => 'Biophilic Travels']);
        $category = Category::create(['name' => 'Tableware']);

        $this->assertCount(1, Topic::get());
        $this->assertEquals($topic->name, Topic::first()->name);
        $this->assertEquals($topic->type, Topic::first()->type);

        $this->assertCount(1, Category::get());
        $this->assertEquals($category->name, Category::first()->name);
        $this->assertEquals($category->type, Category::first()->type);
    }
}

class Topic extends Tag
{
    use HasTagOfType;

    public const TYPE = 'topic';

    protected $table = 'tags';
}

class Category extends Tag
{
    use HasTagOfType;

    public const TYPE = 'category';

    protected $table = 'tags';
}
