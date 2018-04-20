<?php

namespace Drivezy\LaravelUtility\Library;

use Drivezy\LaravelUtility\Models\UserPreference;
use Illuminate\Support\Facades\Auth;

/**
 * Class PreferenceManagement
 * @package Drivezy\LaravelUtility\Library
 */
class PreferenceManagement {

    /**
     * @param $key
     * @param null $userId
     * @return null
     */
    public static function getUserPreference ($key, $userId = null) {
        $userId = $userId ? : Auth::id();

        //check if user has the individual preference set against it
        $preference = UserPreference::where('user_id', $userId)->where('key', $key)->first();
        if ( $preference )
            return $preference->value;

        return self::getGlobalPreference($key);
    }

    /**
     * @param $key
     * @return null
     */
    public static function getGlobalPreference ($key) {
        //check if global preference is set against the key
        $preference = UserPreference::whereNull('user_id')->where('key', $key)->first();
        if ( $preference )
            return $preference->value;

        return null;
    }

    /**
     * @param $key
     * @param $value
     * @param null $userId
     * @return mixed
     */
    public static function setUserPreference ($key, $value, $userId = null) {
        $userId = $userId ? : Auth::id();

        $preference = UserPreference::firstOrNew([
            'user_id' => $userId,
            'key'     => $key,
        ]);

        $preference->value = $value;
        $preference->save();

        return $preference;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function setGlobalPreference ($key, $value) {
        $preference = UserPreference::firstOrNew([
            'user_id' => null,
            'key'     => $key,
        ]);

        $preference->value = $value;
        $preference->save();

        return $preference;
    }

    /**
     * @param $key
     */
    public static function revokeUserIndividualPreference ($key) {
        UserPreference::whereNotNull('user_id')->where('key', $key)->delete();
    }

    /**
     * @param $key
     * @param null $userId
     */
    public static function deleteUserPreference ($key, $userId = null) {
        $userId = $userId ? : Auth::id();
        UserPreference::where('user_id', $userId)->where('key', $key)->delete();
    }
}