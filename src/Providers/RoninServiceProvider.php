<?php

namespace Bosnadev\Ronin\Providers;

use Bosnadev\Ronin\Ronin;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\ServiceProvider;
use Bosnadev\Ronin\Contracts\Role as RoleContract;
use Bosnadev\Ronin\Contracts\Scope as ScopeContract;

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

        // Merge Ronin's with the users defined configuration
        $this->mergeConfigFrom(__DIR__ . '/../../config/ronin.php', 'ronin');

        // Register Ronin's bindings (models, repositories etc.)
        $this->registerBindings();
    }

    protected function publishResources()
    {
        // Ronin config path
        $config = realpath(__DIR__ . '/../../config/ronin.php');

        // Ronin database path
        $database = realpath(__DIR__ . '/../../database');

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

    protected function registerBindings()
    {
        $this->app->bind(RoleContract::class, config('ronin.roles.model'));
        $this->app->bind(ScopeContract::class, config('ronin.scopes.model'));
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
