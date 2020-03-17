<?php

namespace Drivezy\LaravelUtility;

use Drivezy\LaravelUtility\Commands\EventQueueProcessorCommand;
use Drivezy\LaravelUtility\Library\Message;
use Illuminate\Support\ServiceProvider;

class LaravelUtilityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot ()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');

        $this->publishes([
            __DIR__ . '/Config/utility.php' => config_path('utility.php'),
        ]);

        //load command defined in the system
        if ( $this->app->runningInConsole() ) {
            $this->commands([
                EventQueueProcessorCommand::class,
            ]);
        }

        //load routes defined out here
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register ()
    {
        $this->app->bind('message', Message::class);
    }
}
