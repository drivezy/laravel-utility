<?php

namespace Drivezy\LaravelUtility\Observers;

/**
 * Class LookupTypeObserver
 * @package Drivezy\LaravelUtility\Observers
 */
class LookupTypeObserver extends BaseObserver {

    /**
     * @var array
     */
    protected $rules = [
        'name' => 'required',
    ];

}