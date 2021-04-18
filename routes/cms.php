<?php

use Illuminate\Support\Facades\Route;
use Lemaur\Cms\Http\Controllers\PageController;

Route::macro('cms', function (string $prefix, string $name) {
    Route::prefix($prefix)
        ->domain(config('cms.domain', null))
        ->group(function () use ($name) {
            Route::get('/{slug?}', PageController::class)
                ->where('slug', '.*')
                ->name($name);
        });
});
