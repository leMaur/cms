<?php

namespace Lemaur\Cms\Tests\Feature\Http\Controllers;

use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\Tests\TestCase;

class PageControllerTest extends TestCase
{
    /** @test */
    public function it_shows_homepage()
    {
        Page::factory()->create([
            'title' => 'Welcome',
            'slug' => ReservedSlug::HOMEPAGE,
            'content' => 'Hey there!',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Hey there!');
    }

    /** @test */
    public function it_shows_nested_page()
    {
        Page::factory()->published()->create(['slug' => 'blog']);
        Page::factory()->published()->create(['parent' => 'blog', 'slug' => 'article', 'content' => 'Once upon a time...']);

        $this->get('/blog/article')
            ->assertOk()
            ->assertSee('Once upon a time...');
    }

    /** @test */
    public function it_shows_a_third_level_nested_page()
    {
        Page::factory()->published()->create(['slug' => 'first']);
        Page::factory()->published()->create(['parent' => 'first', 'slug' => 'second']);
        Page::factory()->published()->create(['parent' => 'first/second', 'slug' => 'third', 'content' => 'Hello world!']);

        $this->get('/first/second/third')
            ->assertOk()
            ->assertSee('Hello world!');
    }

    /** @test */
    public function it_shows_404_if_page_not_found()
    {
        $this->get('/page-not-found')->assertNotFound();

        Page::factory()->create([
            'title' => 'Sitemap',
            'slug' => ReservedSlug::SITEMAP,
            'layout' => 'sitemap_index',
        ]);

        $this->get('/sitemaps/sitemap-not-found')->assertNotFound();
    }

    /** @test */
    public function it_shows_sitemap_index()
    {
        Page::factory()->count(5)->create();

        Page::factory()->create(['slug' => 'blog']);
        Page::factory()->count(5)->create(['type' => 'article', 'parent' => 'blog']);

        Page::factory()->create(['slug' => 'services-shop']);
        Page::factory()->count(5)->create(['type' => 'service', 'parent' => 'services-shop']);

        Page::factory()->create([
            'title' => 'Sitemap',
            'slug' => ReservedSlug::SITEMAP,
            'layout' => 'sitemap_index',
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/xml; charset=UTF-8')
            ->assertSee('http://localhost/sitemaps/sitemap-articles.xml')
            ->assertSee('http://localhost/sitemaps/sitemap-pages.xml')
            ->assertSee('http://localhost/sitemaps/sitemap-services.xml');
    }

    /** @test */
    public function it_shows_the_given_sitemap()
    {
        $this->withoutExceptionHandling();
        $pages = Page::factory()->count(5)->create();

        Page::factory()->create(['slug' => 'blog']);
        Page::factory()->count(5)->create(['type' => 'article', 'parent' => 'blog']);

        Page::factory()->create(['slug' => 'services-shop']);
        Page::factory()->count(5)->create(['type' => 'service', 'parent' => 'services-shop']);

        Page::factory()->create([
            'title' => 'Sitemap',
            'slug' => ReservedSlug::SITEMAP,
            'layout' => 'sitemap_index',
        ]);
        $this->get('/sitemaps/sitemap_pages.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/xml; charset=UTF-8')
            ->assertSee($pages->get(0)->slug)
            ->assertSee($pages->get(1)->slug)
            ->assertSee($pages->get(2)->slug)
            ->assertSee($pages->get(3)->slug)
            ->assertSee($pages->get(4)->slug);
    }
}
