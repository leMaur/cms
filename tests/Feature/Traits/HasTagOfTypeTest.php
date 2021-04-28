<?php

declare(strict_types=1);

namespace Lemaur\Cms\Tests\Feature\Traits;

use Lemaur\Cms\Models\Tag;
use Lemaur\Cms\Tests\TestCase;
use Lemaur\Cms\Traits\HasGlobalScopeType;

class HasTagOfTypeTest extends TestCase
{
    /** @test */
    public function it_add_a_global_scope(): void
    {
        $topic = Topic::create(['name' => 'Biophilic Travels']);
        $category = Category::create(['name' => 'Tableware']);

        self::assertCount(1, Topic::get());
        self::assertEquals($topic->name, Topic::first()->name);
        self::assertEquals($topic->type, Topic::first()->type);

        self::assertCount(1, Category::get());
        self::assertEquals($category->name, Category::first()->name);
        self::assertEquals($category->type, Category::first()->type);
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
