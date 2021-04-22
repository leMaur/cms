<?php

namespace Lemaur\Cms\Models;

use Lemaur\Cms\Models\Concerns\HasSchemalessAttributes;
use Spatie\Tags\Tag as SpatieTag;

class Tag extends SpatieTag
{
    use HasSchemalessAttributes;

    protected $casts = [
        'extra_attributes' => 'array',
    ];
}
