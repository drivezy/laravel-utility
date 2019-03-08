<?php

namespace Drivezy\LaravelUtility\Library;

use Drivezy\LaravelUtility\LaravelUtility;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use JRApp\Libraries\Utility\DateUtil;
use JRApp\Libraries\Utility\Utility;

/**
 * Class QueueManager
 * @package JRApp\Libraries\Queue
 */
class QueueManager extends Command {
    /**
     * @var bool|string
     */
    private $sleep_timing = '';
    /**
     * @var int
     */
    private $iterations = 0;

    protected $identifier;

    /**
     * QueueManager constructor.
     */
    public function __construct () {
        if ( !Auth::check() ) Auth::loginUsingId(3);
        $this->sleep_timing = intval(LaravelUtility::getProperty('sqs.queue.sleep', 10));
    }

    /**
     * This would give the time when the code restart was requested
     * @return mixed
     */
    protected function getLastRestartTime () {
        return Cache::get('illuminate:queue:restart');
    }

    protected function canExecuteJob ($obj) {
        return true;
    }

    protected function endJob () {
        return true;
    }

    /**
     * This would check if the concurrent request are allowed or not
     */
    protected function checkFirstRun () {
        $running = Cache::get($this->identifier, false);
        if ( $running ) $this->handleRunCases();
    }

    /**
     * Check if the restart is required for the process
     * @param $time
     */
    protected function needsRestart ($time) {
        if ( $time != $this->getLastRestartTime() )
            $this->restart();

        $this->iterations = 0;

        $this->checkHoldCondition();
        Cache::put($this->identifier, true, LaravelUtility::getProperty('sqs.queue.lock', 2));
    }

    /**
     * Check if event processing has been disabled by the system
     * @return bool
     */
    private function checkHoldCondition () {
        ++$this->iterations;

        $runQueue = LaravelUtility::getProperty('sys.enable.queue', 1);
        if ( $runQueue ) return true;

        if ( $this->iterations > 20 ) $this->restart();

        $this->rest();
        $this->checkHoldCondition();
    }

    /**
     * Check if code can be run or needs to wait for a while for the first run case
     */
    protected function handleRunCases () {
        ++$this->iterations;

        if ( $this->iterations < 20 )
            $this->rest();
        else
            $this->restart();

        $this->checkFirstRun();
    }

    /**
     * Sleep for a while
     */
    protected function rest () {
        sleep(rand(3, $this->sleep_timing));
    }

    /**
     * Exit the daemon process
     */
    private function restart () {
        Cache::forever($this->identifier, false);
        exit(0);
    }

    /**
     * Check the maximum no of entries can be pushed to sqs depending on our strength to process data
     * @return int
     */
    protected function getMaximumNumberOfEntriesToPush () {
        $actionableItems = LaravelUtility::getProperty('queue.max.limit', 200);

        return $actionableItems > 0 ? $actionableItems : 0;
    }

    /**
     * Close off the request object
     */
    public function __destruct () {
        Cache::forever($this->identifier, false);
    }

}
