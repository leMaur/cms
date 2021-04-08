<?php

namespace Lemaur\Cms\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lemaur\Cms\Traits\HasSingleImage;
use ReflectionClass;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;

class Navigation extends Model implements Sortable, HasMedia
{
    use HasSingleImage;
    use SortableTrait;

    public const PRIMARY = 'primary';
    public const SECONDARY = 'secondary';
    public const SOCIAL = 'social';

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('cms.navigations.table'));
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo((string) config('cms.pages.model'), 'page_id');
    }

    public function scopeWithType(Builder $query, string $type = null): Builder
    {
        if (is_null($type)) {
            return $query;
        }

        $reflector = new ReflectionClass(static::class);
        if (! collect($reflector->getConstants())->values()->containsStrict($type)) {
            return $query;
        }

        return $query->where('type', $type);
    }
}
