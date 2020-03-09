<?php

namespace Drivezy\LaravelUtility\Library\Queue;

use Drivezy\LaravelUtility\LaravelUtility;
use Drivezy\LaravelUtility\Library\DateUtil;
use Drivezy\LaravelUtility\Models\WorkerJob;
use Drivezy\LaravelUtility\Models\WorkerProcess;
use Drivezy\LaravelUtility\Models\WorkerStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Class QueueStatsManager
 * @package Drivezy\LaravelUtility\Library\Queue
 */
class QueueStatsManager
{
    /**
     * @var null
     */
    private static $exceptions = null;

    /**
     * initialize the variable against the configuration setup by the user on utility
     */
    public static function init ()
    {
        self::$exceptions = config('custom-utility.job_stats_exceptions') ?? [];
    }

    /**
     * utility function to get the static value of the job exceptions
     * this is loaded only on first call
     * @return null
     */
    public static function getJobExceptions ()
    {
        if ( is_null(self::$exceptions) ) self::init();

        return self::$exceptions;
    }

    /**
     * get the current minute passed on since epoch
     * @return int
     */
    public static function getCurrentMinute ()
    {
        return (int) ( strtotime('now') / 60 );
    }

    /**
     * this would interface with the controller which would intercept request from the worker server
     * the worker server would broadcast all the pids that are of php and are active on the system
     * @param Request $request
     */
    public static function setServerProcessLogs (Request $request)
    {
        //we will be getting comma separated values from the server input
        $pids = explode(',', $request->pids);

        //find all the process ids that are available in our system and then touch it with the current
        //timestamp. idea is that server would ber posting the same data every minute
        foreach ( $pids as $pid ) {
            $process = WorkerProcess::where('hostname', $request->hostname)
                ->where('pid', $pid)->first();
            if ( !$process ) continue;

            $process->last_ping_time = DateUtil::getDateTime();
            $process->save();
        }
    }

    /**
     * fetch record from the cache and then put it down into the database so that
     * data analysis can be easy and then the storage is non-transient.
     * data is stored against worker host and jobs
     */
    public static function setJobStatsInDB ()
    {
        $jobs = array_merge(QueueStatsManager::getJobExceptions(), ['others']);
        $hosts = self::getDistinctWorkerHosts();
        $minute = self::getCurrentMinute();

        //iterate through the last 5 minutes logs. assumption is that if by any change there is delay in the
        //processing, we will have at least last 5 minute data on the system
        $lastRun = (int) ( Cache::get('worker.stats.last.run', self::getCurrentMinute() - 30) ) - 5;


        //iterate through all hosts and all jobs mentioned in the exception.
        //logging only for those records which are available.
        for ( $i = $lastRun; $i < $minute; ++$i ) {
            foreach ( $hosts as $host ) {
                foreach ( $jobs as $job ) {
                    $jobMd5 = md5($job);
                    $identifier = "worker.stats.{$host}.{$jobMd5}.{$i}";

                    $counter = Cache::get($identifier, false);

                    if ( !$counter ) continue;

                    //log the stats into the system against the given host and job.
                    $stats = WorkerStat::firstOrNew([
                        'hostname' => $host,
                        'minute'   => $i,
                        'type'     => $job,
                    ]);

                    $stats->counter = $counter;
                    $stats->save();

                    //remove the stats cache from the system
                    Cache::forget($identifier);
                }
            }
        }

        //log the last run status on the cache
        Cache::set('worker.stats.last.run', $minute - 1);
    }

    /**
     * find all worker hosts that are still active in the system
     * @return array
     */
    private static function getDistinctWorkerHosts ()
    {
        $hosts = [];
        $records = WorkerProcess::distinct()->where('active', true)->get('hostname');
        foreach ( $records as $record )
            array_push($hosts, $record->hostname);

        return $hosts;
    }

    /**
     * find all the hosts and its corresponding stats against which data is the analyzed.
     * idea is to find how many jobs have been processed within a given time frame.
     */
    public static function getWorkerStats ()
    {
        //initialize the stats array onto which all data points would be populated
        $stats = [];

        //get the periods against which the stats is to be fetched and processed
        $periods = explode(',', LaravelUtility::getProperty('worker.stats.analysis.periods', '5,15,30,60'));

        $currentMinute = self::getCurrentMinute();
        $hosts = self::getDistinctWorkerHosts();

        //iterate through the active hosts and the mentioned time periods
        foreach ( $hosts as $host ) {
            foreach ( $periods as $period )
                $stats[ $host ]['stats'][ $period ] = self::getWorkerCounter($host, $currentMinute - $period);

            //get active processes right now
            $stats[ $host ]['active_processes'] = WorkerProcess::where('hostname', $host)->where('active', true)->count();
            //get active jobs which have yet not completed
            $stats[ $host ]['active_jobs'] = self::getActiveJobsOnServer($host);
        }

        //get the count of fifo jobs processed within given time period
        foreach ( $periods as $period ) {
            $dateTime = DateUtil::getPastTime($period);
            $stats['fifo'][ $period ] = WorkerJob::where('channel', 'fifo')->where('start_time', '>', $dateTime)->count();
        }

        return $stats;
    }

    /**
     * fetch no of jobs processed against the given host in the given time period.
     * this would be sum of all the jobs grouped by the host
     * @param $host
     * @param $period
     * @return mixed
     */
    private static function getWorkerCounter ($host, $period)
    {
        return WorkerStat::where('hostname', $host)->where('minute', '>=', $period)->sum('counter');
    }

    /**
     * Get the number of jobs that are still running and yet have any proper closure.
     * This is grouped on host and also against active processes only
     * @param $host
     * @return mixed
     */
    private static function getActiveJobsOnServer ($host)
    {
        $sql = "select count(1) counter from dz_worker_processes a, dz_worker_jobs b where a.hostname = b.hostname and a.pid = b.pid and a.active = 1 and b.end_time is null and b.failed_at is null and a.hostname = '{$host}'";

        return DB::select(DB::raw($sql))[0]->counter;
    }

}