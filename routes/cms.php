<?php

use Illuminate\Support\Facades\Route;
use Lemaur\Cms\Http\Controllers\PageController;

Route::macro('cms', function (string $name, string $prefix): void {
    Route::prefix($prefix)
        ->domain(config('cms.domain', null))
        ->group(function () use ($name) {
            $prefixes = implode('|', config('cms.reserved_domain_prefixes', []));
            $regex = '^(?!'.$prefixes.')(.*)$';

            Route::get('/{slug?}', PageController::class)
                ->where('slug', $regex)
//                ->where('slug', '.*')
                ->name($name);
        });
});
