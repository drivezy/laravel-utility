<?php

namespace Drivezy\LaravelUtility\Controllers;

use Drivezy\LaravelRecordManager\Controllers\RecordController;
use Drivezy\LaravelUtility\Models\EventQueue;

/**
 * Class EventQueueController
 * @package Drivezy\LaravelUtility\Controllers
 */
class EventQueueController extends RecordController {
    /**
     * @var string
     */
    protected $model = EventQueue::class;
}
