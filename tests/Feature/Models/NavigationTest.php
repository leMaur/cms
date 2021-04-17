<?php

namespace Lemaur\Cms\Tests\Feature\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
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

    /** @test */
    public function it_may_has_a_single_image()
    {
        $page = Page::factory()->create();

        $navigation = new Navigation();
        $navigation->page()->associate($page);
        $navigation->type = Navigation::PRIMARY;
        $navigation->save();

        $image = UploadedFile::fake()->image('image.png');
        $navigation->addMedia($image)->toMediaCollection('navigation.image');

        self::assertSame($image->name, $navigation->getMedia('navigation.image')->first()->file_name);
    }

    /** @test */
    public function it_scopes_query_by_type(): void
    {
        $primary = Navigation::create(['name' => 'Primary', 'type' => 'primary']);
        $secondary = Navigation::create(['name' => 'Secondary', 'type' => 'secondary']);
        $social = Navigation::create(['name' => 'Social', 'type' => 'social']);

        self::assertEquals($primary->id, Navigation::withType('primary')->first()->id);
        self::assertEquals($secondary->id, Navigation::withType('secondary')->first()->id);
        self::assertEquals($social->id, Navigation::withType('social')->first()->id);
    }
}
