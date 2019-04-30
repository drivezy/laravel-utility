<?php

namespace Drivezy\LaravelUtility\Models;

use Drivezy\LaravelUtility\Observers\ScheduledJobObserver;

/**
 * Class ScheduledJob
 * @package Drivezy\LaravelUtility\Models
 */
class ScheduledJob extends BaseModel {
    /**
     * @var string
     */
    protected $table = 'dz_scheduled_jobs';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event () {
        return $this->belongsTo(EventDetail::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function event_queues () {
        return $this->hasMany(EventQueue::class, 'source_id')->where('source_type', md5(self::class));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function active_jobs () {
        return $this->hasMany(EventQueue::class, 'source_id')->where('source_type', md5(self::class))->whereNull('start_time');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Query\Builder
     */
    public function completed_jobs () {
        return $this->hasMany(EventQueue::class, 'source_id')->where('source_type', md5(self::class))->whereNotNull('start_time');
    }

    /**
     * Override the observer with our own
     */
    protected static function boot () {
        parent::boot();
        self::observe(new ScheduledJobObserver());
    }
}
