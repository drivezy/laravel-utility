<?php

namespace Drivezy\LaravelUtility\Job;

use Drivezy\LaravelRecordManager\Library\Notification\NotificationManager;

/**
 * Class QueueNotificationManagerJob
 * @package Drivezy\LaravelUtility\Job
 */
class QueueNotificationManagerJob extends BaseJob
{
    /**
     * @return bool|void
     */
    public function handle ()
    {
        parent::handle();

        if ( !$this->event ) return;

        ( new NotificationManager($this->id) )->process($this->event->object_value);
    }

}