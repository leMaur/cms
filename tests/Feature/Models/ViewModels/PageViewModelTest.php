<?php

namespace Lemaur\Cms\Tests\Feature\Models\ViewModels;

use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Tests\TestCase;

class PageViewModelTest extends TestCase
{
    /** @test */
    public function it_shows_content_formatted_as_html(): void
    {
        $markdown = <<<'MD'
## Title
this is a paragraph with **bold** text and _italic_.
MD;

        $page = Page::factory()->create(['content' => $markdown]);
        $viewModel = $page->toViewModel();

        self::assertEquals(<<<'HTML'
<h2>Title</h2>
<p>this is a paragraph with <strong>bold</strong> text and <em>italic</em>.</p>

HTML
        , $viewModel->content());
    }

    /** @test */
    public function it_shows_excerpt_formatted_as_html(): void
    {
        $markdown = <<<'MD'
## Title
this is a paragraph with **bold** text and _italic_.
MD;

        $page = Page::factory()->create(['excerpt' => $markdown]);
        $viewModel = $page->toViewModel();

        self::assertEquals(<<<'HTML'
<h2>Title</h2>
<p>this is a paragraph with <strong>bold</strong> text and <em>italic</em>.</p>

HTML
        , $viewModel->excerpt());
    }

    /** @test */
    public function it_checks_if_has_cover_image()
    {
        self::markTestSkipped();
    }

    /** @test */
    public function it_shows_cover_image()
    {
        self::markTestSkipped();
    }

    /** @test */
    public function it_shows_media_library()
    {
        self::markTestSkipped();
    }

    /** @test */
    public function it_shows_meta_information()
    {
        self::markTestSkipped();
    }

    /** @test */
    public function it_shows_slug()
    {
        $page = Page::factory()->create(['slug' => 'blog']);
        self::assertEquals('blog', $page->toViewModel()->slug());

        $page = Page::factory()->create(['parent' => 'blog', 'slug' => 'article']);
        self::assertEquals('blog/article', $page->toViewModel()->slug());
    }

    /** @test */
    public function it_shows_url()
    {
        $page = Page::factory()->create(['slug' => 'blog']);
        self::assertEquals('https://localhost/blog', $page->toViewModel()->url());

        $page = Page::factory()->create(['parent' => 'blog', 'slug' => 'article']);
        self::assertEquals('https://localhost/blog/article', $page->toViewModel()->url());
    }

    /** @test */
    public function it_checks_if_has_children_pages()
    {
        self::markTestSkipped();
    }

    /** @test */
    public function it_shows_children_pages()
    {
        self::markTestSkipped();
    }
}
