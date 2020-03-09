<?php

namespace Drivezy\LaravelUtility\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WorkerJob
 * @package Drivezy\LaravelUtility\Models
 */
class WorkerJob extends Model
{
    /**
     * @var string
     */
    protected $table = 'dz_worker_jobs';

    /**
     * @var array
     */
    protected $guarded = [];
}