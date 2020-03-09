<?php

namespace Drivezy\LaravelUtility\Library\Queue;

use Drivezy\LaravelUtility\Library\DateUtil;
use Drivezy\LaravelUtility\Library\ServerHostManager;
use Drivezy\LaravelUtility\Models\WorkerJob;
use Drivezy\LaravelUtility\Models\WorkerProcess;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

/**
 * Class JobProcessingEventManager
 * @package Drivezy\LaravelUtility\Library\Queue
 */
class JobProcessingLogManager
{
    /**
     * @var JobProcessing
     */
    private $event;


    /**
     * JobProcessingEventManager constructor.
     * @param JobProcessing $event
     */
    public function __construct (JobProcessing $event)
    {
        $this->event = $event;
    }

    /**
     * it does three activity.
     * setting up the stats increment counter
     * logging the job on the table if required
     * updating the worker process with current running job
     */
    public function handle ()
    {
        //get the name of the job being processed
        $name = $this->event->job->resolveName();

        $minute = QueueStatsManager::getCurrentMinute();
        $hostname = ServerHostManager::getHostName();
        $pid = ServerHostManager::getPID();

        //check if this job is to be logged on jobs
        $isException = in_array($name, QueueStatsManager::getJobExceptions());

        //log to the stats on cache server. Redis server on cache is recommended
        $md5Name = $isException ? md5($name) : md5('others');
        $identifier = "worker.stats.{$hostname}.{$md5Name}.{$minute}";
        Cache::increment($identifier, 1);

        //if the job is not falling in the exceptions, then log it into the table
        if ( !$isException ) {
            WorkerJob::create([
                'hostname'       => $hostname,
                'pid'            => $pid,
                'job_identifier' => $this->event->job->getJobId(),
                'job_name'       => $name,
                'start_time'     => DateUtil::getDateTime(),
                'channel'        => $this->event->connectionName,
            ]);
        }

        //record the job activity on the worker process table
        $process = WorkerProcess::firstOrNew([
            'hostname' => $hostname,
            'pid'      => $pid,
        ]);

        $process->job_name = $name;
        $process->last_ping_time = DateUtil::getDateTime();
        $process->save();
    }
}