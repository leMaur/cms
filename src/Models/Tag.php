<?php

namespace Lemaur\Cms\Models;

use Lemaur\Cms\Models\Concerns\HasSchemalessAttributes;
use Lemaur\Cms\Traits\HasMediaCollections;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Tags\Tag as SpatieTag;

class Tag extends SpatieTag implements HasMedia
{
    use HasMediaCollections;
    use HasSchemalessAttributes;

    protected $casts = [
        'extra_attributes' => 'array',
    ];

    protected array $mediaConfiguration = [
        'image' => 'single_image',
    ];
}
