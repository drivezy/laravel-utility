<?php

namespace Drivezy\LaravelUtility\Models;


use Drivezy\LaravelUtility\Observers\EventTriggerObserver;

/**
 * Class LookupValue
 * @package Drivezy\LaravelUtility\Models
 */
class EventTrigger extends BaseModel {
    /**
     * @var string
     */
    protected $table = 'dz_event_triggers';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event_queue () {
        return $this->belongsTo(EventQueue::class);
    }

    /**
     * Load the observer rule against the model
     */
    public static function boot () {
        parent::boot();
        self::observe(new EventTriggerObserver());
    }

}
