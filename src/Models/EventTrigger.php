<?php

namespace Drivezy\LaravelUtility\Models;


use Drivezy\LaravelUtility\Observers\EventTriggerObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class LookupValue
 * @package Drivezy\LaravelUtility\Models
 */
class EventTrigger extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'dz_event_triggers';

    /**
     * Load the observer rule against the model
     */
    public static function boot ()
    {
        parent::boot();
        self::observe(new EventTriggerObserver());
    }

    /**
     * @return BelongsTo
     */
    public function event_queue ()
    {
        return $this->belongsTo(EventQueue::class);
    }

}
