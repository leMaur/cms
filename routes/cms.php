<?php

use Illuminate\Support\Facades\Route;
use Lemaur\Cms\Http\Controllers\PageController;

Route::macro('cms', function (string $name, string $prefix): void {
    Route::prefix($prefix)
        ->domain(config('cms.domain', null))
        ->group(function () use ($name) {
            Route::get('/{slug?}', PageController::class)
                ->where('slug', '.*')
                ->name($name);
        });
});
