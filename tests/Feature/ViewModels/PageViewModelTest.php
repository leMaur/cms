<?php

declare(strict_types=1);

namespace Lemaur\Cms\Tests\Feature\ViewModels;

use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\Tests\Feature\User;
use Lemaur\Cms\Tests\TestCase;
use Lemaur\Cms\ViewModels\ImageViewModel;
use Lemaur\Cms\ViewModels\PageViewModel;

class PageViewModelTest extends TestCase
{
    /** @test */
    public function it_shows_content_formatted_as_html(): void
    {
        $page = Page::factory()->create(['content' => <<<'MD'
            ## Title
            this is a paragraph with **bold** text and _italic_.
            MD]);

        $this->assertMatchesHtmlSnapshot($page->toViewModel()->content());
    }

    /** @test */
    public function it_does_not_show_content_formatted_as_html_when_is_null(): void
    {
        $page = Page::factory()->create(['content' => null]);

        self::assertNull($page->toViewModel()->content());
    }

    /** @test */
    public function it_shows_excerpt_formatted_as_html(): void
    {
        $page = Page::factory()->create(['excerpt' => <<<'MD'
            ## Title
            this is a paragraph with **bold** text and _italic_.
            MD]);

        $this->assertMatchesHtmlSnapshot($page->toViewModel()->excerpt());
    }

    /** @test */
    public function it_does_not_show_excerpt_formatted_as_html_when_is_null(): void
    {
        $page = Page::factory()->create(['excerpt' => null]);

        self::assertNull($page->toViewModel()->excerpt());
    }

    /**
     * @return array [['parent', 'slug', 'expected'], ...]
     */
    public function slugs(): array
    {
        return [
            [null, ReservedSlug::HOMEPAGE, null],
            [null, ReservedSlug::SITEMAP, 'sitemap.xml'],
            [null, 'blog', 'blog'],
            ['blog', 'article', 'blog/article'],
            [null, 'shop', 'shop'],
            ['shop', 'ebook', 'shop/ebook'],
            ['shop/ebook', 'coding-with-laravel', 'shop/ebook/coding-with-laravel'],
        ];
    }

    /**
     * @test
     * @dataProvider slugs
     */
    public function it_shows_slug($parent, $slug, $expected): void
    {
        $page = Page::factory()->create(['parent' => $parent, 'slug' => $slug]);
        self::assertEquals($expected, $page->toViewModel()->slug());
    }

    /**
     * @test
     * * @dataProvider slugs
     */
    public function it_shows_url($parent, $slug, $expected): void
    {
        $page = Page::factory()->create(['parent' => $parent, 'slug' => $slug]);
        self::assertEquals(trim('http://localhost/'.$expected, '/'), $page->toViewModel()->url());
    }

    /** @test */
    public function it_may_has_not_children(): void
    {
        $parent = Page::factory()->create();

        self::assertNull($parent->toViewModel()->children());
    }

    /** @test */
    public function it_may_has_children(): void
    {
        $this->actingAs(User::create(['name' => 'John', 'email' => 'john@example.com']));

        $parent = Page::factory()->published()->create(['slug' => 'blog']);
        $drafts = Page::factory(5)->create(['parent' => 'blog']);
        $published = Page::factory(5)->published()->create(['parent' => 'blog']);

        self::assertInstanceOf(LengthAwarePaginator::class, $parent->fresh()->toViewModel()->children());
        self::assertEquals(10, $parent->toViewModel()->children()->total());
        self::assertNotContains($parent, $published);
        self::assertNotContains($parent, $drafts);
    }

    /** @test */
    public function it_may_has__only_published_children(): void
    {
        $parent = Page::factory()->published()->create(['slug' => 'blog']);
        $drafts = Page::factory(5)->create(['parent' => 'blog']);
        $published = Page::factory(5)->published()->create(['parent' => 'blog']);

        self::assertInstanceOf(LengthAwarePaginator::class, $parent->fresh()->toViewModel()->children());
        self::assertEquals(5, $parent->toViewModel()->children()->total());
        self::assertNotContains($parent, $published);
        self::assertNotContains($parent, $drafts);
    }

    /** @test */
    public function it_may_has_a_cover_image(): void
    {
        Storage::fake('local');
        $image = UploadedFile::fake()->image('photo1.jpg');

        $page = Page::factory()->create();
        self::assertNull($page->toViewModel()->coverImage());

        $page->addMedia($image)
            ->withCustomProperties([
                'alt' => 'alternative text',
                'caption' => 'caption text',
            ])
            ->toMediaCollection('page.cover', 'local');

        $coverImage = $page->fresh()->toViewModel()->coverImage();

        self::assertInstanceOf(ImageViewModel::class, $coverImage);

        self::assertEquals('http://localhost/storage/1/photo1.jpg', $coverImage->url());
        self::assertEquals('alternative text', $coverImage->alt());
        self::assertEquals('caption text', $coverImage->caption());
    }

    /** @test */
    public function it_has_meta_tags(): void
    {
        Storage::fake('local');

        tap(Page::factory()->published()->create([
            'title' => 'About',
            'slug' => 'about',
            'content' => "## About me\nparagraph with some **bold** and _italic_ text",
        ]), static function ($page) {
            $page->addMedia(UploadedFile::fake()->image('photo1.jpg'))
                ->withCustomProperties([
                    'alt' => 'alternative text',
                    'caption' => 'caption text',
                ])
                ->toMediaCollection('page.cover', 'local');
        });

        $html = $this->get('/about')->content();

        $this->assertMatchesHtmlSnapshot($html);
    }

    /**
     * @test
     */
    public function it_has_title(): void
    {
        $page = Page::factory()->create();

        self::assertEquals($page->title, $page->toViewModel()->title());
    }

    /**
     * @test
     */
    public function it_has_parent(): void
    {
        $parent = Page::factory()->create(['slug' => 'blog']);
        self::assertNull($parent->toViewModel()->parent());

        $page = Page::factory()->create(['parent' => 'blog']);
        self::assertInstanceOf(PageViewModel::class, $page->toViewModel()->parent());
        self::assertEquals($parent->title, $page->toViewModel()->parent()->title());
    }

    /**
     * @test
     */
    public function it_has_type(): void
    {
        $page = Page::factory()->create();

        self::assertEquals($page->type, $page->toViewModel()->type());
    }

    /**
     * @test
     */
    public function it_has_layout(): void
    {
        $page = Page::factory()->create();

        self::assertEquals($page->layout, $page->toViewModel()->layout());
    }
}
