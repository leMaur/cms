<?php

namespace Lemaur\Cms\Tests\Feature\Traits;

use Illuminate\Database\Eloquent\Model;
use Lemaur\Cms\Tests\TestCase;
use Lemaur\Cms\Traits\HasMediaCollections;
use Spatie\MediaLibrary\HasMedia;

class HasMediaCollectionsTest extends TestCase
{
    /** @test */
    public function it_has_single_image(): void
    {
        $config = config('cms.media.single_image');
        $mediaCollections = (new TestMediaCollectionModel())->getRegisteredMediaCollections();
        $index = 0;

        self::assertCount(5, $mediaCollections);
        self::assertEquals('testmediacollectionmodel.cover', $mediaCollections->get($index)->name);
        self::assertEquals(data_get($config, 'only_keep_latest'), $mediaCollections->get($index)->collectionSizeLimit);
        self::assertTrue($mediaCollections->get($index)->singleFile);
        self::assertIsArray($mediaCollections->get($index)->acceptsMimeTypes);
        self::assertSame(data_get($config, 'accepts_mime_types'), $mediaCollections->get($index)->acceptsMimeTypes);
    }

    /** @test */
    public function it_has_image_collection(): void
    {
        $config = config('cms.media.collection_image');
        $mediaCollections = (new TestMediaCollectionModel())->getRegisteredMediaCollections();
        $index = 1;

        self::assertCount(5, $mediaCollections);
        self::assertEquals('testmediacollectionmodel.image.collection', $mediaCollections->get($index)->name);
        self::assertEquals(data_get($config, 'only_keep_latest'), $mediaCollections->get($index)->collectionSizeLimit);
        self::assertFalse($mediaCollections->get($index)->singleFile);
        self::assertIsArray($mediaCollections->get($index)->acceptsMimeTypes);
        self::assertSame(data_get($config, 'accepts_mime_types'), $mediaCollections->get($index)->acceptsMimeTypes);
    }

    /** @test */
    public function it_has_video_collection(): void
    {
        $config = config('cms.media.collection_video');
        $mediaCollections = (new TestMediaCollectionModel())->getRegisteredMediaCollections();
        $index = 2;

        self::assertCount(5, $mediaCollections);
        self::assertEquals('testmediacollectionmodel.video.collection', $mediaCollections->get($index)->name);
        self::assertEquals(data_get($config, 'only_keep_latest'), $mediaCollections->get($index)->collectionSizeLimit);
        self::assertFalse($mediaCollections->get($index)->singleFile);
        self::assertIsArray($mediaCollections->get($index)->acceptsMimeTypes);
        self::assertSame(data_get($config, 'accepts_mime_types'), $mediaCollections->get($index)->acceptsMimeTypes);
    }

    /** @test */
    public function it_has_document_collection(): void
    {
        $config = config('cms.media.collection_document');
        $mediaCollections = (new TestMediaCollectionModel())->getRegisteredMediaCollections();
        $index = 3;

        self::assertCount(5, $mediaCollections);
        self::assertEquals('testmediacollectionmodel.document.collection', $mediaCollections->get($index)->name);
        self::assertEquals(data_get($config, 'only_keep_latest'), $mediaCollections->get($index)->collectionSizeLimit);
        self::assertFalse($mediaCollections->get($index)->singleFile);
        self::assertIsArray($mediaCollections->get($index)->acceptsMimeTypes);
        self::assertSame(data_get($config, 'accepts_mime_types'), $mediaCollections->get($index)->acceptsMimeTypes);
    }

    /** @test */
    public function it_has_archive_collection(): void
    {
        $config = config('cms.media.collection_archive');
        $mediaCollections = (new TestMediaCollectionModel())->getRegisteredMediaCollections();
        $index = 4;

        self::assertCount(5, $mediaCollections);
        self::assertEquals('testmediacollectionmodel.archive.collection', $mediaCollections->get($index)->name);
        self::assertEquals(data_get($config, 'only_keep_latest'), $mediaCollections->get($index)->collectionSizeLimit);
        self::assertFalse($mediaCollections->get($index)->singleFile);
        self::assertIsArray($mediaCollections->get($index)->acceptsMimeTypes);
        self::assertSame(data_get($config, 'accepts_mime_types'), $mediaCollections->get($index)->acceptsMimeTypes);
    }
}

class TestMediaCollectionModel extends Model implements HasMedia
{
    use HasMediaCollections;
}
