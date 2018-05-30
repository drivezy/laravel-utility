<?php

namespace Drivezy\LaravelUtility;

use Illuminate\Support\ServiceProvider;

class LaravelUtilityServiceProvider extends ServiceProvider {
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot () {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');

        $this->publishes([
            __DIR__ . '/Config/Utility.php' => config_path('utility.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register () {
    }
}
