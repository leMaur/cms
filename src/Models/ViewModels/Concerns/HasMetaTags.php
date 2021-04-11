<?php

namespace Lemaur\Cms\Models\ViewModels\Concerns;

use Artesaos\SEOTools\Facades\SEOTools;

trait HasMetaTags
{
    public function generateMeta(): void
    {
        $this->generateMetaTags();

        $this->generateOpengraphMetaTags();

        $this->generateTwitterMetaTags();

        $this->generateSchemaOrg();
    }

    private function generateMetaTags(): void
    {
        SEOTools::setTitle($this->pageTitle());
        SEOTools::setDescription($this->page->meta_description);
    }

    private function generateOpengraphMetaTags(): void
    {
        // @TODO: check opengraph metatags generator
        SEOTools::opengraph()
            ->addProperty('locale', app()->getLocale())
            ->setType($this->opengraphType())
//            ->setTitle($title)
//            ->setDescription($description)
            // images [630x1200:jpg]
            // product
        ;
    }

    private function generateTwitterMetaTags(): void
    {
        // @TODO: check twitter metatags generator
        SEOTools::twitter()
//            ->setTitle($title)
//            ->setDescription($description)
            // images [600x1200:jpg]
            // product
        ;
    }

    private function generateSchemaOrg(): void
    {
        // @TODO: generate schema org
    }

    private function opengraphType(): string
    {
        // @TODO: complete opengraph types
        $types = [
            'page' => 'website',
            'service' => 'website',
//            'shop' => 'product',
//            'article' => 'article',
        ];

        return $types[$this->page->type] ?? 'website';
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
}
