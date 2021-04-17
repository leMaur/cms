<?php

namespace Lemaur\Cms\Models\ViewModels;

use Illuminate\Database\Eloquent\Collection;
use Lemaur\Cms\Markdown;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\Models\ViewModels\Concerns\HasMetaTags;
use Lemaur\Cms\Models\ViewModels\Concerns\HasSitemaps;
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

    public function content(): string | null
    {
        return Markdown::convert($this->page->content, config('cms.markdown.options', []));
    }

    public function excerpt(): string | null
    {
        return Markdown::convert($this->page->excerpt, config('cms.markdown.options', []));
    }

    public function slug(): string
    {
        $string = collect([$this->page->parent, ReservedSlug::toSlug($this->page->slug)])->join('/');

        return trim($string, '/');
    }

    public function url(): string
    {
        return secure_url($this->slug());
    }

    public function children(): Collection | null
    {
        // @TODO: cache it
        $pages = Page::where('parent', $this->page->slug)
            ->latestPublished()
            ->get();

        if (is_null($pages) || $pages->count() === 0) {
            return null;
        }

        return $pages;
    }

    public function coverImage(): ImageViewModel | null
    {
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
