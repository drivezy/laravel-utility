<?php

namespace Drivezy\LaravelUtility\Library;

use Drivezy\LaravelUtility\Models\EventDetail;
use Drivezy\LaravelUtility\Models\EventQueue;

/**
 * Class EventQueueManager
 * @package Drivezy\LaravelUtility\Library
 */
class EventQueueManager {
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