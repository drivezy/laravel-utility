<?php

namespace Drivezy\LaravelUtility\Controllers;

use Drivezy\LaravelRecordManager\Controllers\RecordController;
use Drivezy\LaravelUtility\Models\EventDetail;

/**
 * Class EventDetailController
 * @package Drivezy\LaravelUtility\Controllers
 */
class EventDetailController extends RecordController
{
    /**
     * @var string
     */
    protected $model = EventDetail::class;
}
