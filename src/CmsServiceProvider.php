<?php

namespace Lemaur\Cms;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Str;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;

class CmsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('cms')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoutes('cms', 'social')
        ;
    }

    public function bootingPackage(): void
    {
        parent::bootingPackage();

        $this->bootingMigrations();

        $this->bootingMacros();

        $this->bootingConfigurationOverride();
    }

    private function bootingMigrations(): void
    {
        $now = Carbon::now();

        $migrationFileNames = [
            'create_cms_tables' => '/../database/migrations/create_cms_tables.php.stub',
            'create_tag_tables' => '/../vendor/spatie/laravel-tags/database/migrations/create_tag_tables.php.stub',
            'create_media_table' => '/../vendor/spatie/laravel-medialibrary/database/migrations/create_media_table.php.stub',
        ];

        foreach ($migrationFileNames as $migrationFileName => $migrationFilePath) {
            $this->publishes([
                $this->package->basePath($migrationFilePath) => database_path('migrations/' . $now->addSecond()->format('Y_m_d_His') . '_' . Str::finish($migrationFileName, '.php')),
            ], "{$this->package->shortName()}-migrations");
        }
    }

    private function bootingConfigurationOverride(): void
    {
        $this->app['config']->set('seotools.meta.defaults.title', false);
        $this->app['config']->set('seotools.meta.defaults.description', false);
        $this->app['config']->set('seotools.meta.defaults.separator', $this->app['config']->get('cms.seo.title.separator'));
        $this->app['config']->set('seotools.opengraph.defaults.title', false);
        $this->app['config']->set('seotools.opengraph.defaults.description', false);
        $this->app['config']->set('seotools.twitter.defaults.card', $this->app['config']->get('cms.seo.twitter.card'));
        $this->app['config']->set('seotools.twitter.defaults.site', $this->app['config']->get('cms.seo.twitter.site'));
    }

    private function bootingMacros(): void
    {
        MediaCollection::macro('when', function (bool $condition, Closure $callback) {
            if ($condition) {
                $callback($this);
            }

            return $this;
        });
    }
}
