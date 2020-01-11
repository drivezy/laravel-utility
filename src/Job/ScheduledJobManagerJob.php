<?php

namespace Drivezy\LaravelUtility\Job;

use Cron\CronExpression;
use Drivezy\LaravelUtility\Library\DateUtil;
use Drivezy\LaravelUtility\Models\EventQueue;
use Drivezy\LaravelUtility\Models\ScheduledJob;

/**
 * Class ScheduledJobManagerJob
 * @package Drivezy\LaravelUtility\Job
 */
class ScheduledJobManagerJob extends BaseJob
{
    public $source_class = null;

    /**
     * ScheduledJobManagerJob constructor.
     * @param $id
     * @param null $eventId
     */
    public function __construct ($id, $eventId = null)
    {
        parent::__construct($id, $eventId);
    }

    /**
     * @return bool|void
     */
    public function handle ()
    {
        parent::handle();

        $this->source_class = md5(ScheduledJob::class);

        //process the particular scheduled job
        if ( $this->id != 0 ) {
            $job = ScheduledJob::find($this->id);
            if ( $job )
                return $this->handleJob($job);
        }


        //process all jobs as particular option to hit is not processed
        $jobs = ScheduledJob::where('active', true)->get();
        foreach ( $jobs as $job ) {
            $this->handleJob($job);
        }
    }

    /**
     * @param ScheduledJob $job
     */
    private function handleJob (ScheduledJob $job)
    {
        //check if the given event exists against the given job
        if ( !$job->event ) return;

        //get the count of events already registered which are active
        $inQueue = EventQueue::active()->where('source_type', '=', $this->source_class)->where('source_id', $job->id)->count();
        if ( $inQueue > 6 ) return;

        //get the last event which was scheduled
        $lastScheduled = EventQueue::where('source_type', $this->source_class)->where('source_id', $job->id)->orderBy('scheduled_start_time', 'desc')->first();
        $nextRunTime = $lastScheduled ? ( DateUtil::getDateTimeDifference($lastScheduled->scheduled_start_time, DateUtil::getDateTime()) > 0 ? DateUtil::getDateTime() : $lastScheduled->scheduled_start_time ) : DateUtil::getDateTime();

        while ( $inQueue <= 10 ) {
            try {
                $cron = CronExpression::factory($job->timing);
                $nextRunTime = $cron->getNextRunDate($nextRunTime)->format('Y-m-d H:i:s');
            } catch ( \Exception $e ) {
                break;
            }

            if ( DateUtil::getDateTimeDifference($job->end_time, $nextRunTime) > 0 ) break;

            //create event queue against the given job
            EventQueue::create([
                'event_id'             => $job->event_id,
                'event_name'           => $job->event->event_name,
                'object_value'         => $job->parameter,
                'scheduled_start_time' => $nextRunTime,
                'source_type'          => $this->source_class,
                'source_id'            => $job->id,
            ]);
            ++$inQueue;
        }

        if ( $inQueue > 10 ) {
            $job->last_scheduled_time = $nextRunTime;
            $job->save();
        }
    }


}
