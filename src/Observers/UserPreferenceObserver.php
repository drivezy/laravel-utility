<?php

namespace Drivezy\LaravelUtility\Observers;


class UserPreferenceObserver extends BaseObserver {
    /**
     * @var array
     */
    protected $rules = [
        'key' => 'required',
    ];

}