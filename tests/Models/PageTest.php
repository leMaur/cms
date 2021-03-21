<?php

namespace Lemaur\Cms\Tests\Models;

use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Tests\TestCase;
use Ramsey\Uuid\Uuid;

class PageTest extends TestCase
{
    /** @test */
    public function it_has_autogenerated_uuid()
    {
        $page = Page::create([
            'title' => 'My new Page',
        ]);

        $this->assertTrue(Uuid::isValid($page->uuid));
    }

    /** @test */
    public function it_has_autogenerated_slug_from_title()
    {
        Page::create([
            'title' => 'My new Page',
        ]);

        $this->assertDatabaseHas('pages', [
            'title' => 'My new Page',
            'slug' => 'my-new-page',
        ]);
    }

    /** @test */
    public function it_may_has_extra_attributes()
    {
        $page = Page::factory()->create();

        // Set extra attribute
        $page->setExtraAttribute('meta', [
            'title' => 'Meta Title',
            'description' => 'Meta Description',
        ]);

        // Get extra attributes
        $this->assertEquals([
            'title' => 'Meta Title',
            'description' => 'Meta Description',
        ], $page->getExtraAttribute('meta'));

        // Has extra attribute
        $this->assertTrue($page->hasExtraAttribute('meta'));

        // Forget extra attribute
        $page->forgetExtraAttribute('meta');

        $this->assertFalse($page->hasExtraAttribute('meta'));

        // Fill extra attributes
        $page->fillExtraAttributes([
            'meta' => [
                'title' => 'Meta Title',
                'description' => 'Meta Description',
            ]
        ]);

        $this->assertEquals([
            'title' => 'Meta Title',
            'description' => 'Meta Description',
        ], $page->getExtraAttribute('meta'));
    }
}
