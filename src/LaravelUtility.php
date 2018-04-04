<?php

namespace Drivezy\LaravelUtility;

use Drivezy\LaravelUtility\Models\Property;
use Illuminate\Support\Facades\Cache;

/**
 * Class LaravelUtility
 * @package Drivezy\LaravelUtility
 */
class LaravelUtility {

    /**
     * Get the property from the system
     * @param $property
     * @param bool $default
     * @return bool|string
     */
    public static function getProperty ($property, $default = false) {
        $key = 'system-property.' . $property;

        //check if the cache has that property saved within
        $value = Cache::get($property, null);
        if ( !is_null($value) ) return $value;

        //find the property against the name
        $object = Property::where('name', $property)->first();

        if ( $object ) {
            if ( $object->caching_enabled )
                Cache::forever($key, trim($object->value));

            return trim($object->value);
        }

        //if no match, then just push out the default
        if ( $default ) return $default;

        return false;
    }

}