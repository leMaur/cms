<?php

declare(strict_types=1);

namespace Lemaur\Cms\ViewModels\Concerns;

use Illuminate\Support\Str;
use Lemaur\Cms\Models\Page;
use Lemaur\Sitemap\Sitemap;
use Lemaur\Sitemap\Tags\Image;
use Lemaur\Sitemap\Tags\Url;
use Spatie\Sitemap\SitemapIndex;
use Symfony\Component\HttpFoundation\Response;

trait HasSitemaps
{
    protected function sitemapToResponse($request): Response
    {
        $type = $this->getSitemapType($request);

        if (! collect(Page::getAvailableTypes())->containsStrict($type)) {
            abort(404);
        }

        $sitemap = Sitemap::create();

        // @TODO: cache it (make cache decorator)
        $pages = Page::withType($type)
            ->withoutSlug($this->model->slug)
            ->orderBy('id')
            ->get();

        $pages->each(function ($page) use ($sitemap) {
            $url = Url::create($page->toViewModel()->url())
                ->setLastModificationDate($page->updated_at->toDate())
                ->setChangeFrequency($page->sitemap_frequency)
                ->setPriority($page->sitemap_priority);

            $coverImage = $page->toViewModel()->coverImage();

            if (! is_null($coverImage)) {
                $url->addImage(
                    Image::create($coverImage->url())
                        ->setTitle($coverImage->alt())
                        ->setCaption($coverImage->caption())
                );
            }

            $sitemap->add($url);
        });

        return $sitemap->toResponse($request);
    }

    protected function sitemapIndexToResponse($request): Response
    {
        $sitemapIndex = SitemapIndex::create();

        foreach (Page::getAvailableTypes() as $type) {
            $sitemapIndex->add(sprintf('sitemaps/sitemap-%s.xml', Str::plural($type)));
        }

        return $sitemapIndex->toResponse($request);
    }

    protected function getSitemapType($request): string
    {
        return (string) Str::of($request->segment(2))
            ->replace(['sitemap', '.xml', '-', '_'], ['', '', '', ''])
            ->singular();
    }

    protected function isSitemapIndex(): bool
    {
        return $this->model->layout === 'sitemap_index';
    }

    protected function isSitemap($request): bool
    {
        return count($request->segments()) === 2 && $request->segment(1) === 'sitemaps';
    }
}
