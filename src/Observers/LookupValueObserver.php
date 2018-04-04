<?php

namespace Drivezy\LaravelUtility\Observers;

/**
 * Class LookupValueObserver
 * @package Drivezy\LaravelUtility\Observers
 */
class LookupValueObserver extends BaseObserver {

    /**
     * @var array
     */
    protected $rules = [
        'lookup_type_id' => 'required',
        'value'          => 'required',
        'name'           => 'required',
    ];

}