<?php

declare(strict_types=1);

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
    public function it_returns_the_homepage_if_slug_is_not_passed(): void
    {
        // Homepage will be always visible. For guests or authenticated users.
        Page::factory()->create(['slug' => ReservedSlug::HOMEPAGE]);

        $page = (new PageRepository())->find();

        self::assertInstanceOf(Page::class, $page);
        self::assertEquals(ReservedSlug::HOMEPAGE, $page->slug);
    }

    /** @test */
    public function it_returns_the_page_with_the_given_slug(): void
    {
        $slug = 'blog';
        Page::factory()->published()->create(['slug' => $slug]);

        $page = (new PageRepository())->find($slug);
        self::assertEquals($slug, $page->slug);
    }

    /** @test */
    public function it_gets_404_page_for_reserved_slug(): void
    {
        $this->expectException(NotFoundHttpException::class);

        (new PageRepository())->find(ReservedSlug::HOMEPAGE);
    }

    /** @test */
    public function it_gets_404_page_if_slug_is_not_found(): void
    {
        $this->expectException(ModelNotFoundException::class);

        (new PageRepository())->find('not-found-page');
    }

    /** @test */
    public function it_returns_only_the_published_pages_for_guest_users(): void
    {
        Auth::logout();

        Page::factory()->published()->create(['slug' => ReservedSlug::HOMEPAGE]);
        Page::factory()->create(['slug' => 'blog']);

        self::assertEquals(ReservedSlug::HOMEPAGE, (new PageRepository())->find()->slug);

        $this->expectException(ModelNotFoundException::class);
        (new PageRepository())->find('blog');
    }

    /** @test */
    public function it_returns_all_pages_for_authenticated_users(): void
    {
        Auth::login(User::create(['email' => 'me@example.com']));

        Page::factory()->published()->create(['slug' => ReservedSlug::HOMEPAGE]);
        Page::factory()->create(['slug' => 'blog']);

        self::assertEquals(ReservedSlug::HOMEPAGE, (new PageRepository())->find()->slug);
        self::assertEquals('blog', (new PageRepository())->find('blog')->slug);
    }
}
