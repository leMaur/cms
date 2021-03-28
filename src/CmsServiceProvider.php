<?php

namespace Lemaur\Cms;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
}
