<?php

namespace Drivezy\LaravelUtility\Controllers;

use Drivezy\LaravelRecordManager\Controllers\RecordController;
use Drivezy\LaravelUtility\Models\ScheduledJob;

/**
 * Class ScheduledJobController
 * @package Drivezy\LaravelUtility\Controllers
 */
class ScheduledJobController extends RecordController
{
    /**
     * @var string
     */
    protected $model = ScheduledJob::class;
}
