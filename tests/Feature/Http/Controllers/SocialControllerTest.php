<?php

namespace Lemaur\Cms\Tests\Feature\Http\Controllers;

use Lemaur\Cms\Models\Navigation;
use Lemaur\Cms\Tests\TestCase;

class SocialControllerTest extends TestCase
{
    /** @test */
    public function it_redirects_to_the_given_social_network(): void
    {
        Navigation::factory()->social()->create(['name' => 'Pinterest', 'url' => 'https://www.pinterest.com']);

        $this->get(route('social', 'PINTEREST'))->assertRedirect('https://www.pinterest.com');
    }

    /** @test */
    public function it_redirects_to_a_404_page_if_the_given_social_network_is_not_in_the_list(): void
    {
        $this->get(route('social', 'PINTEREST'))->assertNotFound();
    }
}
