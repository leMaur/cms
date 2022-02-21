<?php

declare(strict_types=1);

namespace Lemaur\Cms\Tests\Feature\Support\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use Lemaur\Cms\Support\Services\CacheStore;
use Lemaur\Cms\Tests\TestCase;

class CacheStoreTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_if_key_is_not_provided(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new CacheStore())
            ->for(3600)
            ->remember(fn() => 'hello world');
    }

    /** @test */
    public function it_throws_an_exception_if_ttl_is_not_provided(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new CacheStore())
            ->key('foo')
            ->remember(fn() => 'hello world');
    }

    /** @test */
    public function it_may_has_tags(): void
    {
        Cache::shouldReceive('tags')
            ->withSomeOfArgs(['baz', 'bar'])
            ->andReturnSelf()
            ->shouldReceive('remember')
            ->once()
            ->withSomeOfArgs('foo', 3600)
            ->andReturn('hello world');

        (new CacheStore())
            ->key('foo')
            ->tags(['baz', 'bar'])
            ->for(3600)
            ->remember(fn() => 'hello world');
    }

    /** @test */
    public function it_caches_forever(): void
    {
        Carbon::setTestNow();

        Cache::shouldReceive('rememberForever')
            ->twice()
            ->withSomeOfArgs('foo')
            ->andReturn('hello world');

        $return = (new CacheStore())
            ->key('foo')
            ->forever()
            ->remember(fn() => 'hello world');

        $this->assertEquals($return, 'hello world');

        $return = cacheStore('foo')
            ->forever()
            ->remember(fn() => 'hello world');

        $this->assertEquals($return, 'hello world');
    }

    /** @test */
    public function it_caches_for(): void
    {
        Carbon::setTestNow();

        $timestamp = Carbon::now()->addMinutes(14)->timestamp;

        Cache::shouldReceive('remember')
            ->twice()
            ->withSomeOfArgs('foo', $timestamp)
            ->andReturn('hello world');

        (new CacheStore())
            ->key('foo')
            ->for($timestamp)
            ->remember(fn() => 'hello world');

        cacheStore('foo')
            ->for($timestamp)
            ->remember(fn() => 'hello world');
    }

    /**
     * @test
     * @dataProvider cacheTtlData
     */
    public function it_caches_for_period_of_time($method, $ttl): void
    {
        Carbon::setTestNow();

        Cache::shouldReceive('remember')
            ->twice()
            ->withSomeOfArgs('foo', Carbon::now()->addMinutes($ttl)->timestamp)
            ->andReturn('hello world');

        (new CacheStore())
            ->key('foo')
            ->$method()
            ->remember(fn() => 'hello world');

        cacheStore('foo')
            ->$method()
            ->remember(fn() => 'hello world');
    }

    public function cacheTtlData(): array
    {
        return [
            ['minute', 1],
            ['fiveMinutes', 5],
            ['tenMinutes', 10],
            ['fifteenMinutes', 15],
            ['twentyMinutes', 20],
            ['twentyFiveMinutes', 25],
            ['thirtyMinutes', 30],
            ['hour', 60],
            ['twoHours', 60 * 2],
            ['fourHours', 60 * 4],
            ['eightHours', 60 * 8],
            ['day', 60 * 24],
            ['week', 60 * 24 * 7],
            ['month', 60 * 24 * 28],
            ['quarter', 60 * 24 * 89],
            ['year', 60 * 24 * 365],
        ];
    }
}
