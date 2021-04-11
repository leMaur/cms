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
        $slug = ReservedSlug::toSlug('@home');

        self::assertEquals('/', $slug);
    }

    /** @test */
    public function it_returns_the_slug_itself_if_the_reserved_slug_is_not_found()
    {
        $slug = ReservedSlug::toSlug('about');

        self::assertEquals('about', $slug);
    }

    /** @test */
    public function it_returns_the_reserved_value_by_slug()
    {
        self::assertEquals('@home', ReservedSlug::find('/'));
        self::assertEquals('@sitemap', ReservedSlug::find('sitemap.xml'));
        self::assertEquals('about', ReservedSlug::find('about'));
        self::assertEquals('first/second/third', ReservedSlug::find('first/second/third'));
    }

    /** @test */
    public function it_checks_if_the_given_slug_is_reserved(): void
    {
        self::assertTrue(ReservedSlug::isReserved('@home'));
        self::assertTrue(ReservedSlug::isReserved('@sitemap'));
        self::assertFalse(ReservedSlug::isReserved('about'));
        self::assertFalse(ReservedSlug::isReserved('first/second/third'));
    }
}
