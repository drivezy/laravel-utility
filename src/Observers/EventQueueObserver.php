<?php

namespace Drivezy\LaravelUtility\Observers;

class EventQueueObserver extends BaseObserver {

    /**
     * @var array
     */
    protected $rules = [
        'event_name'           => 'required',
        'scheduled_start_time' => 'required',
    ];

}