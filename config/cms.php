<?php

return [

    /*
    |--------------------------------------------------------------------------
    | S.E.O.
    |--------------------------------------------------------------------------
    */

    'seo' => [
        'title' => [
            'prefix' => env('CMS_SEO_TITLE_PREFIX', 'Biophilic & Sustainable Interior Design'),
            'separator' => env('CMS_SEO_TITLE_SEPARATOR', '·'),
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Define User Model and Migration
    |--------------------------------------------------------------------------
    */

    'users' => [
        'table' => 'users',
        'model' => \App\Models\User::class,
    ],


    /*
    |--------------------------------------------------------------------------
    | Define Page Model and Migration
    |--------------------------------------------------------------------------
    */

    'pages' => [
        'table' => 'pages',
        'model' => \Lemaur\Cms\Models\Page::class,
    ],


    /*
    |--------------------------------------------------------------------------
    | Define Navigation Model and Migration
    |--------------------------------------------------------------------------
    */

    'navigations' => [
        'table' => 'navigations',
        'model' => \Lemaur\Cms\Models\Navigation::class,
    ],


    /*
    |--------------------------------------------------------------------------
    | Define a domain
    |--------------------------------------------------------------------------
    */

    'domain' => env('CMS_DOMAIN', null),


    /*
    |--------------------------------------------------------------------------
    | Define markdown configuration
    |--------------------------------------------------------------------------
    |
    | https://commonmark.thephpleague.com/1.5/customization/overview/
    |
    */

    'markdown' => [
        'options' => [
            // @TODO
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Define Media Collections
    |--------------------------------------------------------------------------
    | For mime types refer to the IANA archives.
    | https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types
    |
    */

    'media' => [
        'single_image' => [
            'name' => 'cover',  // this will generate a media collection name like "{model}.cover"
            'only_keep_latest' => 1,
            'accepts_mime_types' => [
                'image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp', 'image/heic', 'image/heic-sequence', 'image/heif', 'image/heif-sequence', 'image/hej2k', 'image/hsj2', 'image/jp2',
            ],
        ],

        'collection_image' => [
            'name' => 'image.collection',
            'only_keep_latest' => null,
            'accepts_mime_types' => [
                'image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp', 'image/heic', 'image/heic-sequence', 'image/heif', 'image/heif-sequence', 'image/hej2k', 'image/hsj2', 'image/jp2',
            ],
        ],

        'collection_video' => [
            'name' => 'video.collection',
            'only_keep_latest' => null,
            'accepts_mime_types' => [
                'video/x-msvideo', 'video/mp4', 'video/mpeg', 'video/ogg', 'video/webm', 'video/3gpp', 'video/3gpp2', 'video/quicktime', 'video/H265', 'video/H264', 'video/H263', 'video/H261',
            ],
        ],

        'collection_document' => [
            'name' => 'document.collection',
            'only_keep_latest' => null,
            'accepts_mime_types' => [
                'application/rtf', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text',
                'application/epub+zip', 'application/pdf',
                'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.oasis.opendocument.presentation',
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.oasis.opendocument.spreadsheet', 'text/csv',
            ],
        ],

        'collection_archive' => [
            'name' => 'archive.collection',
            'only_keep_latest' => null,
            'accepts_mime_types' => [
                'application/zip', 'application/x-7z-compressed', 'application/java-archive', 'application/gzip',
            ],
        ],
    ],
];
