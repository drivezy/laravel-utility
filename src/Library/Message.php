<?php

namespace Drivezy\LaravelUtility\Library;

/**
 * Class Message
 * @package Drivezy\LaravelUtility\Library
 */
class Message {
    /**
     * @var array
     */
    public static $message = [];

    /**
     * @param $message
     */
    public static function info ($message) {
        if ( !isset(self::$message['info']) )
            self::$message['info'] = [];

        array_push(self::$message['info'], [
            'message' => $message,
        ]);
    }

    /**
     * @param $message
     */
    public static function error ($message) {
        if ( !isset(self::$message['error']) )
            self::$message['error'] = [];

        array_push(self::$message['error'], [
            'message' => $message,
        ]);
    }

    /**
     * @param $message
     */
    public static function warn ($message) {
        if ( !isset(self::$message['warn']) )
            self::$message['warn'] = [];

        array_push(self::$message['warn'], [
            'message' => $message,
        ]);
    }
}