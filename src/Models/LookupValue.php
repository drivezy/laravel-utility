<?php

namespace Hemantanshu\LaravelUtility\Models;


use Hemantanshu\LaravelUtility\Observers\LookupValueObserver;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LookupValue
 * @package Hemantanshu\LaravelUtility\Models
 */
class LookupValue extends Model {
    /**
     * @var string
     */
    protected $table = 'hm_lookup_values';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lookup_type () {
        return $this->belongsTo(LookupType::class);
    }

    /**
     * Load the observer rule against the model
     */
    public static function boot () {
        parent::boot();
        self::observe(new LookupValueObserver());
    }

}