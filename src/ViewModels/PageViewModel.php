<?php

namespace Lemaur\Cms\ViewModels;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Lemaur\Cms\Markdown;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\ViewModels\Concerns\HasMetaTags;
use Lemaur\Cms\ViewModels\Concerns\HasSitemaps;
use Spatie\ViewModels\ViewModel;
use Symfony\Component\HttpFoundation\Response;

class PageViewModel extends ViewModel
{
    use HasMetaTags;
    use HasSitemaps;

    public function __construct(private Page $page)
    {
        $this->view = sprintf('cms::%s', $this->page->layout);
    }

    public function title(): string
    {
        return $this->page->title;
    }

    public function parent(): PageViewModel | null
    {
        // @TODO: cache it
        $parent = Page::whereSlug($this->page->parent)->first();

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

    public function content(): string | null
    {
        // @TODO: cache it
        return Markdown::convert($this->page->content, config('cms.markdown.options', []));
    }

    public function excerpt(): string | null
    {
        // @TODO: cache it
        return Markdown::convert($this->page->excerpt, config('cms.markdown.options', []));
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

    public function children(int $page = 1, int $perPage = 15): LengthAwarePaginator | null
    {
        // @TODO: cache it
        $pages = Page::where('parent', $this->page->slug)
            ->latestPublished()
            ->paginate($page, $perPage);

        if (is_null($pages) || $pages->count() === 0) {
            return null;
        }

        return $pages;
    }

    public function coverImage(): ImageViewModel | null
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
