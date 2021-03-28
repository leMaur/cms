<?php

namespace Lemaur\Cms\Tests\Unit;

use Illuminate\Support\Facades\Route;
use Lemaur\Cms\Tests\TestCase;

class RouteTest extends TestCase
{
    /** @test */
    public function it_as_routes_defined()
    {
        $this->assertEquals('http://localhost', route('cms'));
    }

    /** @test */
    public function it_has_personalised_routes()
    {
        Route::cms('/cms', 'custom');

        $this->assertEquals('http://localhost/cms', route('custom'));
    }
}
