<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Lemaur\Cms\Models\Concerns\HasMediaCollections;
use Lemaur\Cms\Models\Concerns\HasSlug;
use Lemaur\Cms\Models\Concerns\HasType;
use Lemaur\Cms\ViewModels\Contracts\Viewable;
use Lemaur\Cms\ViewModels\NavigationViewModel;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;

class Navigation extends Model implements Sortable, HasMedia, Viewable
{
    use HasMediaCollections;
    use HasFactory;
    use HasSlug;
    use HasType;
    use SortableTrait;

    public const PRIMARY = 'primary';
    public const SECONDARY = 'secondary';
    public const SOCIAL = 'social';

    protected $guarded = [];

    protected array $mediaConfiguration = [
        'image' => 'single_image',
    ];

    protected string $slugFrom = 'name';

    // @TODO: move to observer class
    protected static function booted(): void
    {
        static::saved(function ($model) {
            Cache::forget(cacheKeyGenerator('navigation', 'social', $model->slug));
        });

        static::deleted(function ($model) {
            Cache::forget(cacheKeyGenerator('navigation', 'social', $model->slug));
        });
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('cms.navigations.table'));
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo((string) config('cms.pages.model'), 'page_id');
    }

    public function toViewModel(): NavigationViewModel
    {
        return new NavigationViewModel($this);
    }
}
