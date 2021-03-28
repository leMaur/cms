<?php

namespace Lemaur\Cms\Tests\Feature\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lemaur\Cms\Models\Navigation;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Tests\TestCase;

class NavigationTest extends TestCase
{
    /** @test */
    public function it_may_belongs_to_a_page()
    {
        $page = Page::factory()->create();

        $navigation = new Navigation();
        $navigation->page()->associate($page);
        $navigation->type = Navigation::PRIMARY;
        $navigation->save();

        $this->assertInstanceOf(BelongsTo::class, $navigation->page());
        $this->assertInstanceOf(Page::class, $navigation->page);
        $this->assertDatabaseHas('navigations', [
            'page_id' => $page->id, 'type' => Navigation::PRIMARY,
        ]);
    }
}
