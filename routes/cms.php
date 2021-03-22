<?php

use Illuminate\Support\Facades\Route;
use Lemaur\Cms\Http\Controllers\PageController;

Route::macro('cms', function (string $prefix, string $name, array $middleware = ['web']) {
    Route::prefix($prefix)
        ->middleware($middleware)
        ->group(function () use ($name) {
            Route::get('/{slug?}', PageController::class)
                ->where('slug', '.*')
                ->name($name);
        });
});
