<?php

namespace Lemaur\Cms\Tests\Feature\Models;

use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\Tests\TestCase;

class ReservedSlugTest extends TestCase
{
    /** @test */
    public function it_fetches_a_list_of_all_reserved_slug(): void
    {
        $list = ReservedSlug::list();

        self::assertArrayHasKey('@home', $list);
        self::assertArrayHasKey('@sitemap', $list);
    }

    /** @test */
    public function it_gets_the_slug_associated_with_the_reserved_slug()
    {
        $slug = ReservedSlug::handle('@home');

        self::assertEquals('/', $slug);
    }

    /** @test */
    public function it_returns_the_slug_itself_if_the_reserved_slug_is_not_found()
    {
        $slug = ReservedSlug::handle('about');

        self::assertEquals('about', $slug);
    }
}
