<?php

namespace Drivezy\LaravelUtility\Controllers;

use Drivezy\LaravelRecordManager\Controllers\RecordController;
use Drivezy\LaravelUtility\Models\UserPreference;

/**
 * Class UserPreferenceController
 * @package Drivezy\LaravelUtility\Controllers
 */
class UserPreferenceController extends RecordController {
    /**
     * @var string
     */
    protected $model = UserPreference::class;
}
