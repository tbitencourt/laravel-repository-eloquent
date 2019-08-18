<?php

namespace Tbitencourt\LaravelRepositoryEloquent\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryEloquentServiceProvider
 * @package Tbitencourt\LaravelRepositoryEloquent
 * @author  Thales Bitencourt
 */
class RepositoryEloquentServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     * @var bool
     */
    protected $defer = true;

    /**
     * Register services.
     * The register() method is used to bind any classes or functionality into the app container
     * @return void
     */
    public function register()
    {
        if ($this->isLumen()) {
            $this->app->configure('repository');
        } else {
            $this->publishes(
                [
                    __DIR__ . '/../../config/config.php' => config_path('repository.php'),
                ], 'config'
            );
        }
    }

    /**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return ['repository.factory'];
    }

    /**
     * Bootstrap services.
     * The boot() method is used to boot any routes, event listeners, or any other functionality you want to add to
     * your package
     * @return void
     */
    public function boot()
    {
        $packageConfigFile = __DIR__ . '/../../config/config.php';
        $this->mergeConfigFrom(
            $packageConfigFile, 'repository'
        );
    }

    /**
     * @return bool
     */
    private function isLumen()
    {
        return true === str_contains($this->app->version(), 'Lumen');
    }
}
