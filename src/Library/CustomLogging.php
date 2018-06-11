<?php

namespace Drivezy\LaravelUtility\Library;

/**
 * Class CustomLogging
 * @package Drivezy\LaravelUtility\Library
 */
class CustomLogging {
    /**
     * @var array
     */
    private static $message = [];

    /**
     *
     */
    public static function init () {
        self::$message = [];
    }

    /**
     * @param $key
     * @param $value
     */
    public static function setResponseMessage ($key, $value) {
        if ( !isset(self::$message[ $key ]) )
            self::$message[ $key ] = [];

        array_push(self::$message[ $key ], $value);
    }

    /**
     * @return array
     */
    public static function getResponseMessage () {
        return self::$message;
    }

}