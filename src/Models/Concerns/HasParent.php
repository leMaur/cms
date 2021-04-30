<?php

declare(strict_types=1);

namespace Lemaur\Cms\Models\Concerns;

use Lemaur\Cms\Models\Page;

trait HasParent
{
    public function setParentAttribute($value): void
    {
        $slug = collect();

        if ($value instanceof Page) {
            $slug->push($value->parent);
            $slug->push($value->slug);

            $parent = $slug->filter()->join('/');
        }

        if (is_string($value)) {
            $ancestor = self::firstWhere('slug', $value)?->parent;

            $slug->push($ancestor);
            $slug->push($value);

            $parent = $slug->filter()->join('/');
        }

        $this->attributes['parent'] = $parent ?? $value;
    }
}
