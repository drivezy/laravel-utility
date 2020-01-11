<?php

namespace Drivezy\LaravelUtility\Library;

use Drivezy\LaravelUtility\LaravelUtility;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * Class QueueManager
 * @package JRApp\Libraries\Queue
 */
class QueueManager extends Command
{
    /**
     * @var bool|string
     */
    private $sleep_timing = '';
    /**
     * @var int
     */
    private $iterations = 0;

    /**
     * @var null
     */
    private $queueManager = null;

    /**
     * @var
     */
    protected $identifier;

    /**
     * QueueManager constructor.
     */
    public function __construct ()
    {
        if ( !Auth::check() ) Auth::loginUsingId(3);
        $this->sleep_timing = intval(LaravelUtility::getProperty('sqs.queue.sleep', 10));
    }

    /**
     * This would give the time when the code restart was requested
     * @return mixed
     */
    protected function getLastRestartTime ()
    {
        return Cache::get('illuminate:queue:restart');
    }

    /**
     * Check if the restart is required for the process
     * @param $time
     */
    protected function needsRestart ($time)
    {
        if ( $time != $this->getLastRestartTime() )
            $this->restart();

        $this->iterations = 0;
    }

    /**
     * Check if code can be run or needs to wait for a while for the first run case
     */
    protected function handleRunCases ()
    {
        ++$this->iterations;

        if ( $this->iterations < 20 )
            $this->rest();
        else
            $this->restart();

    }

    /**
     * Sleep for a while
     */
    protected function rest ()
    {
        sleep(rand(3, $this->sleep_timing));
    }

    /**
     * Exit the daemon process
     */
    private function restart ()
    {
        exit(0);
    }

    /**
     * Close off the request object
     */
    public function __destruct ()
    {
        Cache::forever($this->identifier, false);
    }

}
