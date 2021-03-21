<?php

namespace Lemaur\Cms;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Lemaur\Cms\Commands\CmsCommand;
use Spatie\Tags\TagsServiceProvider;

class CmsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-cms')
//            ->hasConfigFile()
//            ->hasViews()
            ->hasMigrations([
                'create_cms_tables',
                'create_tag_tables'
            ])
//            ->hasCommand(CmsCommand::class)
        ;
    }
}
