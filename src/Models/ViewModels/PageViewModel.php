<?php

namespace Lemaur\Cms\Models\ViewModels;

use Illuminate\Support\Str;
use Lemaur\Cms\Markdown;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Cms\Models\ViewModels\Concerns\HasMetaTags;
use Lemaur\Sitemap\Sitemap;
use Lemaur\Sitemap\Tags\Url;
use Spatie\Sitemap\SitemapIndex;
use Spatie\ViewModels\ViewModel;
use Symfony\Component\HttpFoundation\Response;

class PageViewModel extends ViewModel
{
    use HasMetaTags;

    public function __construct(private Page $page)
    {
        $this->view = sprintf('cms::%s', $this->page->layout);
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

    private function isSitemapIndex(): bool
    {
        return $this->page->layout === 'sitemap_index';
    }

    private function isSitemap($request): bool
    {
        return count($request->segments()) === 2 && $request->segment(1) === 'sitemaps';
    }

    private function sitemapIndexToResponse($request): Response
    {
        $sitemapIndex = SitemapIndex::create();

        foreach (Page::getAvailableTypes() as $type) {
            $sitemapIndex->add(sprintf('sitemaps/sitemap-%s.xml', Str::plural($type)));
        }

        return $sitemapIndex->toResponse($request);
    }

    private function sitemapToResponse($request): Response
    {
        $type = $this->getSitemapType($request);

        if (! collect(Page::getAvailableTypes())->containsStrict($type)) {
            abort(404);
        }

        $sitemap = Sitemap::create();

        // @TODO: cache it (make cache decorator)
        $pages = Page::withType($type)
            ->withoutSlug($this->page->slug)
            ->orderBy('id')
            ->get();

        $pages->each(function ($page) use ($sitemap) {
            $url = Url::create($page->toViewModel()->url())
                ->setLastModificationDate($page->updated_at->toDate())
                // @TODO: improve sitemap change frequency
                ->setChangeFrequency($this->getSitemapFrequency($page))
                // @TODO: improve sitemap priority
                ->setPriority($this->getSitemapPriority($page));

//            if ($viewModel->hasCoverImage()) {
//                $url->addImage(
//                    Image::create($viewModel->coverImage()->url)
//                        ->setTitle($viewModel->coverImage()->alt)
//                        ->setCaption($viewModel->coverImage()->caption)
//                );
//            }

            $sitemap->add($url);
        });

        return $sitemap->toResponse($request);
    }

    public function content(): string | null
    {
        if (is_null($this->page?->content)) {
            return null;
        }

        return Markdown::convert($this->page->content, config('cms.markdown.options', []));
    }

    public function excerpt(): string | null
    {
        if (is_null($this->page?->excerpt)) {
            return null;
        }

        return Markdown::convert($this->page->excerpt, config('cms.markdown.options', []));
    }

    public function slug(): string
    {
        return (string) Str::of(vsprintf('%s/%s', [
                $this->page->parent,
                ReservedSlug::toSlug($this->page->slug),
            ]))
            ->replace('//', '/')
            ->trim('/');
    }

    public function url(): string
    {
        return secure_url($this->slug());
    }

    public function children()
    {
        // @TODO: get children pages
    }

    public function media()
    {
        // @TODO: get page media
        return $this->page->getMedia();
//        return (object) [
//            'single' => [
//                'src' => '',
//                'alt' => '',
//                'caption' => '',
//            ],
//        ];
    }

    private function getSitemapType($request): string
    {
        return (string) Str::of($request->segment(2))
            ->replace(['sitemap', '.xml', '-', '_'], ['', '', '', ''])
            ->singular();
    }

    private function getSitemapFrequency(Page $page): string
    {
        $diffInDays = $page->updated_at->floatDiffInDays();

        return $diffInDays < 30 ? Url::CHANGE_FREQUENCY_WEEKLY : Url::CHANGE_FREQUENCY_MONTHLY;
    }

    private function getSitemapPriority(Page $page): mixed
    {
        $diffInDays = $page->updated_at->floatDiffInDays();

        return max(0.1, min(1.0, round(10 / $diffInDays, 1)));
    }
}
