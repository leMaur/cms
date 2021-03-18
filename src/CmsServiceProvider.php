<?php

namespace Lemaur\Cms;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Lemaur\Cms\Commands\CmsCommand;

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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_cms_table')
            ->hasCommand(CmsCommand::class);
    }
}