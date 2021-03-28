<?php

namespace Lemaur\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Navigation extends Model implements Sortable
{
    use SortableTrait;

    public const PRIMARY = 'primary_navigation';
    public const SECONDARY = 'secondary_navigation';
    public const SOCIAL = 'social_navigation';

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
