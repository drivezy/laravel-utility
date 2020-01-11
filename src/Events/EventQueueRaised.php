<?php

namespace Drivezy\LaravelUtility\Events;

use Drivezy\LaravelUtility\Models\EventQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class EventQueueRaised
 * @package Drivezy\LaravelUtility\Events
 */
class EventQueueRaised
{
    use SerializesModels;

    /**
     * @var EventQueue|null
     */
    public $eventQueue = null;

    /**
     * EventQueueRaised constructor.
     * @param EventQueue $eventQueue
     */
    public function __construct (EventQueue $eventQueue)
    {
        $this->eventQueue = $eventQueue;
    }
}