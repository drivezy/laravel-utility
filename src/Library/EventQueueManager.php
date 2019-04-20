<?php

namespace Drivezy\LaravelUtility\Library;

use Drivezy\LaravelUtility\Models\EventQueue;
use Illuminate\Support\Facades\Cache;

/**
 * Class EventQueueManager
 * @package JRApp\Libraries\Queue
 */
class EventQueueManager extends QueueManager {

    /**
     * EventQueueManager constructor.
     */
    public function __construct () {
        $this->identifier = 'sqs.queue.push';
        parent::__construct();
    }

    /**
     * This would poll the event queue table and would process it.
     */
    public function processQueue () {
        $lastRestartTime = $this->getLastRestartTime();

        while ( true ) {
            $this->needsRestart($lastRestartTime);

            $events = $this->getPendingEvents();

            foreach ( $events as $event ) {
                $event->start_time = DateUtil::getDateTime();

                //no event is registered for the given record
                $handler = $event->event_detail;
                if ( !$handler || !$handler->job_name || !class_exists($handler->job_name) ) {
                    $this->saveEvent($event);
                    continue;
                }

                //process the event queue
                $job = $handler->job_name;
                dispatch(new $job($event->object_value, $event->id));

                $this->saveEvent($event);
            }

            //check if the program has to sleep or not
            if ( !sizeof($events) ) $this->rest();

            Cache::forever($this->identifier, false);
        }
    }

    /**
     * @param $event
     * @return mixed
     */
    private function saveEvent ($event) {
        $event->pick_latency = $this->getEventLatency($event);
        $event->end_time = DateUtil::getDateTime();
        $event->save();

        return $event;
    }

    /**
     * This would get the pending events from the system
     * @return mixed
     */
    private function getPendingEvents () {
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
    private function getEventLatency ($event) {
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
    public static function setEvent ($eventName, $value, $options = []) {
        $event = EventDetail::where('name', $eventName)->first();

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
}



