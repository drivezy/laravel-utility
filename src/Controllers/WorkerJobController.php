<?php

namespace Drivezy\LaravelUtility\Controllers;

use Drivezy\LaravelRecordManager\Controllers\ReadRecordController;
use Drivezy\LaravelUtility\Models\WorkerJob;

/**
 * Class WorkerJobController
 * @package Drivezy\LaravelUtility\Controllers
 */
class WorkerJobController extends ReadRecordController
{
    /**
     * @var string
     */
    protected $model = WorkerJob::class;
}