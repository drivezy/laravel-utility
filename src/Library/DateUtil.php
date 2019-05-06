<?php

namespace Drivezy\LaravelUtility\Library;

class DateUtil {
    /**
     * @param $sDate
     * @param $eDate
     * @return int
     */
    public static function getDateTimeDifference ($sDate, $eDate) {
        return ( strtotime($eDate) - strtotime($sDate) );
    }

    /**
     * @param $sTime
     * @param $eTime
     * @return float
     */
    public static function getTimeDifference ($sTime, $eTime) {
        $timeDifference = strtotime($eTime) - strtotime($sTime);

        return floor($timeDifference / ( 60 * 60 ));
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return float
     */
    public static function getDateDifference ($startDate, $endDate) {
        $dateDifference = ( strtotime($endDate) - strtotime($startDate) );

        return floor($dateDifference / ( 60 * 60 * 24 )) + 1;
    }

    /**
     * @param bool $time
     * @return bool|string
     */
    public static function getDateTime ($time = false) {
        if ( $time )
            return date('Y-m-d H:i:s', $time);
        else
            return date('Y-m-d H:i:s');
    }

    /**
     * @param bool $time
     * @return bool|string
     */
    public static function getDate ($time = false) {
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
    public static function getFutureDate ($days, $date = false) {
        if ( $date ) {
            $d = new \DateTime($date);
        } else
            $d = new \DateTime();
        $d->modify("+" . $days . " day");

        return $d->format('Y-m-d');
    }

    /**
     * @param $days
     * @param bool $date
     * @return string
     */
    public static function getPastDate ($days, $date = false) {
        if ( $date ) {
            $d = new \DateTime($date);
        } else
            $d = new \DateTime();
        $d->modify("-" . $days . " day");

        return $d->format('Y-m-d');
    }

    /**
     * @param $minutes
     * @param bool $date
     * @return bool|string
     */
    public static function getFutureTime ($minutes, $date = false) {
        if ( $date ) {
            return self::getDateTime(strtotime($date) + $minutes * 60);
        } else {
            return self::getDateTime(strtotime("now") + $minutes * 60);
        }
    }

    /**
     * @param $minutes
     * @param bool $date
     * @return bool|string
     */
    public static function getPastTime ($minutes, $date = false) {
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
    public static function getDisplayFormat ($date, $flag = false) {
        if ( $flag )
            return date('d F Y | h:i A', strtotime($date));

        return date('l, F d, Y h:i A', strtotime($date));
    }
}