<?php

return [

    /*
    |--------------------------------------------------------------------------
    | xxx
    |--------------------------------------------------------------------------
    |
    | xxx
    |
    */

    'users' => [
        'table' => 'users',
        'model' => 'App\Models\User',
    ],


    /*
    |--------------------------------------------------------------------------
    | xxx
    |--------------------------------------------------------------------------
    |
    | xxx
    |
    */

    'pages' => [
        'table' => 'pages',
        'model' => 'Lemaur\Cms\Models\Page',
    ],


    /*
    |--------------------------------------------------------------------------
    | xxx
    |--------------------------------------------------------------------------
    |
    | xxx
    |
    */

    'excluded_routes' => [
        '/nova',
    ],


    /*
    |--------------------------------------------------------------------------
    | xxx
    |--------------------------------------------------------------------------
    |
    | xxx
    |
    */

    'domain' => env('CMS_DOMAIN', null),
];
