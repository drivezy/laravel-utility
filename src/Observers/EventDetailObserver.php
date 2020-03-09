<?php

namespace Drivezy\LaravelUtility\Observers;

/**
 * Class EventDetailObserver
 * @package Drivezy\LaravelUtility\Observers
 */
class EventDetailObserver extends BaseObserver
{

    /**
     * @var array
     */
    protected $rules = [
        'event_name' => 'required',
    ];

}
