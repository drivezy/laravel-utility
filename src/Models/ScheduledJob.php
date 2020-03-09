<?php

namespace Drivezy\LaravelUtility\Models;

use Drivezy\LaravelUtility\Observers\ScheduledJobObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * Class ScheduledJob
 * @package Drivezy\LaravelUtility\Models
 */
class ScheduledJob extends BaseModel
{
    /**
     * @var array
     */
    public $auditDisabled = ['last_scheduled_time'];
    /**
     * @var string
     */
    protected $table = 'dz_scheduled_jobs';

    /**
     * Override the observer with our own
     */
    protected static function boot ()
    {
        parent::boot();
        self::observe(new ScheduledJobObserver());
    }

    /**
     * @return BelongsTo
     */
    public function event ()
    {
        return $this->belongsTo(EventDetail::class);
    }

    /**
     * @return HasMany
     */
    public function event_queues ()
    {
        return $this->hasMany(EventQueue::class, 'source_id')->where('source_type', md5(self::class));
    }

    /**
     * @return HasMany
     */
    public function active_jobs ()
    {
        return $this->hasMany(EventQueue::class, 'source_id')->where('source_type', md5(self::class))->whereNull('start_time');
    }

    /**
     * @return HasMany|Builder
     */
    public function completed_jobs ()
    {
        return $this->hasMany(EventQueue::class, 'source_id')->where('source_type', md5(self::class))->whereNotNull('start_time');
    }
}
