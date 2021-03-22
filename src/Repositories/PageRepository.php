<?php

namespace Lemaur\Cms\Repositories;

use Illuminate\Support\Str;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\Repositories\Contracts\Repository;
use Ramsey\Uuid\Exception\InvalidUuidStringException;

class PageRepository implements Repository
{
    public function find(?string $slug): Page
    {
        try {
            return $this->findByUuid($slug);
        } catch (InvalidUuidStringException) {
            return $this->findBySlug($slug);
        }
    }

    private function getParentSlug(?string $slug): ?string
    {
        if (is_null($slug)) {
            return null;
        }

        $slugs = tap(Str::of($slug)->explode('/')->toBase(), fn ($slugs) => $slugs->pop());

        return $slugs->join('/');
    }

    private function getPageSlug(?string $slug): ?string
    {
        if (is_null($slug)) {
            return null;
        }

        $slugs = Str::of($slug)->explode('/')->toBase();

        return $slugs->last();
    }

    private function findByUuid(string $uuid): Page
    {
        return Page::whereUuid($uuid)->firstOrFail();
    }

    private function findBySlug(?string $slug): Page
    {
        $parentSlug = $this->getParentSlug($slug);
        $pageSlug = $this->getPageSlug($slug);

        // if $slug is null, find homepage
        if (is_null($slug)) {
            $pageSlug = ReservedSlug::HOMEPAGE;
        }

        return Page::query()
            ->where('slug', $pageSlug)
            ->when($parentSlug, function ($query) use ($parentSlug) {
                $query->where('parent', $parentSlug);
            })
            ->firstOrFail();
    }
}
