<?php

namespace Lemaur\Cms\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Lemaur\Cms\CmsServiceProvider;
use Lemaur\Cms\Models\Navigation;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Tests\Feature\User;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\EloquentSortable\EloquentSortableServiceProvider;
use Spatie\SchemalessAttributes\SchemalessAttributesServiceProvider;
use Spatie\Tags\TagsServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->getDatabaseSetup($this->app);

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Lemaur\\Cms\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        Route::cms('/', 'cms');
    }

    protected function getPackageProviders($app)
    {
        return [
            CmsServiceProvider::class,
            SchemalessAttributesServiceProvider::class,
            TagsServiceProvider::class,
            EloquentSortableServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('cms', include __DIR__.'/../config/cms.php');
        $app['config']->set('cms.users.model', User::class);

        $app['config']->set('app.key', 'base64:ygJ6fXnImxW2fktgrY06dCqkaVOuzxcSNI4jXWqB2E4=');

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('media-library.disk_name', 'local');
        $app['config']->set('media-library.max_file_size', 1024 * 1024 * 10);
        $app['config']->set('media-library.media_model', \Spatie\MediaLibrary\MediaCollections\Models\Media::class);
        $app['config']->set('media-library.path_generator', \Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator::class);
    }

    private function getDatabaseSetup($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->softDeletes();
        });

        include_once __DIR__.'/../database/migrations/create_cms_tables.php.stub';
        (new \CreateCmsTables())->up();

        include_once __DIR__.'/../vendor/spatie/laravel-medialibrary/database/migrations/create_media_table.php.stub';
        (new \CreateMediaTable())->up();
    }
}
