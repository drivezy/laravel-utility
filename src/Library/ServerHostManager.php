<?php

namespace Drivezy\LaravelUtility\Library;

/**
 * Class ServerHostManager
 * @package Drivezy\LaravelUtility\Library
 */
class ServerHostManager
{
    /**
     * store the hostname of the server where it is being run
     * @var bool
     */
    private static $hostname = null;


    /**
     * store the process id for the given php process
     * @var bool
     */
    private static $pid = null;

    /**
     * setup the hostname and the pid of the given instance
     */
    private static function init ()
    {
        self::$hostname = gethostname();
        self::$pid = getmypid();
    }

    /**
     * return the hostname of the running server
     * @return bool
     */
    public static function getHostName ()
    {
        if ( is_null(self::$hostname) ) self::init();

        return self::$hostname;
    }

    /**
     * return the pid of the php process
     * @return bool
     */
    public static function getPID ()
    {
        if ( is_null(self::$pid) ) self::init();

        return self::$pid;
    }
}