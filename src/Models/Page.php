<?php

namespace Lemaur\Cms\Models;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lemaur\Cms\Models\Concerns\HasSchemalessAttributes;
use Lemaur\Cms\Models\Concerns\HasSlug;
use Lemaur\Publishing\Database\Eloquent\Publishes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Page extends Model implements Sortable
{
    use HasFactory;
    use HasSchemalessAttributes;
    use HasSlug;
    use Publishes;
    use SoftDeletes;
    use SortableTrait;
    use GeneratesUuid;

    protected $guarded = [];

    protected $casts = [
        'uuid' => EfficientUuid::class,
        'extra_attributes' => 'array',
    ];

    public function setParentAttribute($value): void
    {
        if ($value instanceof Page) {
            $parent = $value->slug;
        }

        if (is_string($value)) {
            $parent = $value;
        }

        $this->attributes['parent'] = $parent ?? null;
    }
}
