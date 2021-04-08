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

    public function bootingPackage()
    {
        parent::bootingPackage();

        MediaCollection::macro('when', function (bool $condition, Closure $callback) {
            if ($condition) {
                $callback($this);
            }

            return $this;
        });
    }
}
