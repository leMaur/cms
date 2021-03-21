<?php

namespace Lemaur\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Lemaur\Cms\Models\Page;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition()
    {
        $title = $this->faker->sentence;

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'content' => $this->faker->paragraphs(4, true),
        ];
    }
}
