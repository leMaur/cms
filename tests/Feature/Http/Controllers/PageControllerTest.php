<?php

namespace Lemaur\Cms\Tests\Feature\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
    public function it_shows_meta_information()
    {
        Page::factory()->published()->create([
            'title' => 'My Title',
            'slug' => 'my-title',
            'content' => 'Something to say',
        ]);

        $this->assertMatchesHtmlSnapshot(
            $this->get('/my-title')->content()
        );
    }

    /** @test */
    public function it_shows_sitemap_index()
    {
        collect([
            ['title' => 'Welcome', 'slug' => ReservedSlug::HOMEPAGE],

            ['title' => 'Blog'],
            ['title' => 'Article 1', 'layout' => 'article', 'type' => 'article', 'parent' => 'blog'],
            ['title' => 'Article 2', 'layout' => 'article', 'type' => 'article', 'parent' => 'blog'],
            ['title' => 'Article 3', 'layout' => 'article', 'type' => 'article', 'parent' => 'blog'],

            ['title' => 'Services Shop'],
            ['title' => 'Project', 'layout' => 'service', 'type' => 'service', 'parent' => 'services-shop'],
            ['title' => 'Videocall', 'layout' => 'service', 'type' => 'service', 'parent' => 'services-shop'],
            ['title' => 'Editorial', 'layout' => 'service', 'type' => 'service', 'parent' => 'services-shop'],
            ['title' => 'Planner 2021 Printable', 'layout' => 'shop', 'type' => 'service', 'parent' => 'services-shop'],

            ['title' => 'About'],
            ['title' => 'Contact'],
            ['title' => 'Biophilic Design Guide'],
            ['title' => 'Ethical Manifesto'],
            ['title' => 'Press'],
            ['title' => 'Privacy Policy'],
            ['title' => 'Cookie Policy'],
            ['title' => 'Terms of Service'],

            ['title' => 'Sitemap', 'slug' => ReservedSlug::SITEMAP, 'layout' => 'sitemap_index'],

        ])->each(fn ($page) => Page::create(collect($page)->merge(['published_at' => now()])->toArray()));

        $this->assertMatchesXmlSnapshot(
            $this->get('/sitemap.xml')->content()
        );
    }

    /** @test */
    public function it_shows_the_given_sitemap()
    {
        Storage::fake('local');

        collect([
            ['title' => 'Welcome', 'slug' => ReservedSlug::HOMEPAGE],

            ['title' => 'Blog'],
            ['title' => 'Article 1', 'layout' => 'article', 'type' => 'article', 'parent' => 'blog'],
            ['title' => 'Article 2', 'layout' => 'article', 'type' => 'article', 'parent' => 'blog'],
            ['title' => 'Article 3', 'layout' => 'article', 'type' => 'article', 'parent' => 'blog'],

            ['title' => 'Services Shop'],
            ['title' => 'Project', 'layout' => 'service', 'type' => 'service', 'parent' => 'services-shop'],
            ['title' => 'Videocall', 'layout' => 'service', 'type' => 'service', 'parent' => 'services-shop'],
            ['title' => 'Editorial', 'layout' => 'service', 'type' => 'service', 'parent' => 'services-shop'],
            ['title' => 'Planner 2021 Printable', 'layout' => 'shop', 'type' => 'service', 'parent' => 'services-shop'],

            ['title' => 'About'],
            ['title' => 'Contact'],
            ['title' => 'Biophilic Design Guide'],
            ['title' => 'Ethical Manifesto'],
            ['title' => 'Press'],
            ['title' => 'Privacy Policy'],
            ['title' => 'Cookie Policy'],
            ['title' => 'Terms of Service'],

            ['title' => 'Sitemap', 'slug' => ReservedSlug::SITEMAP, 'layout' => 'sitemap_index'],

        ])->each(fn ($page) => Page::create(collect($page)->merge(['published_at' => now()])->toArray()));

        Page::where('layout', 'article')->orderBy('id')->get()->each(static function (Page $page): void {
            $page->addMedia(UploadedFile::fake()->image('photo1.jpg'))
                ->withCustomProperties([
                    'alt_text' => 'alternative text',
                    'caption' => 'caption text',
                ])
                ->toMediaCollection('page.cover', 'local');
        });

        $this->travel(3)->years();

        $this->assertMatchesXmlSnapshot(
            $this->get('/sitemaps/sitemap-articles.xml')->content()
        );

        $this->assertMatchesXmlSnapshot(
            $this->get('/sitemaps/sitemap-pages.xml')->content()
        );

        $this->assertMatchesXmlSnapshot(
            $this->get('/sitemaps/sitemap-services.xml')->content()
        );
    }
}
