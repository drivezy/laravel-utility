<?php

namespace Drivezy\LaravelUtility\Models;


use Drivezy\LaravelUtility\Observers\LookupTypeObserver;

/**
 * Class LookupType
 * @package Drivezy\LaravelUtility\Models
 */
class LookupType extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'dz_lookup_types';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lookup_values ()
    {
        return $this->hasMany(LookupValue::class, 'lookup_type_id');
    }

    /**
     * Load the observer rule against the model
     */
    public static function boot ()
    {
        parent::boot();
        self::observe(new LookupTypeObserver());
    }

}