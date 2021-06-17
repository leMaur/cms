<?php

declare(strict_types=1);

namespace Lemaur\Cms\ViewModels;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\ViewModels\Concerns\HasMetaTags;
use Lemaur\Cms\ViewModels\Concerns\HasSitemaps;
use Lemaur\Markdown\Markdown;
use Spatie\ViewModels\ViewModel;
use Symfony\Component\HttpFoundation\Response;

class PageViewModel extends ViewModel
{
    use HasMetaTags;
    use HasSitemaps;

    public function __construct(protected Page $page)
    {
        $layout = $this->page->layout ?? 'basic';

        $this->view = sprintf('cms::%s', $layout);
    }

    public function title(): string
    {
        return $this->page->title;
    }

    public function parent(): ?PageViewModel
    {
        // @TODO: cache it
        if (is_null($this->page->parent)) {
            return null;
        }

        $parent = Page::firstWhere('slug', $this->page->parent);

        if (is_null($parent)) {
            return null;
        }

        return new PageViewModel($parent);
    }

    public function type(): string
    {
        return $this->page->type;
    }

    public function layout(): string
    {
        return $this->page->layout;
    }

    public function content(): ?string
    {
        // @TODO: cache it
        $content = Markdown::render($this->page->content);

        if ($content->isEmpty()) {
            return null;
        }

        return $content->toHtml();
    }

    public function excerpt(): ?string
    {
        // @TODO: cache it
        $content = Markdown::render($this->page->excerpt);

        if ($content->isEmpty()) {
            return null;
        }

        return $content->toHtml();
    }

    public function slug(): string
    {
        // @TODO: cache it
        $string = collect([$this->page->parent, ReservedSlug::toSlug($this->page->slug)])->join('/');

        return trim($string, '/');
    }

    public function url(): string
    {
        return url($this->slug());
    }

    public function children(int $page = 1, int $perPage = 15): ?LengthAwarePaginator
    {
        // @TODO: cache it
        $pages = Page::where('parent', $this->page->slug)
            ->when(
                Auth::guest(),
                fn (Builder $query) => $query->onlyPublished()->latestPublished(),
                fn (Builder $query) => $query->latest('updated_at')
            )
            ->paginate($page, $perPage);

        if (is_null($pages) || $pages->count() === 0) {
            return null;
        }

        return $pages;
    }

    public function coverImage(): ?ImageViewModel
    {
        // @TODO: cache it
        $mediaCollectionName = $this->page
            ->getRegisteredMediaCollections()
            ->pluck('name')
            ->filter(fn ($item) => str_contains($item, "cover"))
            ->first();

        $media = $this->page->getFirstMedia($mediaCollectionName);

        if (is_null($media)) {
            return null;
        }

        return new ImageViewModel($media);
    }

    public function toResponse($request): Response
    {
        if ($this->isSitemap($request)) {
            return $this->sitemapToResponse($request);
        }

        if ($this->isSitemapIndex()) {
            return $this->sitemapIndexToResponse($request);
        }

        return parent::toResponse($request);
    }
}
