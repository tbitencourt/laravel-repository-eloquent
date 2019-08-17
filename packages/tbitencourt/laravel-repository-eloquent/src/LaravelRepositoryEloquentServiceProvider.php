<?php

namespace Tbitencourt\LaravelRepositoryEloquent;

use Illuminate\Support\ServiceProvider;

/**
 * Class LaravelRepositoryEloquentServiceProvider
 * @package Tbitencourt\LaravelRepositoryEloquent
 * @author Thales Bitencourt
 */
class LaravelRepositoryEloquentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * The register() method is used to bind any classes or functionality into the app container
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     * The boot() method is used to boot any routes, event listeners, or any other functionality you want to add to your package
     *
     * @return void
     */
    public function boot()
    {
        //
        include __DIR__.'/routes.php';
    }
}
