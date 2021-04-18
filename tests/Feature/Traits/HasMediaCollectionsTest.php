<?php

namespace Lemaur\Cms\Tests\Feature\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Tests\TestCase;
use Lemaur\Cms\Traits\HasMediaCollections;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\Support\ImageFactory;

class HasMediaCollectionsTest extends TestCase
{
    /** @test */
    public function it_thrown_an_exception_if_property_is_not_set(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new TestModel())->getRegisteredMediaCollections();
    }

    /** @test */
    public function it_has_single_image(): void
    {
        $config = config('cms.media.single_image');
        $mediaCollections = (new TestMediaModel())->getRegisteredMediaCollections();
        $index = 0;

        self::assertCount(5, $mediaCollections);
        self::assertEquals('testmediamodel.cover', $mediaCollections->get($index)->name);
        self::assertEquals(data_get($config, 'only_keep_latest'), $mediaCollections->get($index)->collectionSizeLimit);
        self::assertTrue($mediaCollections->get($index)->singleFile);
        self::assertIsArray($mediaCollections->get($index)->acceptsMimeTypes);
        self::assertSame(data_get($config, 'accepts_mime_types'), $mediaCollections->get($index)->acceptsMimeTypes);
    }

    /** @test */
    public function it_has_image_collection(): void
    {
        $config = config('cms.media.multiple_images');
        $mediaCollections = (new TestMediaModel())->getRegisteredMediaCollections();
        $index = 1;

        self::assertCount(5, $mediaCollections);
        self::assertEquals('testmediamodel.images', $mediaCollections->get($index)->name);
        self::assertEquals(data_get($config, 'only_keep_latest'), $mediaCollections->get($index)->collectionSizeLimit);
        self::assertFalse($mediaCollections->get($index)->singleFile);
        self::assertIsArray($mediaCollections->get($index)->acceptsMimeTypes);
        self::assertSame(data_get($config, 'accepts_mime_types'), $mediaCollections->get($index)->acceptsMimeTypes);
    }

    /** @test */
    public function it_has_video_collection(): void
    {
        $config = config('cms.media.multiple_videos');
        $mediaCollections = (new TestMediaModel())->getRegisteredMediaCollections();
        $index = 2;

        self::assertCount(5, $mediaCollections);
        self::assertEquals('testmediamodel.videos', $mediaCollections->get($index)->name);
        self::assertEquals(data_get($config, 'only_keep_latest'), $mediaCollections->get($index)->collectionSizeLimit);
        self::assertFalse($mediaCollections->get($index)->singleFile);
        self::assertIsArray($mediaCollections->get($index)->acceptsMimeTypes);
        self::assertSame(data_get($config, 'accepts_mime_types'), $mediaCollections->get($index)->acceptsMimeTypes);
    }

    /** @test */
    public function it_has_document_collection(): void
    {
        $config = config('cms.media.multiple_documents');
        $mediaCollections = (new TestMediaModel())->getRegisteredMediaCollections();
        $index = 3;

        self::assertCount(5, $mediaCollections);
        self::assertEquals('testmediamodel.documents', $mediaCollections->get($index)->name);
        self::assertEquals(data_get($config, 'only_keep_latest'), $mediaCollections->get($index)->collectionSizeLimit);
        self::assertFalse($mediaCollections->get($index)->singleFile);
        self::assertIsArray($mediaCollections->get($index)->acceptsMimeTypes);
        self::assertSame(data_get($config, 'accepts_mime_types'), $mediaCollections->get($index)->acceptsMimeTypes);
    }

    /** @test */
    public function it_has_archive_collection(): void
    {
        $config = config('cms.media.multiple_archives');
        $mediaCollections = (new TestMediaModel())->getRegisteredMediaCollections();
        $index = 4;

        self::assertCount(5, $mediaCollections);
        self::assertEquals('testmediamodel.archives', $mediaCollections->get($index)->name);
        self::assertEquals(data_get($config, 'only_keep_latest'), $mediaCollections->get($index)->collectionSizeLimit);
        self::assertFalse($mediaCollections->get($index)->singleFile);
        self::assertIsArray($mediaCollections->get($index)->acceptsMimeTypes);
        self::assertSame(data_get($config, 'accepts_mime_types'), $mediaCollections->get($index)->acceptsMimeTypes);
    }

    /** @test */
    public function it_has_conversions(): void
    {
        Storage::fake('local');

        $page = tap(TestMediaModel::create(Page::factory()->raw()), static function ($page) {
           $page->addMedia(UploadedFile::fake()->image('photo1.jpg', 1200, 1200))
               ->toMediaCollection('testmediamodel.cover', 'local');
        });

        $image = ImageFactory::load($page->getFirstMedia('testmediamodel.cover')->getPath());
        self::assertEquals(1200, $image->getWidth());
        self::assertEquals(1200, $image->getHeight());

        $image = ImageFactory::load($page->getFirstMedia('testmediamodel.cover')->getPath('meta'));
        self::assertEquals(1200, $image->getWidth());
        self::assertEquals(600, $image->getHeight());

        $image = ImageFactory::load($page->getFirstMedia('testmediamodel.cover')->getPath('3:4'));
        self::assertEquals(900, $image->getWidth());
        self::assertEquals(1200, $image->getHeight());

        $image = ImageFactory::load($page->getFirstMedia('testmediamodel.cover')->getPath('4:3'));
        self::assertEquals(1200, $image->getWidth());
        self::assertEquals(900, $image->getHeight());
    }
}

class TestMediaModel extends Model implements HasMedia
{
    use HasMediaCollections;

    protected $table = 'pages';

    protected $guarded = [];

    protected array $mediaConfiguration = [
        'cover' => 'single_image',
        'images' => 'multiple_images',
        'videos' => 'multiple_videos',
        'documents' => 'multiple_documents',
        'archives' => 'multiple_archives',
    ];

    protected array $mediaConversionConfiguration = [
        'cover' => ['meta', '3:4', '4:3'],
    ];
}

class TestModel extends Model implements HasMedia
{
    use HasMediaCollections;
}
