<?php

namespace Lemaur\Cms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lemaur\Cms\Models\Concerns\HasExcerpt;
use Lemaur\Cms\Models\Concerns\HasParent;
use Lemaur\Cms\Models\Concerns\HasSchemalessAttributes;
use Lemaur\Cms\Models\Concerns\HasSlug;
use Lemaur\Publishing\Database\Eloquent\Publishes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Page extends Model implements Sortable
{
    use HasExcerpt;
    use HasFactory;
    use HasParent;
    use HasSchemalessAttributes;
    use HasSlug;
    use Publishes;
    use SoftDeletes;
    use SortableTrait;

    public const EXCERPT_LIMIT = 160;

    protected $guarded = [];

    protected $casts = [
        'extra_attributes' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo((string) config('cms.users.model'), 'user_id');
    }
}
