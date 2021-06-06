<?php

declare(strict_types=1);

namespace Lemaur\Cms\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Snapshots\MatchesSnapshots;

class TestCase extends Orchestra
{
    use MatchesSnapshots;

    protected Carbon $now;

    public function setUp(): void
    {
        parent::setUp();

        $this->now = Carbon::create('2016', '1', '1', '0', '0', '0');

        Carbon::setTestNow($this->now);

        $this->getDatabaseSetup($this->app);

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Lemaur\\Cms\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        Route::social('social');
        Route::cms('cms', '/');
    }

    protected function getPackageProviders($app)
    {
        return [
            \Lemaur\Cms\CmsServiceProvider::class,
            \Spatie\SchemalessAttributes\SchemalessAttributesServiceProvider::class,
            \Spatie\Tags\TagsServiceProvider::class,
            \Spatie\EloquentSortable\EloquentSortableServiceProvider::class,
            \Spatie\Sitemap\SitemapServiceProvider::class,
            \Lemaur\Sitemap\SitemapServiceProvider::class,
            \Artesaos\SEOTools\Providers\SEOToolsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:ygJ6fXnImxW2fktgrY06dCqkaVOuzxcSNI4jXWqB2E4=');
        $app['config']->set('app.url', 'http://website.com');

        $app['config']->set('cms', include __DIR__.'/../config/cms.php');
        $app['config']->set('cms.seo.twitter.site', '@username');
        $app['config']->set('cms.users.model', \Lemaur\Cms\Tests\Feature\User::class);

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('media-library', include __DIR__.'/../vendor/spatie/laravel-medialibrary/config/media-library.php');
        $app['config']->set('media-library.disk_name', 'local');
    }

    private function getDatabaseSetup($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->softDeletes();
        });

        include_once __DIR__.'/../vendor/spatie/laravel-medialibrary/database/migrations/create_media_table.php.stub';
        (new \CreateMediaTable())->up();

        include_once __DIR__.'/../vendor/spatie/laravel-tags/database/migrations/create_tag_tables.php.stub';
        (new \CreateTagTables())->up();

        include_once __DIR__.'/../database/migrations/create_cms_tables.php.stub';
        (new \CreateCmsTables())->up();
    }
}
