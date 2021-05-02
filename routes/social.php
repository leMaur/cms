<?php

use Illuminate\Support\Facades\Route;
use Lemaur\Cms\Http\Controllers\SocialController;

Route::macro('social', function (string $name): void {
    Route::domain('{social}.'.config('app.domain'))
        ->group(function () use ($name): void {
            $prefixes = (array) config('cms.reserved_domain_prefixes', []);
            $regex = $prefixes !== [] ? '^(?!'.implode('|', $prefixes).')([a-z]+)$' : '[a-z]+';

            Route::get('/', SocialController::class)
                ->where('social', $regex)
                ->name($name);
        });
});
