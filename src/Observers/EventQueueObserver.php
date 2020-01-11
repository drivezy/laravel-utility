<?php

namespace Drivezy\LaravelUtility\Observers;

use Drivezy\LaravelUtility\Library\DateUtil;
use Drivezy\LaravelUtility\Models\EventDetail;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class EventQueueObserver
 * @package Drivezy\LaravelUtility\Observers
 */
class EventQueueObserver extends BaseObserver
{

    /**
     * @var array
     */
    protected $rules = [
        'event_name'           => 'required',
        'scheduled_start_time' => 'required',
    ];

    /**
     * @param Eloquent $model
     * @return bool
     */
    public function creating (Eloquent $model)
    {
        //check for the event id if not being passed on
        $model->event_id = $model->event_id ?? $this->getEventId($model->event_name);

        //check for the scheduled start time. if not then default it with the current time
        $model->scheduled_start_time = $model->scheduled_start_time ?? DateUtil::getDateTime();

        return parent::creating($model);
    }

    /**
     * @param $name
     * @return |null
     */
    private function getEventId ($name)
    {
        $event = EventDetail::where('event_name', $name)->first();
        if ( $event )
            return $event->id;

        return null;
    }

}