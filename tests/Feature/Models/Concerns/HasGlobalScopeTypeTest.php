<?php

declare(strict_types=1);

namespace Lemaur\Cms\Tests\Feature\Models\Concerns;

use Illuminate\Support\Str;
use Lemaur\Cms\Models\Concerns\HasGlobalScopeType;
use Lemaur\Cms\Models\Contracts\Typeable;
use Lemaur\Cms\Models\Tag;
use Lemaur\Cms\Tests\TestCase;

class HasGlobalScopeTypeTest extends TestCase
{
    /** @test */
    public function it_add_a_global_scope(): void
    {
        $topic = Topic::create(['name' => 'Biophilic Travels', 'type' => Topic::getType()]);
        $category = Category::create(['name' => 'Tableware', 'type' => Category::getType()]);
        $productType = ProductType::create(['name' => 'Rug', 'type' => ProductType::getType()]);

        self::assertCount(1, Topic::get());
        self::assertEquals($topic->name, 'Biophilic Travels');
        self::assertEquals(Topic::getType(), 'topic');
        self::assertEquals($topic->type, 'topic');

        self::assertCount(1, Category::get());
        self::assertEquals($category->name, 'Tableware');
        self::assertEquals(Category::getType(), 'category');
        self::assertEquals($category->type, 'category');

        self::assertCount(1, ProductType::get());
        self::assertEquals($productType->name, 'Rug');
        self::assertEquals(ProductType::getType(), 'product_type');
        self::assertEquals($productType->type, 'product_type');
    }
}

class Topic extends Tag implements Typeable
{
    use HasGlobalScopeType;

    protected $table = 'tags';
}

class Category extends Tag implements Typeable
{
    use HasGlobalScopeType;

    protected $table = 'tags';
}

class ProductType extends Tag implements Typeable
{
    use HasGlobalScopeType;

    protected $table = 'tags';
}
