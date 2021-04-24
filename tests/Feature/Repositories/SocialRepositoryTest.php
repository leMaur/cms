<?php

namespace Lemaur\Cms\Tests\Feature\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lemaur\Cms\Models\Navigation;
use Lemaur\Cms\Repositories\SocialRepository;
use Lemaur\Cms\Tests\TestCase;

class SocialRepositoryTest extends TestCase
{
    /** @test */
    public function it_returns_the_given_social(): void
    {
        Navigation::factory()->social()->create([
            'name' => 'Instagram',
            'url' => 'https://instagram.com',
        ]);

        $social = (new SocialRepository(new Navigation()))->find('instagram');
        self::assertEquals('Instagram', $social->name);
        self::assertEquals('https://instagram.com', $social->url);
    }

    /** @test */
    public function it_gets_404_page_if_social_is_not_found(): void
    {
        $this->expectException(ModelNotFoundException::class);

        (new SocialRepository(new Navigation()))->find('not-valid-social');
    }
}
