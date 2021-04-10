<?php

namespace Lemaur\Cms\Models\ViewModels;

use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Str;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Models\ReservedSlug;
use Lemaur\Sitemap\Sitemap;
use Lemaur\Sitemap\Tags\Url;
use Spatie\Sitemap\SitemapIndex;
use Spatie\ViewModels\ViewModel;
use Symfony\Component\HttpFoundation\Response;

class PageViewModel extends ViewModel
{
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

        // @TODO: cache it
        $pages = Page::withType($type)
            ->withSlug($this->page->slug)
            ->orderBy('id')
            ->get();

        $pages->each(function ($page) use ($sitemap) {
            $viewModel = $page->toViewModel();
            $diffInDays = $page->updated_at->floatDiffInDays();

            $url = Url::create($viewModel->url())
                ->setLastModificationDate($page->updated_at->toDate())
                ->setChangeFrequency($this->getSitemapFrequency($diffInDays))
                ->setPriority($this->getSitemapPriority($diffInDays));

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

        return Str::markdown($this->page->content, config('cms.markdown.options', []));
    }

    public function excerpt(): string | null
    {
        if (is_null($this->page?->excerpt)) {
            return null;
        }

        return Str::markdown($this->page->excerpt, config('cms.markdown.options', []));
    }

    public function pageTitle(): string
    {
        $separator = config('cms.seo.title.separator', null);

        return collect([
            config('cms.seo.title.prefix', null),
            $separator,
            $this->page->meta_title,
            $separator,
            config('app.name'),
        ])->filter()->join(' ');
    }

    public function generateMeta(): void
    {
        SEOTools::setTitle($this->pageTitle());
        SEOTools::setDescription($this->page->meta_description);

        $this->generateOpengraphMetaTags();

        $this->generateTwitterMetaTags();

        $this->generateSchemaOrg();
    }

    private function generateSchemaOrg(): void
    {
        // @TODO
    }

    private function generateTwitterMetaTags(): void
    {
        SEOTools::twitter()
//            ->setTitle($title)
//            ->setDescription($description)
            // images [600x1200:jpg]
            // product
        ;
    }

    private function generateOpengraphMetaTags(): void
    {
        SEOTools::opengraph()
            ->addProperty('locale', app()->getLocale())
            ->setType($this->opengraphType())
//            ->setTitle($title)
//            ->setDescription($description)
            // images [630x1200:jpg]
            // product
        ;
    }

    private function opengraphType(): string
    {
        $types = [
            'page' => 'website',
            'service' => 'website',
//            'shop' => 'product',
//            'article' => 'article',
        ];

        return $types[$this->page->type] ?? 'website';
    }

    public function slug(): string
    {
        return (string) Str::of(vsprintf('%s/%s', [
                $this->page->parent,
                ReservedSlug::find($this->page->slug),
            ]))
            ->replace('//', '/')
            ->trim('/');
    }

    public function url(): string
    {
        return secure_url($this->slug());
    }

//    public function children(): EloquentCollection | null
//    {
//        // @TODO
//    }

    public function media()
    {
        // @TODO
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

    private function getSitemapFrequency(mixed $diffInDays): string
    {
        // @TODO: improve
        return $diffInDays < 30 ? Url::CHANGE_FREQUENCY_WEEKLY : Url::CHANGE_FREQUENCY_MONTHLY;
    }

    private function getSitemapPriority(mixed $diffInDays): mixed
    {
        return max(0.1, min(1.0, round(10 / $diffInDays, 1)));
    }
}
