<?php

namespace Bosnadev\Ronin\Providers;

use Bosnadev\Ronin\Ronin;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\ServiceProvider;

class RoninServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishResources();
    }

    public function register()
    {
        $this->app->singleton('ronin', function ($app) {
            $auth = $app->make(Guard::class);
            return new Ronin($auth);
        });
    }

    protected function publishResources()
    {
        // Ronin config path
        $config = realpath(__DIR__ . '/../../config/ronin.php');

        // Ronin database path
        $database = realpath(__DIR__ . '/../../database');

        // Merge Ronin's with the users defined configuration
        $this->mergeConfigFrom($config, 'ronin');

        // Publish configuration
        $this->publishes([
            $config => config_path('ronin.php'),
        ], 'ronin-config');

        // Publish migrations
        $this->publishes([
            $database . '/migrations' => database_path('/migrations'),
        ], 'ronin-migrations');

        // Publish seeds
        $this->publishes([
            $database . '/seeds' => database_path('/seeds'),
        ], 'ronin-seeds');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['ronin'];
    }
}