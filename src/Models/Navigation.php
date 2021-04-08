<?php

namespace Lemaur\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lemaur\Cms\Models\Concerns\HasType;
use Lemaur\Cms\Traits\HasSingleImage;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;

class Navigation extends Model implements Sortable, HasMedia
{
    use HasSingleImage;
    use HasType;
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
}
