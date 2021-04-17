<?php

namespace Lemaur\Cms;

use Closure;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;

class CmsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-cms')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_cms_tables')
            ->hasRoute('cms')
        ;
    }

    public function bootingPackage(): void
    {
        parent::bootingPackage();

        MediaCollection::macro('when', function (bool $condition, Closure $callback) {
            if ($condition) {
                $callback($this);
            }

            return $this;
        });

        $this->app['config']->set('seotools.meta.defaults.title', false);
        $this->app['config']->set('seotools.meta.defaults.description', false);
        $this->app['config']->set('seotools.meta.defaults.separator', $this->app['config']->get('cms.seo.title.separator'));
        $this->app['config']->set('seotools.opengraph.defaults.title', false);
        $this->app['config']->set('seotools.opengraph.defaults.description', false);
        $this->app['config']->set('seotools.twitter.defaults.card', $this->app['config']->get('cms.seo.twitter.card'));
        $this->app['config']->set('seotools.twitter.defaults.site', $this->app['config']->get('cms.seo.twitter.site'));
    }
}
