<?php

namespace Drivezy\LaravelUtility\Models;

use Drivezy\LaravelUtility\Observers\PropertyObserver;

/**
 * Class Property
 * @package Drivezy\LaravelUtility\Models
 */
class Property extends BaseModel {
    /**
     * @var string
     */
    protected $table = 'dz_properties';

    /**
     * Load the observer rule against the model
     */
    public static function boot () {
        parent::boot();
        self::observe(new PropertyObserver());
    }
}