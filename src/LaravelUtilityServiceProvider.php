<?php

namespace Drivezy\LaravelUtility;

use Drivezy\LaravelUtility\Commands\EventQueueProcessorCommand;
use Drivezy\LaravelUtility\Library\Message;
use Drivezy\LaravelUtility\Library\Queue\JobFailedLogManager;
use Drivezy\LaravelUtility\Library\Queue\JobProcessedLogManager;
use Drivezy\LaravelUtility\Library\Queue\JobProcessingLogManager;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Queue;
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
            __DIR__ . '/Config/Utility.php' => config_path('custom-utility.php'),
        ]);

        //load command defined in the system
        if ( $this->app->runningInConsole() ) {
            $this->commands([
                EventQueueProcessorCommand::class,
            ]);
        }

        //load routes defined out here
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        //set the listeners for the queue processing
        Queue::before(function (JobProcessing $event) {
            ( new JobProcessingLogManager($event) )->handle();
        });

        Queue::after(function (JobProcessed $event) {
            ( new JobProcessedLogManager($event) )->handle();
        });

        Queue::failing(function (JobFailed $event) {
            ( new JobFailedLogManager($event) )->handle();
        });
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
