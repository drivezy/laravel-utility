<?php

namespace Drivezy\LaravelUtility\Models;


use Drivezy\LaravelUtility\Observers\EventQueueObserver;

/**
 * Class LookupValue
 * @package Drivezy\LaravelUtility\Models
 */
class EventQueue extends BaseModel {
    /**
     * @var string
     */
    protected $table = 'dz_event_queues';

    /**
     * Load the observer rule against the model
     */
    public static function boot () {
        parent::boot();
        self::observe(new EventQueueObserver());
    }

}