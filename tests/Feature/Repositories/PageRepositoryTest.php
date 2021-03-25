<?php

namespace Lemaur\Cms\Tests\Feature\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\Repositories\PageRepository;
use Lemaur\Cms\Tests\Feature\User;
use Lemaur\Cms\Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageRepositoryTest extends TestCase
{
    /** @test */
    public function it_returns_the_homepage_if_slug_is_not_passed()
    {
        // Homepage will be always visible, guests or authenticated users.
        Page::factory()->create(['slug' => ReservedSlug::HOMEPAGE]);

        $page = (new PageRepository(new Page()))->find();

        $this->assertInstanceOf(Page::class, $page);
        $this->assertEquals(ReservedSlug::HOMEPAGE, $page->slug);
    }

    /** @test */
    public function it_returns_the_page_with_the_given_slug()
    {
        $slug = 'blog';
        Page::factory()->published()->create(['slug' => $slug]);

        $page = (new PageRepository(new Page()))->find($slug);

        $this->assertInstanceOf(Page::class, $page);
        $this->assertEquals($slug, $page->slug);
    }

    /** @test */
    public function it_gets_404_page_for_reserved_slug()
    {
        $this->expectException(NotFoundHttpException::class);

        (new PageRepository(new Page()))->find(ReservedSlug::HOMEPAGE);
    }

    /** @test */
    public function it_gets_404_page_if_slug_is_not_found()
    {
        $this->expectException(ModelNotFoundException::class);

        (new PageRepository(new Page()))->find('not-found-page');
    }

    /** @test */
    public function it_returns_only_the_published_pages_for_guest_users()
    {
        Page::factory()->published()->create(['slug' => ReservedSlug::HOMEPAGE]);
        $page = (new PageRepository(new Page()))->find();
        $this->assertEquals(ReservedSlug::HOMEPAGE, $page->slug);

        $this->expectException(ModelNotFoundException::class);
        Page::factory()->create(['slug' => 'blog']);
        (new PageRepository(new Page()))->find('blog');
    }

    /** @test */
    public function it_returns_all_pages_for_authenticated_users()
    {
        Page::factory()->published()->create(['slug' => ReservedSlug::HOMEPAGE]);

        $page = (new PageRepository(new Page()))->find();
        $this->assertEquals(ReservedSlug::HOMEPAGE, $page->slug);

        Auth::login(User::create(['email' => 'me@example.com']));

        Page::factory()->create(['slug' => 'blog']);

        $page = (new PageRepository(new Page()))->find('blog');
        $this->assertEquals('blog', $page->slug);
    }
}
