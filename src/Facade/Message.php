<?php

namespace Drivezy\LaravelUtility\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class Message
 * @package Drivezy\LaravelUtility\Facade
 */
class Message extends Facade {
    /**
     * @return string
     */
    protected static function getFacadeAccessor () {
        return 'message';
    }
}