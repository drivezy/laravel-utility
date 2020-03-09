<?php

namespace Drivezy\LaravelUtility\Job;

use Drivezy\LaravelUtility\LaravelUtility;
use Drivezy\LaravelUtility\Library\DateUtil;
use Drivezy\LaravelUtility\Library\Queue\QueueStatsManager;
use Drivezy\LaravelUtility\Models\WorkerProcess;

/**
 * Class WorkerProcessStatsLoggerJob
 * @package Drivezy\LaravelUtility\Job
 */
class WorkerProcessStatsLoggerJob extends BaseJob
{

    /**
     * @return bool|void
     */
    public function handle ()
    {
        parent::handle();

        //set the stats for the given time
        QueueStatsManager::setJobStatsInDB();

        //set the processes inactive which has not responded since x minutes
        $minutes = LaravelUtility::getProperty('worker.process.inactive.period', 5);

        //check for all processes which are not yet active
        WorkerProcess::where('active', true)->where('last_ping_time', '<', DateUtil::getPastTime($minutes))->update(['active' => false]);

        //fallback for those processes that were out of old code
        WorkerProcess::where('active', true)->whereNull('last_ping_time')->update(['active' => false]);


    }

}