<?php

declare(strict_types=1);

namespace Lemaur\Cms\Tests\Feature\Http\Middleware;

use Illuminate\Support\Facades\Route;
use Illuminate\Testing\TestResponse;
use Lemaur\Cms\Http\Middleware\BlockIndexing;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\Tests\TestCase;

class BlockIndexingTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        TestResponse::macro('assertRobotsAreBlocked', function () {
            $this
                ->assertSuccessful()
                ->assertHeader('X-Robots-Tag', 'noindex')
                ->assertSee('ok');

            return $this;
        });

        TestResponse::macro('assertRobotsAreNotBlocked', function () {
            $this
                ->assertSuccessful()
                ->assertHeaderMissing('X-Robots-Tag')
                ->assertSee('ok');

            return $this;
        });

        Page::factory()->published()->create([
            'title' => 'Welcome',
            'slug' => ReservedSlug::HOMEPAGE,
            'content' => 'ok',
        ]);
    }

    /** @test */
    public function it_blocks_pages_from_being_indexed(): void
    {
        config()->set('cms.seo.block_indexing', true);

        $this
            ->get(route('cms'))
            ->assertRobotsAreBlocked();
    }

    /** @test */
    public function it_does_not_block_pages_from_being_indexed(): void
    {
        config()->set('cms.seo.block_indexing', false);

        $this
            ->get(route('cms'))
            ->assertRobotsAreNotBlocked();
    }
}
