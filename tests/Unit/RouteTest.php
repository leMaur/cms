<?php

declare(strict_types=1);

namespace Lemaur\Cms\Tests\Unit;

use Illuminate\Support\Facades\Config;
use Lemaur\Cms\Tests\TestCase;

class RouteTest extends TestCase
{
    /** @test */
    public function it_as_cms_route(): void
    {
        self::assertEquals('http://localhost', route('cms'));
    }

    /** @test */
    public function it_has_personalised_cms_route(): void
    {
        Config::set('cms.routes.main.name', 'custom-cms');
        Config::set('cms.routes.main.prefix', '/cms');

        self::assertEquals('http://localhost/cms', route('custom-cms'));
    }

    /** @test */
    public function it_may_as_social_route(): void
    {
        Config::set('app.url', 'http://website.com');

        self::assertEquals('http://pinterest.website.com', route('social', 'pinterest'));
    }

    /** @test */
    public function it_may_as_personalized_social_route(): void
    {
        Config::set('app.url', 'http://website.com');
        Config::set('cms.routes.social.name', 'custom-social');

        self::assertEquals('http://pinterest.website.com', route('custom-social', 'pinterest'));
    }

    /** @test */
    public function it_may_as_social_route_on_prefixed_url(): void
    {
        Config::set('app.url', 'http://preview.website.com');

        self::assertEquals('http://pinterest-preview.website.com', route('social', 'pinterest'));
    }
}
