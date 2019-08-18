# Laravel Repository Eloquent

[![Latest Stable Version](https://poser.pugx.org/tbitencourt/laravel-repository-eloquent/v/stable)](https://packagist.org/packages/tbitencourt/laravel-repository-eloquent)
[![Total Downloads](https://poser.pugx.org/tbitencourt/laravel-repository-eloquent/downloads)](https://packagist.org/packages/tbitencourt/laravel-repository-eloquent)
[![Build Status](https://travis-ci.org/tbitencourt/laravel-repository-eloquent.png)](https://travis-ci.org/tbitencourt/laravel-repository-eloquent)
[![License](https://poser.pugx.org/tbitencourt/laravel-repository-eloquent/license)](https://packagist.org/packages/tbitencourt/laravel-repository-eloquent)

Easy MVC repository with Eloquent for Laravel 5, an useful tool to combine with Laravel Eloquent classes.

## Table of Contents

- <a href="#installation">Installation</a>
    - <a href="#laravel-compatibility">Laravel compatibility</a>
    - <a href="#composer">Composer</a>
    - <a href="#manually">Manually</a>
- <a href="#config">Config</a>
- <a href="#changelog">Changelog</a>
- <a href="#license">License</a>

## Installation

### Laravel compatibility

 Laravel      | laravel-repository-eloquent
:-------------|:----------
 5.2.x-5.8.x (PHP 7 required) | 1.0.x

### Composer

Install the package via composer: `composer require tbitencourt/laravel-repository-eloquent`

### Manually

In Laravel 5.5, the service provider and facade will automatically get registered. For older versions of the framework, follow the steps below:

Register the service provider in `config/app.php`

```php
        'providers' => [
		// [...]
                Tbitencourt\LaravelRepositoryEloquent\Providers\RepositoryEloquentServiceProvider::class,
        ],
```

You may also register the `LaravelRepositoryEloquent` facade:

```php
        'aliases' => [
		// [...]
                'LaravelRepositoryEloquent' => Tbitencourt\LaravelRepositoryEloquent\Facades\LaravelRepositoryEloquent::class,
        ],
```

## Config

In order to edit the default configuration (where for e.g. you can find `supportedLocales`) for this package you may execute:

```
php artisan vendor:publish --provider="Tbitencourt\LaravelRepositoryEloquent\Providers\RepositoryEloquentServiceProvider"
```

After that, `config/laravelrepositoryeloquent.php` will be created. Inside this file you will find all the fields that can be edited in this package.

## Changelog

View changelog here -> [changelog](CHANGELOG.md)

## License

Laravel Localization is an open-sourced laravel package licensed under the MIT license
