<?php

namespace Lemaur\Cms\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lemaur\Cms\Models\Concerns\HasAuthor;
use Lemaur\Cms\Models\Concerns\HasAvailableLayouts;
use Lemaur\Cms\Models\Concerns\HasAvailableParents;
use Lemaur\Cms\Models\Concerns\HasLayout;
use Lemaur\Cms\Models\Concerns\HasParent;
use Lemaur\Cms\Models\Concerns\HasSchemalessAttributes;
use Lemaur\Cms\Models\Concerns\HasSlug;
use Lemaur\Cms\Traits\HasExcerpt;
use Lemaur\Cms\Traits\HasMediaCollections;
use Lemaur\Cms\Traits\HasMetaDescription;
use Lemaur\Cms\Traits\HasMetaTitle;
use Lemaur\Publishing\Database\Eloquent\Publishes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;

class Page extends Model implements Sortable, HasMedia
{
    use HasAuthor;
    use HasAvailableLayouts;
    use HasAvailableParents;
    use HasExcerpt;
    use HasFactory;
    use HasLayout;
    use HasMediaCollections;
    use HasMetaDescription;
    use HasMetaTitle;
    use HasParent;
    use HasSchemalessAttributes;
    use HasSlug;
    use Publishes;
    use SoftDeletes;
    use SortableTrait;

    public const META_DESCRIPTION_LIMIT = 150;

    protected $guarded = [];

    protected $casts = [
        'extra_attributes' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('cms.pages.table'));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo((string) config('cms.users.model'), 'user_id');
    }

    public function navigations(): HasMany
    {
        return $this->hasMany((string) config('cms.navigations.model'), 'page_id');
    }

    public function scopeWithType(Builder $query, string $type = null): Builder
    {
        if (is_null($type)) {
            return $query;
        }

        return $query->where('type', $type);
    }
}
