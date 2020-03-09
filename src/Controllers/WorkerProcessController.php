<?php

namespace Drivezy\LaravelUtility\Controllers;

use Drivezy\LaravelRecordManager\Controllers\ReadRecordController;
use Drivezy\LaravelUtility\Models\WorkerProcess;

/**
 * Class WorkerProcessController
 * @package Drivezy\LaravelUtility\Controllers
 */
class WorkerProcessController extends ReadRecordController
{
    /**
     * @var string
     */
    protected $model = WorkerProcess::class;
}