<?php

return [

    /*
    |--------------------------------------------------------------------------
    | S.E.O.
    |--------------------------------------------------------------------------
    */

    'seo' => [
        'meta_description_limit' => env('CMS_SEO_META_DESCRIPTION_LIMIT', 150),
        'title' => [
            'prefix' => env('CMS_SEO_TITLE_PREFIX', null),
            'separator' => env('CMS_SEO_TITLE_SEPARATOR', ' · '),
            'limit' => env('CMS_SEO_TITLE_LIMIT', 80),
        ],
        'opengraph' => [
            'enable' => env('CMS_SEO_OPENGRAPH_ENABLE', true),
        ],
        'twitter' => [
            'enable' => env('CMS_SEO_TWITTER_ENABLE', true),
            'card' => env('CMS_SEO_TWITTER_CARD', 'summary'),
            'site' => env('CMS_SEO_TWITTER_SITE', null),
        ],
        'schema_org' => [
            'enable' => env('CMS_SEO_SCHEMA_ORG_ENABLE', true),
        ],
        'sitemap' => [
            'frequency' => \Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_YEARLY,
            'priority' => 0.5,
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | User Model and Table Name
    |--------------------------------------------------------------------------
    */

    'users' => [
        'table' => 'users',
        'model' => \App\Models\User::class,
    ],


    /*
    |--------------------------------------------------------------------------
    | Page Model and Table Name
    |--------------------------------------------------------------------------
    */

    'pages' => [
        'table' => 'pages',
        'model' => \Lemaur\Cms\Models\Page::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Tag Table Name
    |--------------------------------------------------------------------------
    */

    'tags' => [
        'table' => 'tags',
        'pivot_key' => 'tag_id',
        'pivot_table_name' => 'taggable',
    ],


    /*
    |--------------------------------------------------------------------------
    | Navigation Model and Table Name
    |--------------------------------------------------------------------------
    */

    'navigations' => [
        'table' => 'navigations',
        'model' => \Lemaur\Cms\Models\Navigation::class,
    ],


    /*
    |--------------------------------------------------------------------------
    | Define a Domain
    |--------------------------------------------------------------------------
    */

    'domain' => env('CMS_DOMAIN', env('APP_DOMAIN', null)),


    /*
    |--------------------------------------------------------------------------
    | Reserved Domain Prefixes
    |--------------------------------------------------------------------------
    |
    | Those domain prefixes are not caught by social prefix url.
    |
    */

    'reserved_domain_prefixes' => [
        // 'nova',
    ],


    /*
    |--------------------------------------------------------------------------
    | Reserved Uri
    |--------------------------------------------------------------------------
    |
    | Those uri are not caught by cms route.
    |
    */

    'reserved_uri' => [
        'nova',
        'vapor-ui',
        'telescope',
        'horizon',
    ],


    /*
    |--------------------------------------------------------------------------
    | Markdown Configuration
    |--------------------------------------------------------------------------
    |
    | https://commonmark.thephpleague.com/1.5/customization/overview/
    |
    */

    'markdown' => [
        'options' => [
            // @TODO: markdown options
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Conversions
    |--------------------------------------------------------------------------
    */

    'media_conversions' => [
        'meta' => [
            'width' => 1200,
            'height' => 1200,
        ],

        '3:4' => [
            'aspect_ratio' => '3:4',
        ],

        '4:3' => [
            'aspect_ratio' => '4:3',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Media Collection Types
    |--------------------------------------------------------------------------
    | For mime types refer to the IANA archives.
    | https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types
    |
    */

    'media' => [
        'single_image' => [
            'only_keep_latest' => 1,
            'accepts_mime_types' => [
                'image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp', 'image/heic', 'image/heic-sequence', 'image/heif', 'image/heif-sequence', 'image/hej2k', 'image/hsj2', 'image/jp2',
            ],
        ],

        'single_video' => [
            'only_keep_latest' => 1,
            'accepts_mime_types' => [
                'video/x-msvideo', 'video/mp4', 'video/mpeg', 'video/ogg', 'video/webm', 'video/3gpp', 'video/3gpp2', 'video/quicktime', 'video/H265', 'video/H264', 'video/H263', 'video/H261',
            ],
        ],

        'multiple_images' => [
            'only_keep_latest' => null,
            'accepts_mime_types' => [
                'image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp', 'image/heic', 'image/heic-sequence', 'image/heif', 'image/heif-sequence', 'image/hej2k', 'image/hsj2', 'image/jp2',
            ],
        ],

        'multiple_videos' => [
            'only_keep_latest' => null,
            'accepts_mime_types' => [
                'video/x-msvideo', 'video/mp4', 'video/mpeg', 'video/ogg', 'video/webm', 'video/3gpp', 'video/3gpp2', 'video/quicktime', 'video/H265', 'video/H264', 'video/H263', 'video/H261',
            ],
        ],

        'multiple_documents' => [
            'only_keep_latest' => null,
            'accepts_mime_types' => [
                // text
                'application/rtf', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text',
                // pdf, epub
                'application/epub+zip', 'application/pdf',
                // presentation
                'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.oasis.opendocument.presentation',
                // spreadsheet
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.oasis.opendocument.spreadsheet', 'text/csv',
            ],
        ],

        'multiple_archives' => [
            'only_keep_latest' => null,
            'accepts_mime_types' => [
                'application/zip', 'application/x-7z-compressed', 'application/java-archive', 'application/gzip',
            ],
        ],
    ],
];
