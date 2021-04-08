<?php

namespace Lemaur\Cms\Models\ViewModels;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Models\ReservedSlug;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Image;
use Spatie\Sitemap\Tags\Url;
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
            return $this->sitemap($request)->toResponse($request);
        }

        if ($this->isSitemapIndex()) {
            return $this->sitemapIndex()->toResponse($request);
        }

        return parent::toResponse($request);
    }

    private function isSitemapIndex(): bool
    {
        return $this->page->layout === 'sitemap_index';
    }

    private function sitemapIndex(): SitemapIndex
    {
        $sitemapIndex =  SitemapIndex::create();

        foreach (Page::getAvailableTypes() as $type) {
            $sitemapIndex->add(sprintf('sitemaps/sitemap-%s.xml', Str::plural($type)));
        }

        return $sitemapIndex;
    }

    private function isSitemap($request): bool
    {
        return count($request->segments()) === 2 && $request->segment(1) === 'sitemaps';
    }

    private function sitemap($request): Sitemap
    {
        $type = (string) Str::of($request->segment(2))->replace(['sitemap', '.xml', '-', '_'], ['', '', '', ''])->singular();

        if (! collect(Page::getAvailableTypes())->containsStrict($type)) {
            abort(404);
        }

        $sitemap = Sitemap::create();

        Page::withType($type)
            ->where('slug', '!=', ReservedSlug::find($this->page->slug))
            ->orderBy('id')
            ->get()
            ->each(function ($page) use ($sitemap) {
                $viewModel = $page->toViewModel();
                $diffInDays = $page->updated_at->floatDiffInDays();
                $frequency = $diffInDays < 30 ? 'weekly' : 'monthly';
                $priority = max(0.1, min(1.0, round(10 / $diffInDays, 1)));

                $url = Url::create($viewModel->url())
                    ->setLastModificationDate($page->updated_at->toDate())
                    ->setChangeFrequency($frequency)
                    ->setPriority($priority);

                if ($viewModel->hasCoverImage()) {
                    $url->addImage(
                        Image::create($viewModel->coverImage()->url)
                            ->setTitle($viewModel->coverImage()->alt)
                            ->setCaption($viewModel->coverImage()->caption)
                    );
                }

                $sitemap->add($url);
            });

        return $sitemap;
    }

    public function hasCoverImage(): bool
    {
        // @TODO: define me!
        return false;
    }

    public function coverImage(): object
    {
        // @TODO: define me!
        // @TODO: use DTO?
        return (object) [
            'src' => null,
            'alt' => null,
            'caption' => null,
        ];
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

//    public function meta(): object
//    {
//        // TODO
//        return (object) [
//            'title' => $this->page->getExtraAttribute('title', $this->page->title),
//            'description' => $this->page->getExtraAttribute('description', Str::limit($this->page->excerpt ?? $this->page->content, 150, '')),
//            'opengraph' => [],
//            'twitter' => [],
//            'schema' => [],
//        ];
//    }

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
}
