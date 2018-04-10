<?php

namespace Drivezy\LaravelUtility\Models;


use Drivezy\LaravelUtility\Observers\LookupTypeObserver;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LookupType
 * @package Drivezy\LaravelUtility\Models
 */
class LookupType extends Model {
    /**
     * @var string
     */
    protected $table = 'dz_lookup_types';

    /**
     * Load the observer rule against the model
     */
    public static function boot () {
        parent::boot();
        self::observe(new LookupTypeObserver());
    }

}