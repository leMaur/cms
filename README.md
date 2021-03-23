# A simple CMS backend functionality for Laravel

[![Latest Stable Version](https://poser.pugx.org/lemaur/laravel-cms/v)](//packagist.org/packages/lemaur/laravel-cms)
[![Total Downloads](https://poser.pugx.org/lemaur/laravel-cms/downloads)](//packagist.org/packages/lemaur/laravel-cms)
[![License](https://poser.pugx.org/lemaur/laravel-cms/license)](//packagist.org/packages/lemaur/laravel-cms)
![Tests](https://github.com/leMaur/laravel-cms/workflows/Tests/badge.svg)
[![Treeware](https://img.shields.io/badge/dynamic/json?color=brightgreen&label=Treeware&query=%24.total&url=https%3A%2F%2Fpublic.offset.earth%2Fusers%2Ftreeware%2Ftrees)](https://treeware.earth)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require lemaur/laravel-cms
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Lemaur\Cms\CmsServiceProvider" --tag="cms-migrations"
php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider" --tag="tags-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Lemaur\Cms\CmsServiceProvider" --tag="cms-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$laravel-cms = new Lemaur\Cms();
echo $laravel-cms->echoPhrase('Hello, Lemaur!');
```

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

## Battery Included
- https://github.com/leMaur/eloquent-publishing
- https://github.com/spatie/laravel-tags
- https://github.com/spatie/eloquent-sortable
- https://github.com/michaeldyrynda/laravel-efficient-uuid
- https://github.com/michaeldyrynda/laravel-model-uuid
