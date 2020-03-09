<?php

namespace Drivezy\LaravelUtility\Library\Queue;

use Drivezy\LaravelUtility\Library\DateUtil;
use Drivezy\LaravelUtility\Models\WorkerJob;
use Illuminate\Queue\Events\JobProcessed;

/**
 * Class JobProcessedLogManager
 * @package Drivezy\LaravelUtility\Library\Queue
 */
class JobProcessedLogManager
{
    /**
     * @var JobProcessed
     */
    private $event;

    /**
     * JobProcessedLogManager constructor.
     * @param JobProcessed $event
     */
    public function __construct (JobProcessed $event)
    {
        $this->event = $event;
    }

    /**
     * update the job record with the time when the job was completed.
     * this will help in finding the costly jobs
     */
    public function handle ()
    {
        //find the job record in the system against the unique job id
        $job = WorkerJob::where('job_identifier', $this->event->job->getJobId())->first();
        if ( !$job ) return;

        $job->end_time = DateUtil::getDateTime();
        $job->save();
    }
}