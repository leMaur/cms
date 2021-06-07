<?php

declare(strict_types=1);

namespace Lemaur\Cms\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\Repositories\Contracts\Findable;

class PageRepository implements Findable
{
    public function find(?string $slug = null): Page
    {
        if (ReservedSlug::list()->keys()->contains($slug)) {
            Log::channel('errorlog')->alert("Trying to access to a reserved slug '{$slug}' directly");
            abort(404, "Trying to access to a reserved slug '{$slug}' directly");
        }

        if (is_null($slug)) {
            $slug = '/';
        }

        return $this->findBySlug(ReservedSlug::toReserved($slug));
    }

    private function findBySlug(string $slug): Page
    {
        if (ReservedSlug::isReserved($slug)) {
            // @TODO: cache it
            $page = Page::where('slug', $slug)->first();

            if (is_null($page)) {
                Log::channel('errorlog')->alert("Reserved slug '{$slug}' not found");
                abort(404, "Reserved slug '{$slug}' not found");
            }

            return $page;
        }

        $slugs = Str::of($slug)->explode('/')->toBase();

        $slug = $slugs->pop();
        $parent = $slugs->count() === 0 ? null : $slugs->join('/');

        // @TODO: cache it
        $page = Page::query()
            ->where('slug', $slug)
            ->when($parent, function (Builder $query) use ($parent) {
                $query->where('parent', $parent);
            })
            ->when(Auth::guest(), function (Builder $query) {
                $query->onlyPublished();
            })
            ->firstOrFail();

        if (is_null($page)) {
            Log::channel('errorlog')->alert("page '{$page}' not found");
            abort(404, "page '{$page}' not found");
        }

        return $page;
    }
}
