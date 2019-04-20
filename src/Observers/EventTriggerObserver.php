<?php

namespace Drivezy\LaravelUtility\Observers;

/**
 * Class EventDetailObserver
 * @package Drivezy\LaravelUtility\Observers
 */
class EventTriggerObserver extends BaseObserver {

    /**
     * @var array
     */
    protected $rules = [
        'event_queue_id' => 'required',
    ];

}
