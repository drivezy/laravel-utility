<?php

namespace Drivezy\LaravelUtility\Models;


/**
 * Check for the model operations done at the system
 * Trait ModelEvaluator
 * @package Drivezy\LaravelUtility\Models
 */
trait Auditable
{
    /**
     * if the audit is enabled or not at the global level
     * @var bool
     */
    public $auditable = true;

    /**
     * @var bool
     */
    public $observable = true;

    /**
     * if defined, only these columns would be allowed for audit
     * @var array
     */
    public $auditEnabled = [];

    /**
     * if defined, only these columns would be excluded for audit
     * This one has lower precedence than the auditEnabled one
     * @var array
     */
    public $auditDisabled = [];
}
