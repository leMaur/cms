<?php

use Illuminate\Support\Facades\Route;
use Lemaur\Cms\Http\Controllers\SocialController;

$domain = collect(explode('.', parse_url(config('app.url', ''), PHP_URL_HOST), 3));
$sep = $domain->count() === 3 ? '-' : '.';

Route::domain('{social}'.$sep.$domain->join('.'))
    ->middleware((array) config('cms.routes.social.middleware', ['web']))
    ->group(function () : void {
        $prefixes = (array) config('cms.routes.social.reserved_domain_prefixes', []);
        $regex = $prefixes !== [] ? '^(?!'.implode('|', $prefixes).')([a-z]+)$' : '[a-z]+';

        Route::get('/', SocialController::class)
            ->where('social', $regex)
            ->name((string) config('cms.routes.social.name', 'social'));
    });
