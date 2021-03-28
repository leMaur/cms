<?php

namespace Lemaur\Cms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lemaur\Cms\Models\Concerns\HasAvailableParents;
use Lemaur\Cms\Models\Concerns\HasLayout;
use Lemaur\Cms\Models\Concerns\HasParent;
use Lemaur\Cms\Models\Concerns\HasSchemalessAttributes;
use Lemaur\Cms\Models\Concerns\HasSlug;
use Lemaur\Cms\Models\Concerns\HasUser;
use Lemaur\Publishing\Database\Eloquent\Publishes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Page extends Model implements Sortable
{
    use HasAvailableParents;
    use HasFactory;
    use HasLayout;
    use HasParent;
    use HasSchemalessAttributes;
    use HasSlug;
    use HasUser;
    use Publishes;
    use SoftDeletes;
    use SortableTrait;

    public const META_DESCRIPTION_LIMIT = 150;
    public const DEFAULT_LAYOUT = 'basic';

    protected $guarded = [];

    protected $casts = [
        'extra_attributes' => 'array',
    ];

    public static function getAvailableLayouts(): array
    {
        return static::distinct()
                ->select('extra_attributes->layout as layout_name')
                ->whereNotNull('layout_name')
                ->orderBy('layout_name', 'asc')
                ->get()
                ->pluck('layout_name')
                ->all();
    }
}
