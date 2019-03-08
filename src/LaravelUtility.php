<?php

namespace Drivezy\LaravelUtility;

use Drivezy\LaravelUtility\Models\Property;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

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

    /**
     * @return mixed
     */
    public static function getUserTable () {
        $userClass = config('utility.app_namespace') . '\\User';

        return ( new $userClass() )->getTable();
    }

    /**
     * @return string
     */
    public static function getUserModelFullQualifiedName () {
        return config('utility.app_namespace') . '\\User';
    }

    /**
     * @param $path
     * @param $file
     * @return mixed
     */
    public static function uploadToS3 ($path, $file) {
        Storage::disk('s3')->put($path, file_get_contents($file));
        Storage::disk('s3')->setVisibility($path, 'public');

        return Storage::disk('s3')->url($path);
    }

    /**
     * @param $path
     * @param $file
     * @return mixed
     */
    public static function uploadToS3Restricted ($path, $file) {
        Storage::disk('s3')->put($path, file_get_contents($file));

        return Storage::disk('s3')->url($path);
    }

    /**
     * @return bool
     */
    public static function isInstanceProduction () {
        return App::environment() == 'production' ? true : false;
    }

}
