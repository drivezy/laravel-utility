<?php

namespace Drivezy\LaravelUtility\Observers;

use Drivezy\LaravelUtility\Observers\BaseObserver;

/**
 * Class CommentDetailObserver
 * @package Drivezy\LaravelUtility\Observers
 */
class  CommentDetailObserver extends BaseObserver
{
    /**
     * @var array
     */
    protected $rules = [
        'comments' => 'required',
    ];
}