<?php

namespace Drivezy\LaravelUtility\Models;

use App\User;
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user () {
        return $this->belongsTo(User::class);
    }

    /**
     *
     */
    public static function boot () {
        parent::boot();
        self::observe(new UserPreferenceObserver());
    }
}