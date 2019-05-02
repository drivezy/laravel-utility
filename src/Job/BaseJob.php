<?php

namespace Drivezy\LaravelUtility\Job;

use Drivezy\LaravelUtility\LaravelUtility;
use Drivezy\LaravelUtility\Library\DateUtil;
use Drivezy\LaravelUtility\Models\EventQueue;
use Drivezy\LaravelUtility\Models\EventTrigger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

/**
 * Class BaseJob
 * @package Drivezy\LaravelUtility\Job
 */
class BaseJob implements ShouldQueue {
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var null
     */
    public $id = null;
    /**
     * @var null
     */
    protected $event = null;
    /**
     * @var null
     */
    protected $eventId = null;
    /**
     * @var null =-09876
     */
    protected $details = null;

    /**
     * @var null
     */
    protected $logFile = null;
    /**
     * @var null
     */
    protected $fp = null;


    /**
     * BaseJob constructor.
     * @param $id
     * @param null $eventId
     */
    public function __construct ($id, $eventId = null) {
        $this->id = $id;
        $this->eventId = $eventId;
    }

    /**
     * @return bool
     */
    public function handle () {
        if ( !Auth::check() ) Auth::loginUsingId(3);

        $callingClass = debug_backtrace()[1]['class'];

        if ( !$this->eventId ) return true;

        $this->event = EventQueue::find($this->eventId);

        $this->setTrigger($callingClass);
    }

    /**
     * Report issue when things are not working fine
     * @param $job
     * @param $comments
     */
    public function reportIssue ($job, $comments) {
        if ( !$this->event ) return;
    }

    /**
     * Set up a new job trigger against the event
     * @param $identifier
     */
    private function setTrigger ($identifier) {
        $this->details = new EventTrigger();

        $this->details->event_queue_id = $this->eventId;
        $this->details->identifier = $identifier;
        $this->details->start_time = DateUtil::getDateTime();

        $this->details->save();
    }

    /**
     * @param null $exception
     */
    public function fail ($exception = null) {

    }


    /**
     * @param $str
     * @return bool
     */
    protected function info ($str) {
        if ( !$this->details ) return false;

        if ( !$this->logFile ) $this->createFilePointer();

        fwrite($this->fp, $str . PHP_EOL);
    }

    /**
     * Create a file pointer which is to be used for logging purpose
     */
    private function createFilePointer () {
        //create a pointer for file writing
        $this->logFile = $this->details->id . '-jobs-' . strtotime('now') . '.txt';
        $this->fp = fopen(storage_path() . '/logs/' . $this->logFile, 'w');
    }

    /**
     * This would wrap up the activity done for the given job file
     */
    public function __destruct () {
        if ( !$this->details ) return;

        $this->details->end_time = DateUtil::getDateTime();
        $this->details->log_file = $this->logFile ? LaravelUtility::uploadToS3('/logs/' . $this->logFile, storage_path() . '/logs/' . $this->logFile) : null;

        if ( $this->event->start_time )
            $this->details->total_latency = strtotime('now') - strtotime($this->event->start_time) + $this->event->pick_latency;
        else
            $this->details->total_latency = strtotime('now') - strtotime($this->details->start_time);

        $this->details->save();

        //close the file pointer and delete the log file
        if ( $this->fp ) {
            fclose($this->fp);

            //delete the file if present
            try {
                unlink(storage_path() . '/logs/' . $this->logFile);
            } catch ( \Exception $e ) {
                \Log::info('Error while deleting the file');
            }
        }
    }
}
