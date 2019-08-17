<?php

namespace Tbitencourt\LaravelRepositoryEloquent;

use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryEloquentServiceProvider
 * @package Tbitencourt\LaravelRepositoryEloquent
 * @author  Thales Bitencourt
 */
class RepositoryEloquentServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    private $defaultConfigName = 'repository';
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
            $this->app->configure($this->defaultConfigName);
        } else {
            $this->publishes(
                [
                    __DIR__ . '/../config/config.php' => config_path($this->defaultConfigName . '.php'),
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
        return [$this->defaultConfigName . '.factory'];
    }

    /**
     * Bootstrap services.
     * The boot() method is used to boot any routes, event listeners, or any other functionality you want to add to
     * your package
     * @return void
     */
    public function boot()
    {
        $packageConfigFile = __DIR__ . '/../config/config.php';
        $this->mergeConfigFrom(
            $packageConfigFile, $this->defaultConfigName
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
