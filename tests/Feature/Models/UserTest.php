<?php

namespace Lemaur\Cms\Tests\Feature\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Lemaur\Cms\Models\Page;
use Lemaur\Cms\Tests\Feature\User;
use Lemaur\Cms\Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function it_may_has_pages(): void
    {
        $user = User::create(['email' => 'maurizio@example.com']);
        $user->pages()->create(['title' => 'My PageRepository']);

        self::assertInstanceOf(HasMany::class, $user->pages());
        self::assertInstanceOf(Collection::class, $user->pages);
        self::assertCount(1, $user->pages);

        $user->pages()->create(['title' => 'Another PageRepository Too']);

        self::assertCount(2, $user->fresh()->pages);
        self::assertInstanceOf(Page::class, $user->pages->first());
    }
}
