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
        Page::factory()->create(['slug' => 'blog']);
        Page::factory()->create(['parent' => 'blog', 'slug' => 'article', 'content' => 'Once upon a time...']);

        $this->get('/blog/article')
            ->assertOk()
            ->assertSee('Once upon a time...');
    }

    /** @test */
    public function it_shows_a_third_level_nested_page()
    {
        Page::factory()->create(['slug' => 'first']);
        Page::factory()->create(['parent' => 'first', 'slug' => 'second']);
        Page::factory()->create(['parent' => 'first/second', 'slug' => 'third', 'content' => 'Hello world!']);

        $this->get('/first/second/third')
            ->assertOk()
            ->assertSee('Hello world!');
    }

    /** @test */
    public function it_shows_404_if_page_not_found()
    {
        $this->get('/page-not-found')->assertNotFound();
    }
}
