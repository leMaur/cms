<?php

declare(strict_types=1);

namespace Lemaur\Cms\Support\Services;

use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class CacheStore
{
    public function __construct(
        private ?string                                 $key = null,
        private DateTimeInterface|DateInterval|int|null $ttl = null,
        private bool                                    $forever = false,
        private array                                   $tags = [],
        private ?Closure                                $remember = null,
    )
    {
    }

    public function key(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function tags(array $tags): static
    {
        $this->tags = $tags;

        return $this;
    }

    public function remember(Closure $callback): static
    {
        $this->remember = $callback;

        return $this;
    }

    public function for(DateTimeInterface|DateInterval|int $ttl): static
    {
        $this->ttl = $ttl;

        return $this;
    }

    public function minute(): static
    {
        $this->ttl = Carbon::now()->addMinute()->timestamp;

        return $this;
    }

    public function fiveMinutes(): static
    {
        $this->ttl = Carbon::now()->addMinutes(5)->timestamp;

        return $this;
    }

    public function tenMinutes(): static
    {
        $this->ttl = Carbon::now()->addMinutes(10)->timestamp;

        return $this;
    }

    public function fifteenMinutes(): static
    {
        $this->ttl = Carbon::now()->addMinutes(15)->timestamp;

        return $this;
    }

    public function twentyMinutes(): static
    {
        $this->ttl = Carbon::now()->addMinutes(20)->timestamp;

        return $this;
    }

    public function twentyFiveMinutes(): static
    {
        $this->ttl = Carbon::now()->addMinutes(25)->timestamp;

        return $this;
    }

    public function thirtyMinutes(): static
    {
        $this->ttl = Carbon::now()->addMinutes(30)->timestamp;

        return $this;
    }

    public function hour(): static
    {
        $this->ttl = Carbon::now()->addHour()->timestamp;

        return $this;
    }

    public function twoHours(): static
    {
        $this->ttl = Carbon::now()->addHours(2)->timestamp;

        return $this;
    }

    public function fourHours(): static
    {
        $this->ttl = Carbon::now()->addHours(4)->timestamp;

        return $this;
    }

    public function eightHours(): static
    {
        $this->ttl = Carbon::now()->addHours(8)->timestamp;

        return $this;
    }

    public function day(): static
    {
        $this->ttl = Carbon::now()->addDay()->timestamp;

        return $this;
    }

    public function week(): static
    {
        $this->ttl = Carbon::now()->addWeek()->timestamp;

        return $this;
    }

    public function month(): static
    {
        $this->ttl = Carbon::now()->addMonth()->timestamp;

        return $this;
    }

    public function quarter(): static
    {
        $this->ttl = Carbon::now()->addQuarter()->timestamp;

        return $this;
    }

    public function year(): static
    {
        $this->ttl = Carbon::now()->addYear()->timestamp;

        return $this;
    }

    public function forever(): static
    {
        $this->forever = true;

        return $this;
    }

    private function execute(): mixed
    {
        if ($this->forever) {
            if ($this->tags !== []) {
                return Cache::tags($this->tags)->rememberForever($this->key, $this->remember);
            }

            return Cache::rememberForever($this->key, $this->remember);
        }

        if ($this->tags !== []) {
            return Cache::tags($this->tags)->remember($this->key, $this->ttl, $this->remember);
        }

        return Cache::remember($this->key, $this->ttl, $this->remember);
    }

    public function __destruct()
    {
        if (! (bool)$this->key) {
            throw new \InvalidArgumentException('A `key` should be provided.');
        }

        if (! (bool)$this->ttl && $this->forever === false) {
            throw new \InvalidArgumentException('A `ttl` should be provided.');
        }

        if (! (bool)$this->remember) {
            throw new \InvalidArgumentException('A `callback` should be provided.');
        }

        return $this->execute();
    }
}
