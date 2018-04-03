<?php

namespace Hemantanshu\LaravelUtility\Models;


use Hemantanshu\LaravelUtility\Observers\LookupTypeObserver;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LookupType
 * @package Hemantanshu\LaravelUtility\Models
 */
class LookupType extends Model {
    /**
     * @var string
     */
    protected $table = 'hm_lookup_types';

    /**
     * Load the observer rule against the model
     */
    public static function boot () {
        parent::boot();
        self::observe(new LookupTypeObserver());
    }

}