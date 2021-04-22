<?php

namespace Lemaur\Cms\Tests\Feature\Models\ViewModels;

use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\Models\ViewModels\ImageViewModel;
use Lemaur\Cms\Tests\TestCase;

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
            [null, 'services-shop', 'services-shop'],
            ['services-shop', 'ebook', 'services-shop/ebook'],
            ['services-shop/ebook', 'biophilia', 'services-shop/ebook/biophilia'],
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
        self::assertEquals(trim('https://localhost/'.$expected, '/'), $page->toViewModel()->url());
    }

    /** @test */
    public function it_may_has_children(): void
    {
        $parent = Page::factory()->create(['slug' => 'blog']);
        self::assertNull($parent->toViewModel()->children());

        $children = Page::factory(5)->create(['parent' => 'blog']);

        self::assertInstanceOf(LengthAwarePaginator::class, $parent->toViewModel()->children());
        self::assertEquals(5, $parent->toViewModel()->children()->total());
        self::assertNotContains($parent, $children);
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
}
