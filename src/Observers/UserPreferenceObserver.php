<?php

namespace Drivezy\LaravelUtility\Observers;


use Illuminate\Database\Eloquent\Model as Eloquent;

class UserPreferenceObserver extends BaseObserver {
    /**
     * @var array
     */
    protected $rules = [
        'key' => 'required',
    ];

}