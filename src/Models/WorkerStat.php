<?php

namespace Drivezy\LaravelUtility\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WorkerStat
 * @package Drivezy\LaravelUtility\Models
 */
class WorkerStat extends Model
{
    /**
     * @var string
     */
    protected $table = 'dz_worker_stats';
    /**
     * @var array
     */
    protected $guarded = [];
}