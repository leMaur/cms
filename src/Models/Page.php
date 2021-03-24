<?php

namespace Lemaur\Cms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lemaur\Cms\Models\Concerns\HasSchemalessAttributes;
use Lemaur\Cms\Models\Concerns\HasSlug;
use Lemaur\Cms\Tests\Feature\User;
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

    protected $guarded = [];

    protected $casts = [
        'extra_attributes' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo((string) config('cms.users.model'));
    }

    public function setParentAttribute($value): void
    {
        $slug = collect();

        if ($value instanceof Page) {
            $slug->push($value->parent);
            $slug->push($value->slug);

            $parent = $slug->filter()->join('/');
        }

        if (is_string($value)) {
            $ancestor = self::where('slug', $value)->first()?->parent;

            $slug->push($ancestor);
            $slug->push($value);

            $parent = $slug->filter()->join('/');
        }

        $this->attributes['parent'] = $parent ?? $value;
    }
}
