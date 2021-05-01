<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lemaur\Cms\Models\Concerns\HasAuthor;
use Lemaur\Cms\Models\Concerns\HasAvailableLayouts;
use Lemaur\Cms\Models\Concerns\HasAvailableParents;
use Lemaur\Cms\Models\Concerns\HasAvailableTypes;
use Lemaur\Cms\Models\Concerns\HasParent;
use Lemaur\Cms\Models\Concerns\HasSchemalessAttributes;
use Lemaur\Cms\Models\Concerns\HasSitemapExtraAttributes;
use Lemaur\Cms\Models\Concerns\HasSlug;
use Lemaur\Cms\Models\Concerns\HasType;
use Lemaur\Cms\Models\Concerns\HasExcerpt;
use Lemaur\Cms\Models\Concerns\HasMediaCollections;
use Lemaur\Cms\Models\Concerns\HasMetaDescription;
use Lemaur\Cms\Models\Concerns\HasMetaTitle;
use Lemaur\Cms\ViewModels\PageViewModel;
use Lemaur\Publishing\Database\Eloquent\Publishes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;

class Page extends Model implements Sortable, HasMedia
{
    use HasAuthor;
    use HasAvailableLayouts;
    use HasAvailableParents;
    use HasAvailableTypes;
    use HasExcerpt;
    use HasFactory;
    use HasMediaCollections;
    use HasMetaDescription;
    use HasMetaTitle;
    use HasParent;
    use HasSchemalessAttributes;
    use HasSitemapExtraAttributes;
    use HasSlug;
    use HasType;
    use Publishes;
    use SoftDeletes;
    use SortableTrait;

    protected $guarded = [];

    protected $casts = [
        'extra_attributes' => 'array',
    ];

    protected array $mediaConfiguration = [
        'cover' => 'single_image',
    ];

    protected array $mediaConversionConfiguration = [
        'cover' => ['meta'],
    ];

    protected string $slugFrom = 'title';

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

    public function toViewModel(): PageViewModel
    {
        return new PageViewModel($this);
    }
}
