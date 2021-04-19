<?php

namespace Lemaur\Cms\Tests\Unit;

use Illuminate\Support\Facades\Route;
use Lemaur\Cms\Tests\TestCase;

class RouteTest extends TestCase
{
    /** @test */
    public function it_as_routes_defined(): void
    {
        self::assertEquals('http://localhost', route('cms'));
    }

    /** @test */
    public function it_has_personalised_routes(): void
    {
        Route::cms('/cms', 'custom-cms');

        self::assertEquals('http://localhost/cms', route('custom-cms'));
    }

    /** @test */
    public function it_may_as_social_routes(): void
    {
        Route::social('social');

        self::assertEquals('http://pinterest.localhost', route('social', 'pinterest'));
    }

    /** @test */
    public function it_may_as_personalized_social_routes(): void
    {
        Route::social('custom-social');

        self::assertEquals('http://pinterest.localhost', route('custom-social', 'pinterest'));
    }
}
