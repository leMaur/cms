<?php

namespace Lemaur\Cms\Tests\Feature\Traits;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Lemaur\Cms\Tests\TestCase;
use Lemaur\Cms\Traits\HasMediaCollections;
use Spatie\MediaLibrary\HasMedia;

class HasMediaCollectionsTest extends TestCase
{
    /** @test */
    public function it_thronw_an_exception_if_property_is_not_set(): void
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
}

class TestMediaModel extends Model implements HasMedia
{
    use HasMediaCollections;

    protected array $mediaConfiguration = [
        'cover' => 'single_image',
        'images' => 'multiple_images',
        'videos' => 'multiple_videos',
        'documents' => 'multiple_documents',
        'archives' => 'multiple_archives',
    ];
}

class TestModel extends Model implements HasMedia
{
    use HasMediaCollections;
}
