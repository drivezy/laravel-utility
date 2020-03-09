<?php

namespace Drivezy\LaravelUtility\Controllers;

use Drivezy\LaravelRecordManager\Controllers\ReadRecordController;
use Drivezy\LaravelUtility\Models\EventTrigger;

/**
 * Class EventTriggerController
 * @package Drivezy\LaravelUtility\Controllers
 */
class EventTriggerController extends ReadRecordController
{
    /**
     * @var string
     */
    protected $model = EventTrigger::class;
}
