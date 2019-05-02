<?php

namespace Drivezy\LaravelUtility\Observers;

use Drivezy\LaravelUtility\Job\ScheduledJobManagerJob;
use Drivezy\LaravelUtility\Models\EventQueue;
use Drivezy\LaravelUtility\Models\ScheduledJob;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ScheduledJobObserver
 * @package Drivezy\LaravelUtility\Observers
 */
class ScheduledJobObserver extends BaseObserver {
    /**
     * @var array
     */
    protected $rules = [
        'name'     => 'required',
        'event_id' => 'required',
    ];

    /**
     * @param Eloquent $model
     */
    public function saved (Eloquent $model) {
        parent::saved($model);

        if ( !$model->active )
            $this->dropScheduledJobs();
        else
            dispatch(new ScheduledJobManagerJob($model->id));
    }

    /**
     * @param Eloquent $model
     */
    public function deleted (Eloquent $model) {
        parent::deleted($model);
        $this->dropScheduledJobs();
    }

    /**
     * @param $model
     */
    private function dropScheduledJobs ($model) {
        EventQueue::where('source_type', md5(ScheduledJob::class))->where('source_id', $model->id)->delete();
    }
}
