<?php

namespace Lemaur\Cms\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Lemaur\Cms\Models\Page as PageModel;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\Repositories\Contracts\Repository;

class Page implements Repository
{
    private PageModel $page;

    public function __construct(PageModel $page)
    {
        $this->page = $page;
    }

    public function find(?string $slug): PageModel
    {
        if (is_null($slug)) {
            return $this->findHomepage();
        }

        return $this->findBySlug($slug);
    }

    private function findHomepage(): PageModel
    {
        return $this->page->where('slug', ReservedSlug::HOMEPAGE)->firstOrFail();
    }

    private function findBySlug(string $slug): PageModel
    {
        $slugs = Str::of($slug)->explode('/')->toBase();

        $page = $slugs->pop();
        $parent = $slugs->count() === 0 ? null : $slugs->join('/');

        return $this->page
            ->where('slug', $page)
            ->when($parent, function (Builder $query) use ($parent) {
                $query->where('parent', $parent);
            })
            ->firstOrFail();
    }
}