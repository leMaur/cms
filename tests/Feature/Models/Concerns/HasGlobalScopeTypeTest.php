<?php

declare(strict_types=1);

namespace Lemaur\Cms\Tests\Feature\Models\Concerns;

use Lemaur\Cms\Models\Concerns\HasGlobalScopeType;
use Lemaur\Cms\Models\Tag;
use Lemaur\Cms\Tests\TestCase;

class HasGlobalScopeTypeTest extends TestCase
{
    /** @test */
    public function it_add_a_global_scope(): void
    {
        $topic = Topic::create(['name' => 'Biophilic Travels', 'type' => 'topic']);
        $category = Category::create(['name' => 'Tableware', 'type' => 'category']);

        self::assertCount(1, Topic::get());
        self::assertEquals($topic->name, 'Biophilic Travels');
        self::assertEquals($topic->type, Topic::TYPE);

        self::assertCount(1, Category::get());
        self::assertEquals($category->name, 'Tableware');
        self::assertEquals($category->type, Category::TYPE);
    }
}

class Topic extends Tag
{
    use HasGlobalScopeType;

    public const TYPE = 'topic';

    protected $table = 'tags';
}

class Category extends Tag
{
    use HasGlobalScopeType;

    public const TYPE = 'category';

    protected $table = 'tags';
}
