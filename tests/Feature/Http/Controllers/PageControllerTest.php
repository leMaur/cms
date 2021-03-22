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
        Page::factory()->create([
            'slug' => 'blog',
        ]);

        Page::factory()->create([
            'parent' => 'blog',
            'slug' => 'article',
            'content' => 'Once upon a time...',
        ]);

        $this->get('/blog/article')
            ->assertOk()
            ->assertSee('Once upon a time...');
    }

    /** @test */
    public function it_shows_a_third_level_nested_page()
    {
        Page::factory()->create([
            'slug' => 'page-1',
        ]);

        Page::factory()->create([
            'parent' => 'page-1',
            'slug' => 'page-2',
        ]);

        Page::factory()->create([
            'parent' => 'page-1/page-2',
            'slug' => 'page-3',
            'content' => 'Hello world!',
        ]);

        $this->get('/page-1/page-2/page-3')
            ->assertOk()
            ->assertSee('Hello world!');
    }

    /** @test */
    public function it_shows_404_if_no_page_match()
    {
        $this->get('/no-page')
            ->assertNotFound();
    }

    /** @test */
    public function it_shows_page_by_its_uuid()
    {
        $page = Page::factory()->create([
            'slug' => 'about',
            'content' => 'Hello world!',
        ]);

        $this->get($page->uuid)
            ->assertOk()
            ->assertSee('Hello world!');
    }
}
