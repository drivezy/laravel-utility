<?php

namespace Drivezy\LaravelUtility\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WorkerProcess
 * @package Drivezy\LaravelUtility\Models
 */
class WorkerProcess extends Model
{
    /**
     * @var string
     */
    protected $table = 'dz_worker_processes';

    /**
     * @var array
     */
    protected $guarded = [];
}