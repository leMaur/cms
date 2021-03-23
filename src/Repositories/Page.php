<?php

namespace Lemaur\Cms\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Lemaur\Cms\Models\Page as PageModel;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\Repositories\Contracts\Repository;
use Ramsey\Uuid\Exception\InvalidUuidStringException;

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

        try {
            return $this->findByUuid($slug);
        } catch (InvalidUuidStringException) {
        }

        return $this->findBySlug($slug);
    }

    private function findHomepage(): PageModel
    {
        return $this->page->whereSlug(ReservedSlug::HOMEPAGE)->firstOrFail();
    }

    private function findByUuid(string $uuid): PageModel
    {
        return $this->page->whereUuid($uuid)->firstOrFail();
    }

    private function findBySlug(string $slug): PageModel
    {
        $slugs = Str::of($slug)->explode('/')->toBase();

        $page = $slugs->pop();
        $parent = $slugs->join('/');

        return $this->page->query()
            ->where('slug', $page)
            ->when($parent, function (Builder $query) use ($parent) {
                $query->where('parent', $parent);
            })
            ->firstOrFail();
    }
}
