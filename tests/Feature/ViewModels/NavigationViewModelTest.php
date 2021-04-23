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
        $viewModel = new NavigationViewModel($this->navi);

        self::assertEquals('About', $viewModel->name());
    }

    /** @test */
    public function it_has_name_taken_from_relation(): void
    {
        $navi = Navigation::factory()->forPage(['title' => 'About'])->create();

        $viewModel = new NavigationViewModel($navi);

        self::assertEquals('About', $viewModel->name());
    }

    /** @test */
    public function it_has_url(): void
    {
        $viewModel = new NavigationViewModel($this->navi);

        self::assertEquals('http://localhost/about', $viewModel->url());
    }

    /** @test */
    public function it_has_url_taken_from_relation(): void
    {
        $navi = Navigation::factory()->forPage(['slug' => 'about'])->create();

        $viewModel = new NavigationViewModel($navi);

        self::assertEquals('http://localhost/about', $viewModel->url());
    }

    /** @test */
    public function it_may_has_image(): void
    {
        Storage::fake('local');

        $viewModel = new NavigationViewModel($this->navi);

        self::assertNull($viewModel->image());

        $this->navi
            ->addMedia(UploadedFile::fake()->image('photo1.jpg'))
            ->toMediaCollection('navigation.image', 'local');

        $viewModel = new NavigationViewModel($this->navi->fresh());

        self::assertInstanceOf(ImageViewModel::class, $viewModel->image());
        self::assertEquals('http://localhost/storage/1/photo1.jpg', $viewModel->image()->url());
    }
}
