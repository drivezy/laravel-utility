<?php

namespace Drivezy\LaravelUtility\Library;

use Drivezy\LaravelUtility\Events\EventQueueRaised;
use Drivezy\LaravelUtility\Job\QueueNotificationManagerJob;
use Drivezy\LaravelUtility\Models\EventDetail;
use Drivezy\LaravelUtility\Models\EventQueue;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;

/**
 * Class EventQueueManager
 * @package Drivezy\LaravelUtility\Library
 */
class EventQueueManager extends QueueManager
{
    /**
     * @var int
     */
    private $iterations = 0;
    /**
     * @var bool|string|null
     */
    private $startTime = null;

    /**
     * EventQueueManager constructor.
     */
    public function __construct ()
    {
        $this->identifier = 'sqs.queue.push';
        $this->startTime = DateUtil::getDateTime();
        parent::__construct();
    }

    /**
     * This would poll the event queue table and would process it.
     */
    public function processQueue ()
    {
        $lastRestartTime = $this->getLastRestartTime();
        $app = Application::getInstance();

        while ( true ) {
            //check if the code is updated in between the run time
            $this->needsRestart($lastRestartTime);

            //check if the system is down for maintenance
            if ( $app->isDownForMaintenance() ) continue;

            //get all pending events for the period
            $events = $this->getPendingEvents();

            foreach ( $events as $event ) {
                $event->start_time = DateUtil::getDateTime();

                //no event is registered for the given record
                $handler = $event->event_detail;
                if ( !$handler ) {
                    $this->saveEvent($event);
                    continue;
                }

                //set event to broadcast of its event
                event(new EventQueueRaised($event));

                //process the job mentioned with the event
                $this->dispatchEventJob($event);

                //dispatch notification if any attached to the event
                $this->dispatchNotificationJob($event);

                //save the given event with necessary datetime params
                $this->saveEvent($event);
            }

            //check if the program has to sleep or not
            if ( !sizeof($events) ) $this->rest();

            Cache::forever($this->identifier, false);
        }
    }

    /**
     * process the job which is attached with the event queue
     * @param EventQueue $queue
     */
    private function dispatchEventJob (EventQueue $queue)
    {
        $job = $queue->event_detail->job_name;
        if ( !$job ) return;

        //check if job class exists before calling
        if ( !class_exists($job) ) {
            echo DateUtil::getDateTime() . " : Error event : " . $queue->id . ' : ' . $job . PHP_EOL;

            return;
        }

        //broadcast the event
        dispatch(new $job($queue->object_value, $queue->id));

    }

    /**
     * process the notification which is attached with the event queue
     * @param EventQueue $queue
     */
    private function dispatchNotificationJob (EventQueue $queue)
    {
        //dispatch notification if any attached to the event
        $handler = $queue->event_detail;
        if ( $handler->notification_id )
            dispatch(new QueueNotificationManagerJob($handler->notification_id, $queue->id));
    }

    /**
     * set the appropriate messaging
     */
    private function setIteration ()
    {
        ++$this->iterations;
        if ( $this->iterations % 100 == 0 ) {
            echo "processed event queues since : " . $this->startTime . " value : " . $this->iterations . PHP_EOL;
        }
    }

    /**
     * @param $event
     * @return mixed
     */
    private function saveEvent ($event)
    {
        $event->pick_latency = $this->getEventLatency($event);
        $event->end_time = DateUtil::getDateTime();
        $event->save();

        $this->setIteration();

        return $event;
    }

    /**
     * This would get the pending events from the system
     * @return mixed
     */
    private function getPendingEvents ()
    {
        return EventQueue::pending()->with('event_detail')
            ->orderBy('scheduled_start_time', 'asc')
            ->limit(100)
            ->get();
    }

    /**
     * Capture latency of the event
     * @param $event
     * @return false|int
     */
    private function getEventLatency ($event)
    {
        $timings = [
            strtotime($event->created_at),
            strtotime($event->updated_at),
            strtotime($event->scheduled_start_time),
        ];
        rsort($timings);

        return strtotime($event->start_time) - $timings[0];
    }

    /**
     * @param $eventName
     * @param $value
     * @param array $options
     * @return EventQueue
     */
    public static function setEvent ($eventName, $value, $options = [])
    {
        $event = EventDetail::where('event_name', $eventName)->first();

        $queue = new EventQueue();

        $queue->event_id = $event ? $event->id : null;
        $queue->event_name = $eventName;
        $queue->object_value = $value;

        foreach ( $options as $key => $value )
            $queue->setAttribute($key, $value);

        $queue->scheduled_start_time = $queue->scheduled_start_time ? : DateUtil::getDateTime();
        $queue->save();

        return $queue;
    }

    /**
     * Drop all event queues that are still pending against the given attributes
     * @param $eventName
     * @param array $options
     */
    public static function dropEvent ($eventName, $options = [])
    {
        $queue = EventQueue::whereNull('start_time')->where('event_name', $eventName);

        foreach ( $options as $key => $value )
            $queue->where($key, $value);

        $queue->delete();
    }

    /**
     * create and drop event as per the given inputs.
     * one single function that would create for two
     * @param $eventName
     * @param array $options
     */
    public static function dropAndSetEvent ($eventName, $options = [])
    {
        $queue = EventQueue::whereNull('start_time')->where('event_name', $eventName);

        foreach ( $options as $key => $value ) {
            if ( $key == 'scheduled_start_time' ) continue;

            $queue->where($key, $value);
        }

        //find all records that are existing in the event queue
        $records = $queue->get();
        foreach ( $records as $record ) {
            self::setEvent($eventName, $record->object_value, $options);
            $record->save();
        }

        //if no such events are found, create a fresh event against the item
        if ( sizeof($records) )
            self::setEvent($eventName, null, $options);

    }
}



