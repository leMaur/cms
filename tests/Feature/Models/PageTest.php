<?php

namespace Lemaur\Cms\Tests\Feature\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Lemaur\Cms\Models\Navigation;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Tests\Feature\User;
use Lemaur\Cms\Tests\Feature\User as TestUser;
use Lemaur\Cms\Tests\TestCase;
use Lemaur\Cms\Traits\HasExcerpt;
use Lemaur\Cms\Traits\HasMetaDescription;
use Lemaur\Cms\Traits\HasMetaTitle;

class PageTest extends TestCase
{
    /** @test */
    public function it_may_belongs_to_a_user(): void
    {
        $user = TestUser::create(['email' => 'maurizio@example.com']);
        $page = Page::create(['user_id' => $user->id, 'title' => 'My PageRepository']);

        self::assertInstanceOf(BelongsTo::class, $page->user());
        self::assertInstanceOf(TestUser::class, $page->user);

        $page = Page::create(['user_id' => null, 'title' => 'My PageRepository']);

        self::assertEquals(null, $page->user);
    }

    /** @test */
    public function it_may_has_navigations(): void
    {
        $page = Page::factory()->create();

        $page->navigations()->create(['type' => Navigation::PRIMARY]);

        self::assertInstanceOf(HasMany::class, $page->navigations());
        self::assertInstanceOf(Collection::class, $page->navigations);
        self::assertCount(1, $page->navigations);
        $this->assertDatabaseHas('navigations', [
            'page_id' => $page->id, 'type' => Navigation::PRIMARY,
        ]);

        $page->navigations()->create(['type' => Navigation::SECONDARY]);

        self::assertCount(2, $page->fresh()->navigations);
        $this->assertDatabaseHas('navigations', [
            'page_id' => $page->id, 'type' => Navigation::SECONDARY,
        ]);
    }

    /** @test */
    public function it_has_autogenerated_slug_from_title(): void
    {
        Page::create(['title' => 'My Page']);

        $this->assertDatabaseHas('pages', ['title' => 'My Page', 'slug' => 'my-page']);
    }

    /** @test */
    public function it_has_unique_slugs(): void
    {
        Page::create(['title' => 'My Page']);
        Page::create(['title' => 'My Page']);

        $this->assertDatabaseHas('pages', ['slug' => 'my-page']);
        $this->assertDatabaseHas('pages', ['slug' => 'my-page-1']);
    }

    /** @test */
    public function it_has_autogenerated_parent_from_page_model(): void
    {
        $parent = Page::create(['title' => 'Parent']);
        $page = Page::create(['title' => 'PageRepository', 'parent' => $parent]);

        self::assertEquals(null, $parent->parent);
        self::assertEquals('parent', $page->parent);

        $this->assertDatabaseHas('pages', ['parent' => null, 'title' => 'Parent']);
        $this->assertDatabaseHas('pages', ['parent' => 'parent', 'title' => 'PageRepository']);
    }

    /** @test */
    public function it_has_autogenerated_parent_from_nested_page_model(): void
    {
        $parent = Page::create(['title' => 'Parent']);
        $child = Page::create(['title' => 'Child', 'parent' => $parent]);
        $page = Page::create(['title' => 'PageRepository', 'parent' => $child]);

        self::assertEquals(null, $parent->parent);
        self::assertEquals('parent', $child->parent);
        self::assertEquals('parent/child', $page->parent);

        $this->assertDatabaseHas('pages', ['parent' => null, 'title' => 'Parent']);
        $this->assertDatabaseHas('pages', ['parent' => 'parent', 'title' => 'Child']);
        $this->assertDatabaseHas('pages', ['parent' => 'parent/child', 'title' => 'PageRepository']);
    }

    /** @test */
    public function it_has_autogenerated_parent_from_deeply_nested_page_model(): void
    {
        $first = Page::create(['title' => 'First']);
        $second = Page::create(['title' => 'Second', 'parent' => $first]);
        $third = Page::create(['title' => 'Third', 'parent' => $second]);
        $fourth = Page::create(['title' => 'Fourth', 'parent' => $third]);

        self::assertEquals(null, $first->parent);
        self::assertEquals('first', $second->parent);
        self::assertEquals('first/second', $third->parent);
        self::assertEquals('first/second/third', $fourth->parent);

        $this->assertDatabaseHas('pages', ['parent' => null, 'title' => 'First']);
        $this->assertDatabaseHas('pages', ['parent' => 'first', 'title' => 'Second']);
        $this->assertDatabaseHas('pages', ['parent' => 'first/second', 'title' => 'Third']);
        $this->assertDatabaseHas('pages', ['parent' => 'first/second/third', 'title' => 'Fourth']);
    }

    /** @test */
    public function it_has_autogenerated_parent_from_page_slug(): void
    {
        $parent = Page::create(['title' => 'Parent']);
        $page = Page::create(['title' => 'Page', 'parent' => $parent->slug]);

        self::assertEquals(null, $parent->parent);
        self::assertEquals('parent', $page->parent);

        $this->assertDatabaseHas('pages', ['parent' => null, 'title' => 'Parent']);
        $this->assertDatabaseHas('pages', ['parent' => 'parent', 'title' => 'Page']);
    }

    /** @test */
    public function it_has_autogenerated_parent_from_nested_page_slug(): void
    {
        $parent = Page::create(['title' => 'Parent']);
        $child = Page::create(['title' => 'Child', 'parent' => $parent->slug]);
        $page = Page::create(['title' => 'Page', 'parent' => $child->slug]);

        self::assertEquals(null, $parent->parent);
        self::assertEquals('parent', $child->parent);
        self::assertEquals('parent/child', $page->parent);

        $this->assertDatabaseHas('pages', ['parent' => null, 'title' => 'Parent']);
        $this->assertDatabaseHas('pages', ['parent' => 'parent', 'title' => 'Child']);
        $this->assertDatabaseHas('pages', ['parent' => 'parent/child', 'title' => 'Page']);
    }

    /** @test */
    public function it_has_autogenerated_parent_from_deeply_nested_page_slug(): void
    {
        $first = Page::create(['title' => 'First']);
        $second = Page::create(['title' => 'Second', 'parent' => $first->slug]);
        $third = Page::create(['title' => 'Third', 'parent' => $second->slug]);
        $fourth = Page::create(['title' => 'Fourth', 'parent' => $third->slug]);

        self::assertEquals(null, $first->parent);
        self::assertEquals('first', $second->parent);
        self::assertEquals('first/second', $third->parent);
        self::assertEquals('first/second/third', $fourth->parent);

        $this->assertDatabaseHas('pages', ['parent' => null, 'title' => 'First']);
        $this->assertDatabaseHas('pages', ['parent' => 'first', 'title' => 'Second']);
        $this->assertDatabaseHas('pages', ['parent' => 'first/second', 'title' => 'Third']);
        $this->assertDatabaseHas('pages', ['parent' => 'first/second/third', 'title' => 'Fourth']);
    }

    /** @test */
    public function it_may_has_extra_attributes(): void
    {
        $page = Page::factory()->create();

        // Set extra attribute
        $page->setExtraAttribute('meta', [
            'title' => 'Meta Title',
            'description' => 'Meta Description',
        ]);

        // Get extra attributes
        self::assertEquals([
            'title' => 'Meta Title',
            'description' => 'Meta Description',
        ], $page->getExtraAttribute('meta'));

        // Has extra attribute
        self::assertTrue($page->hasExtraAttribute('meta'));

        // Forget extra attribute
        $page->forgetExtraAttribute('meta');

        self::assertFalse($page->hasExtraAttribute('meta'));

        // Fill extra attributes
        $page->fillExtraAttributes([
            'meta' => [
                'title' => 'Meta Title',
                'description' => 'Meta Description',
            ],
        ]);

        self::assertEquals([
            'title' => 'Meta Title',
            'description' => 'Meta Description',
        ], $page->getExtraAttribute('meta'));
    }

    /** @test */
    public function it_may_has_excerpt(): void
    {
        $content = Page::factory()->raw(['excerpt' => 'excerpt']);

        $page = TestPage::create($content);

        self::assertEquals('excerpt', $page->fresh()->excerpt);
    }

    /** @test */
    public function it_may_has_meta_title(): void
    {
        $content = Page::factory()->raw(['meta_title' => 'meta title']);

        $page = TestPage::create($content);

        self::assertEquals('meta title', $page->fresh()->meta_title);
    }

    /** @test
     */
    public function it_may_has_meta_title_autogenerated_by_page_title(): void
    {
        $content = Page::factory()->raw(['title' => 'My Title']);

        $page = TestPage::create($content);

        self::assertEquals('My Title', $page->meta_title);
    }

    /** @test */
    public function it_may_has_meta_description(): void
    {
        $content = Page::factory()->raw(['meta_description' => 'meta description']);

        $page = TestPage::create($content);

        self::assertEquals('meta description', $page->fresh()->meta_description);
    }

    /** @test */
    public function it_may_has_meta_description_autogenerated_by_page_content(): void
    {
        $content = Page::factory()->raw();

        $page = TestPage::create($content);

        self::assertNotNull($page->meta_description);
        self::assertTrue(Str::length($page->meta_description) <= (int) config('cms.seo.meta_description_limit', 150));
    }

    /** @test */
    public function it_returns_the_available_parents_page(): void
    {
        $parents = Page::factory()->count(3)->create();
        $child = Page::factory()->create(['parent' => $parents->first()->slug]);

        self::assertIsArray(Page::getAvailableParents());
        self::assertCount(3, Page::getAvailableParents());
        self::assertArrayNotHasKey($child->slug, Page::getAvailableParents());
    }

    /** @test */
    public function it_returns_the_available_layouts(): void
    {
        Page::factory()->count(5)->create();
        Page::factory()->create(['layout' => 'service']);
        Page::factory()->create(['layout' => 'blog']);

        self::assertIsArray(Page::getAvailableLayouts());
        self::assertCount(3, Page::getAvailableLayouts());
        self::assertTrue(collect(Page::getAvailableLayouts())->values()->containsStrict('blog'));
        self::assertTrue(collect(Page::getAvailableLayouts())->values()->containsStrict('service'));
        self::assertTrue(collect(Page::getAvailableLayouts())->values()->containsStrict('basic'));
    }

    /** @test */
    public function it_belongs_to_its_author(): void
    {
        $user = User::create(['email' => 'john.doe@example.com']);
        $this->actingAs($user);

        $content = Page::factory()->raw();
        $page = Page::create($content);

        self::assertEquals($user->id, $page->user->id);
    }

    /** @test */
    public function it_may_has_media_collections(): void
    {
        $page = Page::factory()->create();

        $image = UploadedFile::fake()->image('image.png');
        $page->addMedia($image)->toMediaCollection('page.image.collection');

        self::assertSame($image->name, $page->getMedia('page.image.collection')->first()->file_name);
    }

    /** @test */
    public function it_scopes_query_with_type(): void
    {
        $page1 = Page::factory()->create();
        $page2 = Page::factory()->create(['type' => 'article']);

        self::assertEquals($page1->id, Page::withType(null)->first()->id);
        self::assertEquals($page2->id, Page::withType('article')->first()->id);
    }

    /** @test */
    public function it_scopes_query_with_slug(): void
    {
        $page1 = Page::factory()->create(['slug' => 'blog']);
        $page2 = Page::factory()->create(['slug' => 'article']);

        self::assertEquals($page1->id, Page::withSlug('blog')->first()->id);
        self::assertEquals($page2->id, Page::withSlug('article')->first()->id);
    }

    /** @test */
    public function it_scopes_query_without_slug(): void
    {
        $page1 = Page::factory()->create(['slug' => 'blog']);
        $page2 = Page::factory()->create(['slug' => 'article']);

        self::assertEquals($page1->id, Page::withoutSlug('article')->first()->id);
        self::assertEquals($page2->id, Page::withoutSlug('blog')->first()->id);
    }

    /** @test */
    public function it_has_a_default_layout(): void
    {
        $page = Page::factory()->create();

        self::assertEquals('basic', $page->fresh()->layout);
    }

    /** @test */
    public function it_has_a_default_type(): void
    {
        $page = Page::factory()->create();

        self::assertEquals('page', $page->fresh()->type);
    }

    /** @test */
    public function it_returns_the_available_types(): void
    {
        Page::factory()->count(5)->create();
        Page::factory()->create(['type' => 'service']);
        Page::factory()->create(['type' => 'blog']);

        self::assertIsArray(Page::getAvailableTypes());
        self::assertCount(3, Page::getAvailableTypes());
        self::assertTrue(collect(Page::getAvailableTypes())->values()->containsStrict('blog'));
        self::assertTrue(collect(Page::getAvailableTypes())->values()->containsStrict('service'));
        self::assertTrue(collect(Page::getAvailableTypes())->values()->containsStrict('page'));
    }
}

class TestPage extends Page
{
    use HasExcerpt;
    use HasMetaTitle;
    use HasMetaDescription;
}
