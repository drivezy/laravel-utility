<?php

namespace Hemantanshu\LaravelUtility\Models;

use Hemantanshu\LaravelUtility\Observers\PropertyObserver;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Property
 * @package Hemantanshu\LaravelUtility\Models
 */
class Property extends Model {
    /**
     * @var string
     */
    protected $table = 'hm_properties';

    /**
     * Load the observer rule against the model
     */
    public static function boot () {
        parent::boot();
        self::observe(new PropertyObserver());
    }
}