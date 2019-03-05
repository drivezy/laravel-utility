<?php

namespace Drivezy\LaravelUtility\Models;


use Drivezy\LaravelUtility\Observers\EventDetailObserver;

/**
 * Class LookupValue
 * @package Drivezy\LaravelUtility\Models
 */
class EventDetail extends BaseModel {
    /**
     * @var string
     */
    protected $table = 'dz_event_details';

    /**
     * Load the observer rule against the model
     */
    public static function boot () {
        parent::boot();
        self::observe(new EventDetailObserver());
    }

}