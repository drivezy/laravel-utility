<?php

namespace Drivezy\LaravelUtility\Observers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class PropertyObserver
 * @package Drivezy\LaravelUtility\Observers
 */
class PropertyObserver extends BaseObserver {
    /**
     * @var array
     */
    protected $rules = [
        'name'  => 'required',
        'value' => 'required',
    ];

    /**
     * @param Eloquent $model
     */
    public function saved (Eloquent $model) {
        parent::saved($model);

        //save the property in the cache for quick access
        if ( $model->caching_enabled ) {
            $cacheName = 'system-property.' . trim($model->key);
            Cache::forever($cacheName, trim($model->value));
        }
    }
}