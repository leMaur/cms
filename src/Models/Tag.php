<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models;

use Lemaur\Cms\Models\Concerns\HasMediaCollections;
use Lemaur\Cms\Models\Concerns\HasSchemalessAttributes;
use Lemaur\Cms\ViewModels\TagViewModel;
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

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        /**
         * Since table name is guessed from the model name.
         * By explicitly define it in the constructor,
         * we can extend the class without setting
         * its name on each extended class.
         */
        $this->setTable((string) config('cms.tags.table'));
    }

    public function toViewModel(): TagViewModel
    {
        return new TagViewModel($this);
    }

    /**
     * Helpful when you want to define:
     *   - Morph to Many: related pivot key
     *   - Morphed by Many: foreign pivot key
     */
    public static function pivotKey(): string
    {
        return (string) config('cms.tags.pivot_key', 'tag_id');
    }

    /**
     * Helpful when you want to define:
     *   - Morph to Many: table name
     *   - Morphed by many: table name
     */
    public static function pivotTableName(): string
    {
        return (string) config('cms.tags.pivot_table_name', 'taggables');
    }
}
