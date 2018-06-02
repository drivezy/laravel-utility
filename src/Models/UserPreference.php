<?php

namespace Drivezy\LaravelUtility\Models;

use Drivezy\LaravelUtility\LaravelUtility;
use Drivezy\LaravelUtility\Observers\UserPreferenceObserver;

/**
 * Class UserPreference
 * @package Drivezy\LaravelUtility\Models
 */
class UserPreference extends BaseModel {
    /**
     * @var string
     */
    protected $table = 'dz_user_preferences';

    /**
     * @param $obj
     */
    public function setValueAttribute ($obj) {
        $this->attributes['value'] = serialize($obj);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getValueAttribute ($value) {
        return unserialize($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user () {
        return $this->belongsTo(LaravelUtility::getUserModelFullQualifiedName());
    }

    /**
     * Load the observer rule against the model
     */
    public static function boot () {
        parent::boot();
        self::observe(new UserPreferenceObserver());
    }
}