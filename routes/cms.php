<?php

use Illuminate\Support\Facades\Route;
use Lemaur\Cms\Http\Controllers\PageController;

Route::macro('cms', function (string $name, string $prefix): void {
    Route::prefix($prefix)
        ->group(function () use ($name) {
            $reservedUri = (array) config('cms.reserved_uri', []);
            $regex = $reservedUri !== [] ? '^(?!'.implode('|', $reservedUri).')(.*)$' : '.*';

            Route::get('/{page?}', PageController::class)
                ->where('page', $regex)
                ->name($name);
        });
});
