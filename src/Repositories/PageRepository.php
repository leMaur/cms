<?php

namespace Lemaur\Cms\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\Repositories\Contracts\Findable;

class PageRepository implements Findable
{
    public function __construct(private Page $page)
    {}

    public function find(?string $slug = null): Page
    {
        if (ReservedSlug::list()->keys()->contains($slug)) {
            abort(404);
        }

        if (is_null($slug)) {
            $slug = '/';
        }

        return $this->findBySlug(ReservedSlug::toReserved($slug));
    }

    private function findBySlug(string $slug): Page
    {
        if (ReservedSlug::isReserved($slug)) {
            return $this->page->where('slug', $slug)->firstOrFail();
        }

        $slugs = Str::of($slug)->explode('/')->toBase();

        $slug = $slugs->pop();
        $parent = $slugs->count() === 0 ? null : $slugs->join('/');

        // @TODO: cache it
        return $this->page
            ->where('slug', $slug)
            ->when($parent, function (Builder $query) use ($parent) {
                $query->where('parent', $parent);
            })
            ->when(Auth::guest(), function (Builder $query) {
                $query->onlyPublished();
            })
            ->orderBy('id')
            ->firstOrFail();
    }
}
