<?php

declare(strict_types=1);

namespace Lemaur\Cms\Tests\Feature\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\Tests\TestCase;
use Spatie\Sitemap\Tags\Url;

class PageControllerTest extends TestCase
{
    /** @test */
    public function it_shows_homepage(): void
    {
        Page::factory()->published()->create([
            'title' => 'Welcome',
            'slug' => ReservedSlug::HOMEPAGE,
            'content' => 'Hey there!',
        ]);

        $this->get(route('cms'))
            ->assertOk()
            ->assertSee('Hey there!');
    }

    /** @test */
    public function it_shows_nested_page(): void
    {
        Page::factory()->published()->create(['slug' => 'blog']);
        Page::factory()->published()->create(['parent' => 'blog', 'slug' => 'article', 'content' => 'Once upon a time...']);

        $this->get(route('cms', '/blog/article'))
            ->assertOk()
            ->assertSee('Once upon a time...');
    }

    /** @test */
    public function it_shows_a_third_level_nested_page(): void
    {
        Page::factory()->published()->create(['slug' => 'first']);
        Page::factory()->published()->create(['parent' => 'first', 'slug' => 'second']);
        Page::factory()->published()->create(['parent' => 'first/second', 'slug' => 'third', 'content' => 'Hello world!']);

        $this->get(route('cms', '/first/second/third'))
            ->assertOk()
            ->assertSee('Hello world!');
    }

    /** @test */
    public function it_shows_404_if_page_not_found(): void
    {
        $this->get(route('cms', '/page-not-found'))->assertNotFound();

        Page::factory()->create([
            'title' => 'Sitemap',
            'slug' => ReservedSlug::SITEMAP,
            'layout' => 'sitemap_index',
        ]);

        $this->get('/sitemaps/sitemap-not-found')->assertNotFound();
    }

    /** @test */
    public function it_shows_meta_information(): void
    {
        Storage::fake('local');

        $page = Page::factory()->published()->create([
            'title' => 'My Title',
            'slug' => 'my-title',
            'content' => 'Something to say',
        ]);

        $page->addMedia(UploadedFile::fake()->image('photo1.jpg'))
            ->withCustomProperties([
                'alt' => 'alternative text',
                'caption' => 'caption text',
            ])
            ->toMediaCollection('page.cover', 'local');

        $this->assertMatchesHtmlSnapshot(
            $this->get(route('cms', '/my-title'))->content()
        );
    }

    /** @test */
    public function it_shows_sitemap_index(): void
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
            ['title' => 'Ethical Manifesto'],
            ['title' => 'Press'],
            ['title' => 'Privacy Policy'],
            ['title' => 'Cookie Policy'],
            ['title' => 'Terms of Service'],

            ['title' => 'Sitemap', 'slug' => ReservedSlug::SITEMAP, 'layout' => 'sitemap_index'],

        ])->each(fn ($page) => Page::create(collect($page)->merge(['published_at' => now()])->toArray()));

        $this->assertMatchesXmlSnapshot(
            $this->get(route('cms', '/sitemap.xml'))->content()
        );
    }

    /** @test */
    public function it_shows_the_given_sitemap(): void
    {
        Storage::fake('local');

        collect([
            ['title' => 'Welcome', 'slug' => ReservedSlug::HOMEPAGE],

            ['title' => 'Blog', 'sitemap_frequency' => Url::CHANGE_FREQUENCY_WEEKLY, 'sitemap_priority' => 0.9],
            ['title' => 'Article 1', 'layout' => 'article', 'type' => 'article', 'parent' => 'blog', 'sitemap_priority' => 0.9],
            ['title' => 'Article 2', 'layout' => 'article', 'type' => 'article', 'parent' => 'blog', 'sitemap_priority' => 0.9],
            ['title' => 'Article 3', 'layout' => 'article', 'type' => 'article', 'parent' => 'blog', 'sitemap_priority' => 0.9],

            ['title' => 'Services Shop', 'sitemap_priority' => 1.0],
            ['title' => 'Project', 'layout' => 'service', 'type' => 'service', 'parent' => 'services-shop', 'sitemap_priority' => 1.0],
            ['title' => 'Videocall', 'layout' => 'service', 'type' => 'service', 'parent' => 'services-shop', 'sitemap_priority' => 1.0],
            ['title' => 'Editorial', 'layout' => 'service', 'type' => 'service', 'parent' => 'services-shop', 'sitemap_priority' => 1.0],

            ['title' => 'About', 'sitemap_frequency' => 'not-existent-frequency-fallback-to-yearly'],
            ['title' => 'Contact'],
            ['title' => 'Ethical Manifesto', 'sitemap_priority' => 0.8],
            ['title' => 'Press', 'sitemap_priority' => 0.8],
            ['title' => 'Privacy Policy', 'sitemap_priority' => 0.2],
            ['title' => 'Cookie Policy', 'sitemap_priority' => 0.2],
            ['title' => 'Terms of Service', 'sitemap_priority' => 0.2],

            ['title' => 'Sitemap', 'slug' => ReservedSlug::SITEMAP, 'layout' => 'sitemap_index'],

        ])->each(fn ($page) => Page::create(collect($page)->merge(['published_at' => now()])->toArray()));

        Page::where('layout', 'article')->orderBy('id')->get()->each(static function (Page $page): void {
            $page->addMedia(UploadedFile::fake()->image('photo1.jpg'))
                ->withCustomProperties([
                    'alt' => 'alternative text',
                    'caption' => 'caption text',
                ])
                ->toMediaCollection('page.cover', 'local');
        });

        Carbon::setTestNow($this->now->addYears(3));

        $this->assertMatchesXmlSnapshot(
            $this->get(route('cms', '/sitemaps/sitemap-articles.xml'))->content()
        );

        $this->assertMatchesXmlSnapshot(
            $this->get(route('cms', '/sitemaps/sitemap-pages.xml'))->content()
        );

        $this->assertMatchesXmlSnapshot(
            $this->get(route('cms', '/sitemaps/sitemap-services.xml'))->content()
        );
    }
}
