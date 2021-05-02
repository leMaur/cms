# A simple CMS backbone for your next project

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lemaur/cms.svg?style=flat-square)](https://packagist.org/packages/lemaur/cms)
[![Total Downloads](https://img.shields.io/packagist/dt/lemaur/cms.svg?style=flat-square)](https://packagist.org/packages/lemaur/cms)
[![License](https://img.shields.io/packagist/l/lemaur/cms.svg?style=flat-square&color=yellow)](https://github.com/leMaur/cms/blob/master/LICENSE.md)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/lemaur/cms/run-tests?label=tests&style=flat-square)](https://github.com/lemaur/cms/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/lemaur/cms/Check%20&%20fix%20styling?label=code%20style&style=flat-square)](https://github.com/lemaur/cms/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![GitHub Sponsors](https://img.shields.io/github/sponsors/lemaur?style=flat-square&color=ea4aaa)](https://github.com/sponsors/leMaur)
[![Trees](https://img.shields.io/badge/dynamic/json?color=yellowgreen&style=flat-square&label=Trees&query=%24.total&url=https%3A%2F%2Fpublic.offset.earth%2Fusers%2Flemaur%2Ftrees)](https://ecologi.com/lemaur?r=6012e849de97da001ddfd6c9)

## Support Me

Hey folks,

Do you like this package? Do you find it useful and it fits well in your project?

I am glad to help you, and I would be so grateful if you considered supporting my work.

You can even choose 😃:
* You can [sponsor me 😎](https://github.com/sponsors/leMaur) with a monthly subscription.
* You can [buy me a coffee ☕ or a pizza 🍕](https://github.com/sponsors/leMaur?frequency=one-time&sponsor=leMaur) just for this package.
* You can [plant trees 🌴](https://ecologi.com/lemaur?r=6012e849de97da001ddfd6c9). By using this link we will both receive 30 trees for free and the planet (and me) will thank you. 
* You can "Star ⭐" this repository (it's free 😉).

## Installation

You can install the package via composer:
```bash
composer require lemaur/cms
```

You can launch the installation:
```bash
php artisan cms:install --force
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
You can find a good starting point at [getterms.io](https://getterms.io/).

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
