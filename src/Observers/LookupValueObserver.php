<?php

namespace Hemantanshu\LaravelUtility\Observers;

/**
 * Class LookupValueObserver
 * @package Hemantanshu\LaravelUtility\Observers
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