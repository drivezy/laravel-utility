<?php

use Drivezy\LaravelRecordManager\Jobs\ObserverEventManagerJob;

return [

    /*
    |--------------------------------------------------------------------------
    | Default namespace of the application server
    |--------------------------------------------------------------------------
    |
    | This option helps to record the namespace if at all the user at the time of deployment
    | has overridden as per his liking. If not the default will always be set as
    | app which is defaulted by the laravel base framework itself.
    |
    */
    'app_namespace' => 'App',

    /*
    |--------------------------------------------------------------------------
    | Default bucket on s3 server
    |--------------------------------------------------------------------------
    |
    | For multiple usages in the custom packages, we need to interact with the storage server
    | the assumption is being made, that we would be using aws s3 storage for all
    | such purpose. And hence the bucket in where it would store all files.
    | eg. usage is with mail logs | notification logs
    |
    */
    's3_bucket'     => 'drivezy-s3-bucket',

    /*
    |--------------------------------------------------------------------------
    | Jobs which are not to be logged by worker listeners
    |--------------------------------------------------------------------------
    |
    | For the worker analytics, if at all there are any jobs which is not to be logged
    | into the worker job records is to be mentioned in here. But for all such
    | jobs, stats record and process record will be updated. This is done to
    | avoid clogging of the worker jobs table and these sort of jobs
    | are not to be individually analyzed in future.
    |
    */

    'job_stats_exceptions' => [
        ObserverEventManagerJob::class,
    ],
];
