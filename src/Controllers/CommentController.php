<?php

namespace Drivezy\LaravelUtility\Controllers;

use Drivezy\LaravelRecordManager\Controllers\RecordController;
use Drivezy\LaravelUtility\Models\CommentDetail;

/**
 * Class CommentController
 * @package Drivezy\LaravelUtility\Controllers
 */
class CommentController extends RecordController
{
    /**
     * @var string
     */
    protected $model = CommentDetail::class;

}
