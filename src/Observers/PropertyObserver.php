<?php

namespace Drivezy\LaravelUtility\Observers;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Cache;

/**
 * Class PropertyObserver
 * @package Drivezy\LaravelUtility\Observers
 */
class PropertyObserver extends BaseObserver
{
    /**
     * @var array
     */
    protected $rules = [
        'key'   => 'required',
        'value' => 'required',
    ];

    /**
     * @param Eloquent $model
     */
    public function saved (Eloquent $model)
    {
        parent::saved($model);

        //save the property in the cache for quick access
        if ( $model->caching_enabled ) {
            $cacheName = 'system-property.' . trim($model->key);
            Cache::forever($cacheName, trim($model->value));
        }
    }
}
