<?php

namespace Drivezy\LaravelUtility\Commands;

use Drivezy\LaravelUtility\Library\EventQueueManager;
use Illuminate\Console\Command;

/**
 * Class CodeGeneratorCommand
 * @package Drivezy\LaravelRecordManager\Console
 */
class EventQueueProcessorCommand extends Command
{

    /**
     * @var
     */
    protected $table;
    /**
     * @var
     */
    protected $namespace;
    /**
     * @var
     */
    protected $name;
    /**
     * @var
     */
    protected $read;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'processing pending event queue on the system';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct ()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle ()
    {
        ( new EventQueueManager() )->processQueue();
    }
}
