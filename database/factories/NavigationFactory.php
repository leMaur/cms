<?php

namespace Lemaur\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lemaur\Cms\Models\Navigation;

class NavigationFactory extends Factory
{
    protected $model = Navigation::class;

    public function definition()
    {
        return [
            'page_id' => null,
            'name' => null,
            'url' => null,
            'type' => Navigation::PRIMARY,
        ];
    }

    public function social()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Navigation::SOCIAL,
            ];
        });
    }

    public function secondary()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Navigation::SECONDARY,
            ];
        });
    }
}
