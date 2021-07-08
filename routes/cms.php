<?php

use Illuminate\Support\Facades\Route;
use Lemaur\Cms\Http\Controllers\PageController;

Route::prefix((string) config('cms.routes.main.prefix', '/'))
    ->middleware((array) config('cms.routes.main.middleware', ['web']))
    ->group(function () {
        $reservedUri = (array) config('cms.routes.main.reserved_uri', []);
        $regex = $reservedUri !== [] ? '^(?!'.implode('|', $reservedUri).')(.*)$' : '.*';

        Route::get('/{page?}', PageController::class)
            ->where('page', $regex)
            ->name((string) config('cms.routes.main.name', 'cms'));
    });
