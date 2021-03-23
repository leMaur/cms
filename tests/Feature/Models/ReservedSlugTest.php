<?php

namespace Lemaur\Cms\Tests\Feature\Models;

use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\Tests\TestCase;

class ReservedSlugTest extends TestCase
{
    /** @test */
    public function it_fetches_a_list_of_all_reserved_slug()
    {
        $list = ReservedSlug::list();

        $this->assertArrayHasKey('homepage', $list);
        $this->assertArrayHasKey('sitemap', $list);
    }
}
