<?php

namespace Lemaur\Cms\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Route;
use Lemaur\Cms\CmsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\EloquentSortable\EloquentSortableServiceProvider;
use Spatie\SchemalessAttributes\SchemalessAttributesServiceProvider;
use Spatie\Tags\TagsServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

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
        $app['config']->set('app.key', 'base64:cleW+WFKXjFtOo55Vz5SvfFGxDWc+vmr7kk2G5JYHqU=');
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        include_once __DIR__.'/../database/migrations/create_cms_tables.php.stub';
        (new \CreateCmsTables())->up();
    }
}
