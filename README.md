# A simple CMS backbone for your next website

[![Latest Stable Version](https://poser.pugx.org/lemaur/laravel-cms/v)](//packagist.org/packages/lemaur/laravel-cms)
[![Total Downloads](https://poser.pugx.org/lemaur/laravel-cms/downloads)](//packagist.org/packages/lemaur/laravel-cms)
[![License](https://poser.pugx.org/lemaur/laravel-cms/license)](//packagist.org/packages/lemaur/laravel-cms)
![Tests](https://github.com/leMaur/laravel-cms/workflows/Tests/badge.svg)
[![Treeware](https://img.shields.io/badge/dynamic/json?color=brightgreen&label=Treeware&query=%24.total&url=https%3A%2F%2Fpublic.offset.earth%2Fusers%2Ftreeware%2Ftrees)](https://treeware.earth)


## Installation

You can install the package via composer:

```bash
composer require lemaur/cms
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Lemaur\Cms\CmsServiceProvider" --tag="cms-migrations"
php artisan migrate
```

You can use the route with:

Open `routes/web.php` and replace the content with:
```blade
Route::social('social');
Route::cms('cms', '/');
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Lemaur\Cms\CmsServiceProvider" --tag="cms-config"

# You also need this 3rd party configuration file.
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="config"
```

You can publish views with:
```bash
php artisan vendor:publish --provider="Lemaur\Cms\CmsServiceProvider" --tag="cms-views"
```

### Apply a sensible default

Add `domain` to your "config/app.php". I advise you to append it after `asset_url`. 
```php
/*
|--------------------------------------------------------------------------
| Application URL
|--------------------------------------------------------------------------
|
| This URL is used by the console to properly generate URLs when using
| the Artisan command line tool. You should set this to the root of
| your application so that it is used when running Artisan tasks.
|
*/

'url' => env('APP_URL', 'http://localhost'),

'asset_url' => env('ASSET_URL', null),

'domain' => env('APP_DOMAIN', 'localhost'),
```

Change your `.env` file like:
```
APP_DOMAIN=your-website.com
APP_URL=https://${APP_DOMAIN}

CMS_DOMAIN=${APP_DOMAIN}
#NOVA_DOMAIN_NAME=nova.${APP_DOMAIN} # <- this is optional. Uncomment if you use Laravel Nova and use the prefix you like.

...

SESSION_DOMAIN=.${APP_DOMAIN}
```

If you plan to use Laravel Nova, you have to change the `path` on "config/nova.php" file to `'/'` instead of the original value `'/nova'`.
```php
/*
|--------------------------------------------------------------------------
| Nova Path
|--------------------------------------------------------------------------
|
| This is the URI path where Nova will be accessible from. Feel free to
| change this path to anything you like. Note that this URI will not
| affect Nova's internal API routes which aren't exposed to users.
|
*/

'path' => '/',
```

### Increase security

Change values of `encrypt` and `same_site` on your "config/session.php" file.
```bash
'encrypt' => true, // <= automatically encrypt your cookies

...

'same_site' => 'strict', // <= the cookie will not be sent along with requests initiated by third party websites
```

### Need some pages to start with

```bash
php artisan db:seed
```

### Need to generate "Terms of service" and "Privacy Policy"
You can find a good starting point at [getterms.io](https://getterms.io/). They offer T.o.S. for small websites for free.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Maurizio](https://github.com/lemaur)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Treeware

If you use it in production, then I ask that you [**buy the world a tree**](https://plant.treeware.earth/leMaur/eloquent-publishing) to thank me for my work. By contributing to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.

Read more about [Treeware](https://treeware.earth).

## Wall of Fame
- [Seo Tools by Artesaos](https://github.com/artesaos/seotools)
- [Common Mark by League](https://github.com/league/commonmark)
- [Eloquent Publishing by Lemaur (it's me :sunglasses:)](https://github.com/lemaur/eloquent-publishing)
- [Laravel Image Sitemap by Lemaur (it's me :sunglasses:)](https://github.com/lemaur/laravel-image-sitemap)
- [Eloquent Sortable by Spatie](https://github.com/spatie/eloquent-sortable)
- [Laravel Media Library by Spatie](https://github.com/spatie/laravel-medialibrary)
- [Laravel Schemaless Attributes by Spatie](https://github.com/spatie/laravel-schemaless-attributes)
- [Laravel Sluggable by Spatie](https://github.com/spatie/laravel-sluggable)
- [Laravel Tags by Spatie](https://github.com/spatie/laravel-tags)
- [Laravel View Models by Spatie](https://github.com/spatie/laravel-view-models)
