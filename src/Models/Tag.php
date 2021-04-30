<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models;

use Lemaur\Cms\Models\Concerns\HasSchemalessAttributes;
use Lemaur\Cms\Traits\HasMediaCollections;
use Lemaur\Cms\ViewModels\TagViewModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Tags\Tag as SpatieTag;

class Tag extends SpatieTag implements HasMedia
{
    public const FOREIGN_PIVOT_KEY = 'tag_id';
    public const MORPH_TABLE_NAME = 'taggable';

    use HasMediaCollections;
    use HasSchemalessAttributes;

    protected $casts = [
        'extra_attributes' => 'array',
    ];

    protected array $mediaConfiguration = [
        'image' => 'single_image',
    ];

    public function toViewModel(): TagViewModel
    {
        return new TagViewModel($this);
    }
}
