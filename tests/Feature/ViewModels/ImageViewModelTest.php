<?php

declare(strict_types=1);

namespace Lemaur\Cms\Tests\Feature\ViewModels;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Tests\TestCase;
use Lemaur\Cms\ViewModels\ImageViewModel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageViewModelTest extends TestCase
{
    private ?Media $media;
    private Page $page;
    private string $disk = 'local';
    private string $imageName = 'photo1.jpg';
    private string $mediaCollection = 'page.cover';

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake($this->disk);

        $content = Page::factory()->raw();

        $this->page = tap(Page::create($content), function ($page): void {
            $page->addMedia(UploadedFile::fake()->image($this->imageName))
                ->toMediaCollection($this->mediaCollection, $this->disk);
        });

        $this->media = $this->page->fresh()->getFirstMedia($this->mediaCollection);
    }

    /** @test */
    public function it_has_url(): void
    {
        $viewModel = new ImageViewModel($this->media);

        self::assertEquals('http://localhost/storage/1/photo1.jpg', $viewModel->url());
    }

    /** @test */
    public function it_has_alt(): void
    {
        $viewModel = new ImageViewModel($this->media);
        self::assertNull($viewModel->alt());

        $this->media->setCustomProperty('alt', 'alternative text');

        $viewModel = new ImageViewModel($this->media);
        self::assertEquals('alternative text', $viewModel->alt());
    }

    /** @test */
    public function it_has_caption(): void
    {
        $viewModel = new ImageViewModel($this->media);
        self::assertNull($viewModel->caption());

        $this->media->setCustomProperty('caption', 'caption text');

        $viewModel = new ImageViewModel($this->media);
        self::assertEquals('caption text', $viewModel->caption());
    }

    /** @test */
    public function it_has_html(): void
    {
        $this->markTestSkipped();

        $html = $this->page->toViewModel()->coverImage()->html();

        $this->assertMatchesHtmlSnapshot($html);
    }
}
