<?php

namespace Lemaur\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Lemaur\Cms\Models\Page;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition()
    {
        $title = $this->faker->sentence;

        return [
            'parent' => null,
            'slug' => Str::slug($title),
            'title' => $title,
            'content' => $this->faker->paragraphs(10, true),
            'layout' => 'page',
        ];
    }

    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'published_at' => now()->subMonth(),
            ];
        });
    }
}
