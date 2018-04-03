<?php

namespace Hemantanshu\LaravelUtility\Observers;

/**
 * Class LookupTypeObserver
 * @package Hemantanshu\LaravelUtility\Observers
 */
class LookupTypeObserver extends BaseObserver {

    /**
     * @var array
     */
    protected $rules = [
        'name' => 'required',
    ];

}