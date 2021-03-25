<?php

namespace Lemaur\Cms;

use Lemaur\Cms\Commands\CmsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasMigration('create_cms_tables')
            ->hasRoute('cms')
//            ->hasCommand(CmsCommand::class)
        ;
    }
}
