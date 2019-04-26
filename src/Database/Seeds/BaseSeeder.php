<?php

namespace Drivezy\LaravelUtility\Database\Seeds;

use Drivezy\LaravelRecordManager\Jobs\ObserverEventManagerJob;
use Drivezy\LaravelRecordManager\Library\BusinessRuleManager;

/**
 * Class BaseSeeder
 * @package Drivezy\LaravelUtility\src\Database\Seeds
 */
class BaseSeeder {
    /**
     * BaseSeeder constructor.
     */
    public function __construct () {
        //dont let the business rule run when db seeder are executed
        BusinessRuleManager::$enabled = false;

        //dont let the observer event run when db seeder is running
        ObserverEventManagerJob::$enabled = false;
    }
}
