<?php

use Illuminate\Support\Facades\Route;
use Lemaur\Cms\Http\Controllers\SocialController;

Route::macro('cms', function (string $prefix, string $name) {
    Route::prefix($prefix)
        ->domain(config('cms.domain', null))
        ->group(function () use ($name) {
            Route::get('/{slug?}', SocialController::class)
                ->where('slug', '.*')
                ->name($name);
        });
});
