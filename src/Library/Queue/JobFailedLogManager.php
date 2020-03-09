<?php

namespace Drivezy\LaravelUtility\Library\Queue;

use Drivezy\LaravelUtility\Library\DateUtil;
use Drivezy\LaravelUtility\Models\WorkerJob;
use Illuminate\Queue\Events\JobFailed;

/**
 * Class JobFailedLogManager
 * @package Drivezy\LaravelUtility\Library\Queue
 */
class JobFailedLogManager
{
    /**
     * @var JobFailed
     */
    private $event;

    /**
     * JobFailedLogManager constructor.
     * @param JobFailed $event
     */
    public function __construct (JobFailed $event)
    {
        $this->event = $event;
    }

    /**
     * record the time when the job failed to complete.
     * This will help in re triggering one particular job if required be
     */
    public function handle ()
    {
        //find the job record in the system against the unique job id
        $job = WorkerJob::where('job_identifier', $this->event->job->getJobId())->first();
        if ( !$job ) return;

        $job->failed_at = DateUtil::getDateTime();
        $job->save();
    }
}