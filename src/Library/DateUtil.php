<?php

namespace Drivezy\LaravelUtility\Library;

use DateTime;
use DateTimeZone;

/**
 * Class DateUtil
 * @package Drivezy\LaravelUtility\Library
 */
class DateUtil
{
    /**
     * @param $sDate
     * @param $eDate
     * @return int
     */
    public static function getDateTimeDifference ($sDate, $eDate)
    {
        return ( strtotime($eDate) - strtotime($sDate) );
    }

    /**
     * @param $sTime
     * @param $eTime
     * @return float
     */
    public static function getTimeDifference ($sTime, $eTime)
    {
        $timeDifference = strtotime($eTime) - strtotime($sTime);

        return floor($timeDifference / ( 60 * 60 ));
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return float
     */
    public static function getDateDifference ($startDate, $endDate)
    {
        $dateDifference = ( strtotime($endDate) - strtotime($startDate) );

        return floor($dateDifference / ( 60 * 60 * 24 )) + 1;
    }

    /**
     * @param bool $time
     * @return bool|string
     */
    public static function getDate ($time = false)
    {
        if ( $time )
            return date('Y-m-d', $time);
        else
            return date('Y-m-d');
    }

    /**
     * @param $days
     * @param bool $date
     * @return string
     */
    public static function getFutureDate ($days, $date = false)
    {
        if ( $date ) {
            $d = new DateTime($date);
        } else
            $d = new DateTime();
        $d->modify("+" . $days . " day");

        return $d->format('Y-m-d');
    }

    /**
     * @param $days
     * @param bool $date
     * @return string
     */
    public static function getPastDate ($days, $date = false)
    {
        if ( $date ) {
            $d = new DateTime($date);
        } else
            $d = new DateTime();
        $d->modify("-" . $days . " day");

        return $d->format('Y-m-d');
    }

    /**
     * @param $minutes
     * @param bool $date
     * @return bool|string
     */
    public static function getFutureTime ($minutes, $date = false)
    {
        if ( $date ) {
            return self::getDateTime(strtotime($date) + $minutes * 60);
        } else {
            return self::getDateTime(strtotime("now") + $minutes * 60);
        }
    }

    /**
     * @param bool $time
     * @return bool|string
     */
    public static function getDateTime ($time = false)
    {
        if ( $time )
            return date('Y-m-d H:i:s', $time);
        else
            return date('Y-m-d H:i:s');
    }

    /**
     * @param $minutes
     * @param bool $date
     * @return bool|string
     */
    public static function getPastTime ($minutes, $date = false)
    {
        if ( $date ) {
            return self::getDateTime(strtotime($date) - $minutes * 60);
        } else {
            return self::getDateTime(strtotime("now") - $minutes * 60);
        }
    }

    /**
     * @param $date
     * @param bool $flag
     * @return false|string
     */
    public static function getDisplayFormat ($date, $flag = false)
    {
        if ( $flag )
            return date('F d Y | h:i A', strtotime($date));

        return date('F, l, d, Y h:i A', strtotime($date));
    }

    /**
     * @param $dob
     * @param $minimumAge
     * @return bool
     */
    public static function checkAgeEligibility ($dob, $minimumAge)
    {
        return date($dob) <= date('Y-m-d', strtotime('-' . $minimumAge . ' years'));
    }

    /*
     * @param $dateTime
     * @return false|string
     */
    public static function getTimeZone ($dateTime)
    {
        $utc_date = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $dateTime,
            new DateTimeZone('UTC')
        );

        $acst_date = clone $utc_date;
        $acst_date->setTimeZone(new DateTimeZone('America/Los_Angeles'));

        return $acst_date->format('Y-m-d H:i:s');
    }
}
