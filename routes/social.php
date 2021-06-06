<?php

use Illuminate\Support\Facades\Route;
use Lemaur\Cms\Http\Controllers\SocialController;

Route::macro('social', function (string $name): void {
    $domain = collect(explode('.', parse_url(config('app.url', ''), PHP_URL_HOST), 3));

    $sep = $domain->count() === 3 ? '-' : '.';

    Route::domain('{social}'.$sep.$domain->join('.'))
        ->group(function () use ($name): void {
            $prefixes = (array) config('cms.reserved_domain_prefixes', []);
            $regex = $prefixes !== [] ? '^(?!'.implode('|', $prefixes).')([a-z]+)$' : '[a-z]+';

            Route::get('/', SocialController::class)
                ->where('social', $regex)
                ->name($name);
        });
});
