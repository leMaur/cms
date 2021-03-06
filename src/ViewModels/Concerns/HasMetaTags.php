<?php

declare(strict_types=1);

namespace Lemaur\Cms\ViewModels\Concerns;

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

    protected function generateMetaTags(): void
    {
        SEOTools::setTitle($this->pageTitle());
        SEOTools::setDescription($this->model->meta_description);
    }

    protected function generateOpengraphMetaTags(): void
    {
        // @TODO: check opengraph metatags generator
        SEOTools::opengraph()
            ->addProperty('locale', app()->getLocale())
            ->setType($this->opengraphType());

        if ((bool) $this->metaImage()) {
            SEOTools::opengraph()->addImage($this->metaImage(), ['height' => '600', 'width' => '1200']);
        }

        // @TODO: meta tags opengraph product
    }

    protected function generateTwitterMetaTags(): void
    {
        if ((bool) $this->metaImage()) {
            SEOTools::twitter()->addImage($this->metaImage());
        }

        // @TODO: meta tags twitter product
    }

    protected function generateSchemaOrg(): void
    {
        // @TODO: generate schema org
    }

    protected function opengraphType(): string
    {
        // @TODO: complete opengraph types
        $types = [
            'page' => 'website',
            'service' => 'website',
//            'shop' => 'product',
//            'article' => 'article',
        ];

        return $types[$this->model->type] ?? 'website';
    }

    protected function metaImage(): ?string
    {
        return $this->coverImage()?->url('meta');
    }

    public function pageTitle(): string
    {
        return collect([
                config('cms.seo.title.prefix', null),
                $this->model->meta_title,
                config('app.name'),
            ])
            ->filter()
            ->join(config('cms.seo.title.separator', ' '));
    }
}
