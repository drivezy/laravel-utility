<?php

namespace Drivezy\LaravelUtility\Models;

use Drivezy\LaravelUtility\Library\DateUtil;
use Drivezy\LaravelUtility\Observers\EventQueueObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class LookupValue
 * @package Drivezy\LaravelUtility\Models
 */
class EventQueue extends BaseModel
{
    /**
     * @var bool
     */
    public $observable = false;
    /**
     * @var bool
     */
    public $auditable = false;
    /**
     * @var string
     */
    protected $table = 'dz_event_queues';

    /**
     * Load the observer rule against the model
     */
    public static function boot ()
    {
        parent::boot();
        self::observe(new EventQueueObserver());
    }

    /**
     * @return BelongsTo
     */
    public function event_detail ()
    {
        return $this->belongsTo(EventDetail::class, 'event_id');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive ($query)
    {
        return $query->whereNull('start_time');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopePending ($query)
    {
        return $query->whereNull('start_time')->where('scheduled_start_time', '<=', DateUtil::getDateTime());
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeCompleted ($query)
    {
        return $query->whereNotNull('start_time')->whereNotNull('end_time');
    }

    /**
     * @return HasMany
     */
    public function triggers ()
    {
        return $this->hasMany(EventTrigger::class, 'event_queue_id');
    }

}
