<?php

namespace Hemantanshu\LaravelUtility;

use Illuminate\Support\ServiceProvider;

class LaravelUtilityServiceProvider extends ServiceProvider {
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot () {
        $this->publishes([
            __DIR__ . '/Migrations' => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register () {
    }
}
