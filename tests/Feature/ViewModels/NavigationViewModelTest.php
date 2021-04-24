<?php

namespace Lemaur\Cms\Tests\Feature\ViewModels;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Lemaur\Cms\Models\Navigation;
use Lemaur\Cms\Tests\TestCase;
use Lemaur\Cms\ViewModels\ImageViewModel;
use Lemaur\Cms\ViewModels\NavigationViewModel;

class NavigationViewModelTest extends TestCase
{
    private Navigation $navi;

    public function setUp(): void
    {
        parent::setUp();

        $this->navi = Navigation::factory()->create([
            'name' => 'About',
            'url' => 'http://localhost/about',
        ]);
    }

    /** @test */
    public function it_has_name(): void
    {
        self::assertEquals('About', $this->navi->toViewModel()->name());
    }

    /** @test */
    public function it_has_name_taken_from_relation(): void
    {
        $navi = Navigation::factory()->forPage(['title' => 'About'])->create();

        self::assertEquals('About', $navi->toViewModel()->name());
    }

    /** @test */
    public function it_has_url(): void
    {
        self::assertEquals('http://localhost/about', $this->navi->toViewModel()->url());
    }

    /** @test */
    public function it_has_url_taken_from_relation(): void
    {
        $navi = Navigation::factory()->forPage(['slug' => 'about'])->create();

        self::assertEquals('http://localhost/about', $navi->toViewModel()->url());
    }

    /** @test */
    public function it_may_has_image(): void
    {
        Storage::fake('local');

        self::assertNull($this->navi->toViewModel()->image());

        $this->navi
            ->addMedia(UploadedFile::fake()->image('photo1.jpg'))
            ->toMediaCollection('navigation.image', 'local');

        $viewModel = new NavigationViewModel($this->navi->fresh());

        self::assertInstanceOf(ImageViewModel::class, $viewModel->image());
        self::assertEquals('http://localhost/storage/1/photo1.jpg', $viewModel->image()->url());
    }
}
