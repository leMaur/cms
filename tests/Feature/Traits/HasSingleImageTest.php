<?php

namespace Lemaur\Cms\Tests\Feature\Traits;

use Illuminate\Database\Eloquent\Model;
use Lemaur\Cms\Tests\TestCase;
use Lemaur\Cms\Traits\HasSingleImage;
use Spatie\MediaLibrary\HasMedia;

class HasSingleImageTest extends TestCase
{
    /** @test */
    public function it_registers_media_collection(): void
    {
        $config = config('cms.media.single_image');
        $mediaCollections = (new TestModel())->getRegisteredMediaCollections();

        self::assertCount(1, $mediaCollections);
        self::assertEquals('testmodel.cover', $mediaCollections->first()->name);
        self::assertEquals(data_get($config, 'only_keep_latest'), $mediaCollections->first()->collectionSizeLimit);
        self::assertTrue($mediaCollections->first()->singleFile);
        self::assertIsArray($mediaCollections->first()->acceptsMimeTypes);
        self::assertSame(data_get($config, 'accepts_mime_types'), $mediaCollections->first()->acceptsMimeTypes);
    }
}

class TestModel extends Model implements HasMedia
{
    use HasSingleImage;
}
