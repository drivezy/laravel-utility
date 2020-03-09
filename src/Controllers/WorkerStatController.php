<?php

namespace Drivezy\LaravelUtility\Controllers;

use Drivezy\LaravelRecordManager\Controllers\ReadRecordController;
use Drivezy\LaravelUtility\Models\WorkerStat;

/**
 * Class WorkerStatController
 * @package Drivezy\LaravelUtility\Controllers
 */
class WorkerStatController extends ReadRecordController
{
    /**
     * @var string
     */
    protected $model = WorkerStat::class;
}