<?php

namespace Drivezy\LaravelUtility\Models;

use Drivezy\LaravelUtility\Observers\PropertyObserver;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Property
 * @package Drivezy\LaravelUtility\Models
 */
class Property extends Model {
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