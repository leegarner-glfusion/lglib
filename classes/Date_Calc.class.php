<?php
/**
 * Date calculation class.
 * Date_Calc is a calendar class used to calculate and
 * manipulate calendar dates and retrieve dates in a calendar
 * format. It does not rely on 32-bit system date stamps, so
 * you can display calendars and compare dates that date
 * pre 1970 and post 2038.
 *
 * This source file is subject to version 2.02 of the PHP license,
 * that is bundled with this package in the file LICENSE, and is
 * available at through the world-wide-web at
 * http://www.php.net/license/2_02.txt.
 * If you did not receive a copy of the PHP license and are unable to
 * obtain it through the world-wide-web, please send a note to
 * license@php.net so we can mail you a copy immediately.
 *
 * Copyright (c) 1999, 2000 ispi
 *
 * @access public
 *
 * @version 1.2.5
 * @author Monte Ohrt <monte@ispi.net>
 * @package evlist
 * @filesource
 *
 * modified by lgarner <lee@leegarner.com>- changed default formats & added
 *  weekOfMonth()
 *  endOfMonth()
 */

namespace LGLib;
// The constant telling us what day starts the week. Monday (1) is the
// international standard. Redefine this to 0 if you want weeks to
// begin on Sunday.
//define('DATE_CALC_BEGIN_WEEKDAY', 1);
if (!defined('DATE_CALC_BEGIN_WEEKDAY')) {
    global $_CONF;
    switch ($_CONF['week_start']) {
    case 'Mon':
        // week begins on Monday
        define('DATE_CALC_BEGIN_WEEKDAY', 1);
        break;
    case 'Sun':
    default:
        // week begins on Sunday
        define('DATE_CALC_BEGIN_WEEKDAY', 0);
        break;
    }
}

/**
*   Date calculation class
*   @package    evlist
*/
class Date_Calc {

    /**
     * Returns the current local date. NOTE: This function
     * retrieves the local date using strftime(), which may
     * or may not be 32-bit safe on your system.
     *
     * @access public
     * @param   string  $format the strftime() format to return the date
     * @return  string      The current date in specified format
     */

    public static function dateNow($format="%Y-%m-%d")
    {
        return(strftime($format,time()));
    }


     /**
     * Returns true for valid date, false for invalid date.
     *
     * @access  public
     * @param   string  $day    Day in format DD
     * @param   string  $month  Month in format MM
     * @param   string  $year   Year in format CCYY
     * @return  boolean     true/false
     */
    public static function isValidDate($day, $month, $year)
    {

        if(empty($year) || empty($month) || empty($day))
            return false;

        // must be digits only
        if(preg_match("/\D/",$year))
            return false;
        if(preg_match("/\D/",$month))
            return false;
        if(preg_match("/\D/",$day))
            return false;

        if($year < 0 || $year > 9999)
            return false;
        if($month < 1 || $month > 12)
            return false;
        if($day < 1 || $day > 31 || $day > Date_Calc::daysInMonth($month,$year))
            return false;

        return true;

    } // end func isValidDate


    /**
     * Determine if a given year is a leap year.
     *
     * @param   string  $year   Year to check.
     * @return  boolean     True if $year is a leap year
     */
    public static function isLeapYear($year="")
    {

        if(empty($year))
            $year = Date_Calc::dateNow("%Y");

        if(strlen($year) != 4)
            return false;

        if(preg_match("/\D/",$year))
            return false;

        return (($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0);

    } // end func isLeapYear


    /**
     * Determines if given date is a future date from now.
     *
     * @access  public
     * @param   string  $day    Day in format DD
     * @param   string  $month  Month in format MM
     * @param   string  $year   Year in format CCYY
     * @return  boolean true/false
     */
    public static function isFutureDate($day, $month, $year)
    {
        $this_year = Date_Calc::dateNow("%Y");
        $this_month = Date_Calc::dateNow("%m");
        $this_day = Date_Calc::dateNow("%d");


        if($year > $this_year)
            return true;
        elseif($year == $this_year)
            if($month > $this_month)
                return true;
            elseif($month == $this_month)
                if($day > $this_day)
                    return true;

        return false;

    } // end func isFutureDate


    /**
     * Determines if given date is a past date from now.
     *
     * @access  public
     * @param   string  $day    Day in format DD
     * @param   string  $month  Month in format MM
     * @param   string  $year   Year in format CCYY
     * @return  boolean true/false
     */
    public static function isPastDate($day, $month, $year)
    {
        $this_year = Date_Calc::dateNow("%Y");
        $this_month = Date_Calc::dateNow("%m");
        $this_day = Date_Calc::dateNow("%d");


        if ($year < $this_year) {
            return true;
        } elseif ($year == $this_year) {
            if ($month < $this_month) {
                return true;
            } elseif ($month == $this_month) {
                if($day < $this_day) {
                    return true;
                }
            }
        }
        return false;
    } // end func isPastDate


    /**
     * Returns day of week for given date, 0=Sunday.
     *
     * @access  public
     * @param   string  $day    Day in format DD, default is current local day
     * @param   string  $month  Month in format MM, default is current local month
     * @param   string  $year   Year in format CCYY, default is current local year
     * @return  integer     Weekday number
     */
    public static function dayOfWeek($day="", $month="", $year="")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        if ($month > 2) {
            $month -= 2;
        } else {
            $month += 10;
            $year--;
        }

        $day = ( floor((13 * $month - 1) / 5) +
                $day + ($year % 100) +
                floor(($year % 100) / 4) +
                floor(($year / 100) / 4) - 2 *
                floor($year / 100) + 77);

        $weekday_number = (($day - 7 * floor($day / 7)));
        return $weekday_number;
    } // end func dayOfWeek


    /**
     * Returns week of the year, first Sunday is first day of first week.
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @return  integer         Week Number
     */
    public static function weekOfYear($day,$month,$year)
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        $week_year = $year - 1501;
        $week_day = $week_year * 365 + floor($week_year / 4) - 29872 + 1 -
            floor($week_year / 100) + floor(($week_year - 300) / 400);

        $week_number =
            ceil((Date_Calc::julianDate($day,$month,$year) + floor(($week_day + 4) % 7)) / 7);
        return $week_number;
    } // end func weekOfYear


    /**
     * Returns number of days since 31 December of year before given date.
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @return  integer         Julian date
     */
    public static function julianDate($day="",$month="",$year="")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        $days = array(0,31,59,90,120,151,181,212,243,273,304,334);
        $julian = ($days[$month - 1] + $day);
        if($month > 2 && Date_Calc::isLeapYear($year)) {
            $julian++;
        }
        return($julian);
    } // end func julianDate


    /**
     * Returns quarter of the year for given date.
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @return  integer         Quarter (1 - 4)
     */
    public static function quarterOfYear($day="",$month="",$year="")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }
        $year_quarter = (intval(($month - 1) / 3 + 1));
        return $year_quarter;
    } // end func quarterOfYear


    /**
     * Returns date of begin of next month of given date.
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function beginOfNextMonth($day="",$month="",$year="",$format="%Y-%m-%d")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        if($month < 12) {
            $month++;
            $day=1;
        } else {
            $year++;
            $month=1;
            $day=1;
        }
        return Date_Calc::dateFormat($day,$month,$year,$format);
    } // end func beginOfNextMonth


    /**
     * Returns date of the last day of next month of given date.
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function endOfNextMonth($day="",$month="",$year="",$format="%Y-%m-%d")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        if ($month < 12) {
            $month++;
        } else {
            $year++;
            $month=1;
        }
        $day = Date_Calc::daysInMonth($month,$year);
        return Date_Calc::dateFormat($day,$month,$year,$format);
    } // end func endOfNextMonth


    /**
     * Returns date of the first day of previous month of given date.
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function beginOfPrevMonth($day="",$month="",$year="",$format="%Y-%m-%d")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        if ($month > 1) {
            $month--;
            $day=1;
        } else {
            $year--;
            $month=12;
            $day=1;
        }
        return Date_Calc::dateFormat($day,$month,$year,$format);
    } // end func beginOfPrevMonth


    /**
     * Returns date of the last day of previous month for given date.
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function endOfPrevMonth($day="",$month="",$year="",$format="%Y-%m-%d")
    {
        if(empty($year))
            $year = Date_Calc::dateNow("%Y");
        if(empty($month))
            $month = Date_Calc::dateNow("%m");
        if(empty($day))
            $day = Date_Calc::dateNow("%d");

        if($month > 1)
        {
            $month--;
        }
        else
        {
            $year--;
            $month=12;
        }

        $day = Date_Calc::daysInMonth($month,$year);

        return Date_Calc::dateFormat($day,$month,$year,$format);

    } // end func endOfPrevMonth


    /**
     * Returns date of the next weekday of given date, skipping from Friday to Monday.
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function nextWeekday($day="",$month="",$year="",$format="%Y-%m-%d")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        $days = Date_Calc::dateToDays($day,$month,$year);
        $dow = Date_Calc::dayOfWeek($day,$month,$year);
        if ($dow  == 5) {
            $days += 3;
        } elseif ($dow == 6) {
            $days += 2;
        } else {
            $days += 1;
        }
        return(Date_Calc::daysToDate($days,$format));
    } // end func nextWeekday


    /**
     * Returns date of the previous weekday, skipping from Monday to Friday.
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function prevWeekday($day="",$month="",$year="",$format="%Y-%m-%d")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        $days = Date_Calc::dateToDays($day,$month,$year);
        if (Date_Calc::dayOfWeek($day,$month,$year) == 1) {
            $days -= 3;
        } elseif (Date_Calc::dayOfWeek($day,$month,$year) == 0) {
            $days -= 2;
        } else {
            $days -= 1;
        }
        return(Date_Calc::daysToDate($days,$format));
    } // end func prevWeekday


    /**
     * Returns date of the next specific day of the week
     * from the given date.
     *
     * @param   integer $dow    Day of week, 0=Sunday
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @param   boolean $onOrAfter  If true and days are same, returns current day
     * @return  string          Ddate in given format
     */
    public static function nextDayOfWeek($dow,$day="",$month="",$year="",$format="%Y-%m-%d",$onOrAfter=false)
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        $days = Date_Calc::dateToDays($day,$month,$year);
        $curr_weekday = Date_Calc::dayOfWeek($day,$month,$year);
        if($curr_weekday == $dow) {
            if(!$onOrAfter) $days += 7;
        } elseif($curr_weekday > $dow) {
            $days += 7 - ( $curr_weekday - $dow );
        } else {
            $days += $dow - $curr_weekday;
        }
        return(Date_Calc::daysToDate($days,$format));
    } // end func nextDayOfWeek


    /**
     * Returns date of the previous specific day of the week
     * from the given date.
     *
     * @param   integer $dow    Day of week, 0=Sunday
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @param   boolean $onOrBefore True for before current date, False for after
     * @return  string          Date in given format
     */
    public static function prevDayOfWeek($dow,$day="",$month="",$year="",$format="%Y-%m-%d",$onOrBefore=false)
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        $days = Date_Calc::dateToDays($day,$month,$year);
        $curr_weekday = Date_Calc::dayOfWeek($day,$month,$year);
        if ($curr_weekday == $dow) {
            if (!$onOrBefore) $days -= 7;
        } elseif($curr_weekday < $dow) {
            $days -= (7 - ( $dow - $curr_weekday ));
        } else {
            $days -= $curr_weekday - $dow;
        }
        return(Date_Calc::daysToDate($days,$format));
    } // end func prevDayOfWeek


    /**
     * Returns date of the next specific day of the week on or after the given date.
     *
     * @param   integer $dow    Day of week, 0=Sunday
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function nextDayOfWeekOnOrAfter($dow,$day="",$month="",$year="",$format="%Y-%m-%d")
    {
        return(Date_Calc::nextDayOfWeek($dow,$day="",$month="",$year="",$format="%Y-%m-%d",true));
    } // end func nextDayOfWeekOnOrAfter


    /**
     * Returns date of the previous specific day of the week on or before the given date.
     *
     * @param   integer $dow    Day of week, 0=Sunday
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function prevDayOfWeekOnOrBefore($dow,$day="",$month="",$year="",$format="%Y-%m-%d")
    {
        return(Date_Calc::prevDayOfWeek($dow,$day="",$month="",$year="",$format="%Y-%m-%d",true));
    } // end func prevDayOfWeekOnOrAfter


    /**
     * Returns date of day after given date.
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function nextDay($day="",$month="",$year="",$format="%Y-%m-%d")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        $days = Date_Calc::dateToDays($day,$month,$year);
        return(Date_Calc::daysToDate($days + 1,$format));
    } // end func nextDay


    /**
     * Returns date of day before given date.
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function prevDay($day="",$month="",$year="",$format="%Y-%m-%d")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        $days = Date_Calc::dateToDays($day,$month,$year);
        return(Date_Calc::daysToDate($days - 1,$format));
    } // end func prevDay


    /**
     * Sets century for 2 digit year.
     * 51-99 is 19, else 20
     *
     * @param   string  $year   2-digit year
     * @return  string          4-digit year
     */
    public static function defaultCentury($year)
    {
        if (strlen($year) == 1) {
            $year = "0$year";
        }
        if ($year > 50) {
            return( "19$year" );
        } else {
            return( "20$year" );
        }
    } // end func defaultCentury


    /**
     * Returns number of days between two given dates.
     *
     * @param   string  $day1   Start Day in format DD, default current local day
     * @param   string  $month1 Start Month in format MM, default current local month
     * @param   string  $year1  Start Year in format CCYY, default current local year
     * @param   string  $day2   End Day in format DD, default current local day
     * @param   string  $month2 End Month in format MM, default current local month
     * @param   string  $year2  End Year in format CCYY, default current local year
     * @return  integer     Absolute value of number of days between dates, -1 for error
     */
    public static function dateDiff($day1,$month1,$year1,$day2,$month2,$year2)
    {
        if(!Date_Calc::isValidDate($day1,$month1,$year1))
            return -1;
        if(!Date_Calc::isValidDate($day2,$month2,$year2))
            return -1;

        return(abs((Date_Calc::dateToDays($day1,$month1,$year1))
                    - (Date_Calc::dateToDays($day2,$month2,$year2))));
    } // end func dateDiff


    /**
     * Find the number of days in the given month.
     *
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year as YYYY, used only for leap year
     * @return  integer     Number of days in the month
     */
    public static function daysInMonth($month="",$year="")
    {
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }

        if ($month == 2) {
            // Get February days depending on leap year
            if(empty($year)) {
                $year = Date_Calc::dateNow("%Y");
            }
            if (Date_Calc::isLeapYear($year)) {
                return 29;
            } else {
                return 28;
            }
        } elseif ($month == 4 or $month == 6 or $month == 9 or $month == 11) {
            return 30;
        } else {
            return 31;
        }
    }


    /**
     * Returns the number of rows on a calendar month.
     * Useful for determining the number of rows when displaying a typical
     * month calendar.
     *
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @return  integer         Number of weeks or partial weeks
     */
    public static function weeksInMonth($month="",$year="")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (DATE_CALC_BEGIN_WEEKDAY == 1) {
            // starts on monday
            if (Date_Calc::firstOfMonthWeekday($month,$year) == 0) {
                $first_week_days = 1;
            } else {
                $first_week_days = 7 - (Date_Calc::firstOfMonthWeekday($month,$year) - 1);
            }
        } elseif(DATE_CALC_BEGIN_WEEKDAY == 6) {
            // starts on saturday
            if(Date_Calc::firstOfMonthWeekday($month,$year) == 0) {
                $first_week_days = 6;
            } else {
                $first_week_days = 7 - (Date_Calc::firstOfMonthWeekday($month,$year) + 1);
            }
        } else {
            // starts on sunday
            $first_week_days = 7 - Date_Calc::firstOfMonthWeekday($month,$year);
        }
        return ceil(((Date_Calc::daysInMonth($month,$year) - $first_week_days) / 7) + 1);
    } // end func weeksInMonth


    /**
     * Find the day of the week for the first of the month of given date.
     *
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @return  integer         Number of weekday for the first day, 0=Sunday
     */
    public static function firstOfMonthWeekday($month="",$year="")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        return(Date_Calc::dayOfWeek("01",$month,$year));
    } // end func firstOfMonthWeekday


    /**
     * Return date of first day of month of given date.
     *
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function beginOfMonth($month="",$year="",$format="%Y-%m-%d")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        return(Date_Calc::dateFormat("01",$month,$year,$format));
    } // end of func beginOfMonth


    /**
     * Find the month day of the beginning of week for given date,
     * Using DATE_CALC_BEGIN_WEEKDAY. (can return weekday of prev month.)
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function beginOfWeek($day="",$month="",$year="",$format="%Y-%m-%d")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        $this_weekday = Date_Calc::dayOfWeek($day,$month,$year);
        if (DATE_CALC_BEGIN_WEEKDAY == 1) {
            if ($this_weekday == 0) {
                $beginOfWeek = Date_Calc::dateToDays($day,$month,$year) - 6;
            } else {
                $beginOfWeek = Date_Calc::dateToDays($day,$month,$year) - $this_weekday + 1;
            }
        } else {
            $beginOfWeek = (Date_Calc::dateToDays($day,$month,$year) - $this_weekday);
            /*  $beginOfWeek = (Date_Calc::dateToDays($day,$month,$year)
                - ($this_weekday - DATE_CALC_BEGIN_WEEKDAY)); */
        }
        return(Date_Calc::daysToDate($beginOfWeek,$format));
    } // end of func beginOfWeek


    /**
     * Find the month day of the end of week for given date,
     * using DATE_CALC_BEGIN_WEEKDAY. (can return weekday
     * of following month.)
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function endOfWeek($day="",$month="",$year="",$format="%Y-%m-%d")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        $this_weekday = Date_Calc::dayOfWeek($day,$month,$year);
        $last_dayOfWeek = (Date_Calc::dateToDays($day,$month,$year)
            + (6 - $this_weekday + DATE_CALC_BEGIN_WEEKDAY));

        return(Date_Calc::daysToDate($last_dayOfWeek,$format));
    } // end func endOfWeek


    /**
     * Find the month day of the beginning of week after given date,
     * Using DATE_CALC_BEGIN_WEEKDAY. (can return weekday of prev month.)
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function beginOfNextWeek($day="",$month="",$year="",$format="%Y-%m-%d")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        $date = Date_Calc::daysToDate(Date_Calc::dateToDays($day+7,$month,$year),"%Y-%m-%d");
        $next_week_year = substr($date,0,4);
        $next_week_month = substr($date,5,2);
        $next_week_day = substr($date,8,2);
        $this_weekday = Date_Calc::dayOfWeek($next_week_day,$next_week_month,$next_week_year);
        $beginOfWeek = (Date_Calc::dateToDays($next_week_day,$next_week_month,$next_week_year)
            - ($this_weekday - DATE_CALC_BEGIN_WEEKDAY));

        return(Date_Calc::daysToDate($beginOfWeek,$format));
    } // end func beginOfNextWeek

    /**
     * Find the month day of the beginning of week before given date,
     * Using DATE_CALC_BEGIN_WEEKDAY. (can return weekday of prev month.)
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function beginOfPrevWeek($day="",$month="",$year="",$format="%Y-%m-%d")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        $date = Date_Calc::daysToDate(Date_Calc::dateToDays($day-7,$month,$year),"%Y-%m-%d");
        $next_week_year = substr($date,0,4);
        $next_week_month = substr($date,4,2);
        $next_week_day = substr($date,6,2);

        $this_weekday = Date_Calc::dayOfWeek($next_week_day,$next_week_month,$next_week_year);
        $beginOfWeek = (Date_Calc::dateToDays($next_week_day,$next_week_month,$next_week_year)
            - ($this_weekday - DATE_CALC_BEGIN_WEEKDAY));

        return(Date_Calc::daysToDate($beginOfWeek,$format));
    } // end func beginOfPrevWeek


    /**
     * Return an array with days in week
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  array           $week[$weekday]
     */
    public static function getCalendarWeek($day="",$month="",$year="",$format="%Y-%m-%d")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        $week_array = array();

        // date for the column of week
        $curr_day = Date_Calc::beginOfWeek($day,$month,$year,"%E");
        for($counter=0; $counter <= 6; $counter++) {
            $week_array[$counter] = Date_Calc::daysToDate($curr_day,$format);
            $curr_day++;
        }
        return $week_array;
    } // end func getCalendarWeek


    /**
     * Return a set of arrays to construct a calendar month for the given date.
     *
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  array           $month[$row][$col]
     */
    public static function getCalendarMonth($month="",$year="",$format="%Y-%m-%d")
    {
        if(empty($year)) $year = Date_Calc::dateNow("%Y");
        if(empty($month)) $month = Date_Calc::dateNow("%m");
        $month_array = array();
        // starts on monday
        if(DATE_CALC_BEGIN_WEEKDAY == 1) {
            if(Date_Calc::firstOfMonthWeekday($month,$year) == 0) {
                $curr_day = Date_Calc::dateToDays("01",$month,$year) - 6;
            } else {
                $curr_day = Date_Calc::dateToDays("01",$month,$year) - Date_Calc::firstOfMonthWeekday($month,$year) + 1;
            }
        // starts on saturday
        } elseif(DATE_CALC_BEGIN_WEEKDAY == 6) {
            if(Date_Calc::firstOfMonthWeekday($month,$year) == 0) {
                $curr_day = Date_Calc::dateToDays("01",$month,$year) - 1;
            } else {
                $curr_day = Date_Calc::dateToDays("01",$month,$year) - Date_Calc::firstOfMonthWeekday($month,$year) - 1;
            }
        // starts on sunday
        } else {
            $curr_day = (Date_Calc::dateToDays("01",$month,$year) - Date_Calc::firstOfMonthWeekday($month,$year));
        }
        // number of days in this month
        $daysInMonth = Date_Calc::daysInMonth($month,$year);

        $weeksInMonth = Date_Calc::weeksInMonth($month,$year);
        for ($row_counter=0; $row_counter < $weeksInMonth; $row_counter++) {
            for ($column_counter=0; $column_counter <= 6; $column_counter++) {
                $month_array[$row_counter][$column_counter] = Date_Calc::daysToDate($curr_day,$format);
                $curr_day++;
            }
        }
        return $month_array;
    }


    /**
     * Return a set of arrays to construct a calendar year for the given date.
     *
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  array           $year[$month][$row][$col]
     */
    public static function getCalendarYear($year="",$format="%Y-%m-%d")
    {
        if(empty($year))
            $year = Date_Calc::dateNow("%Y");

        $year_array = array();

        for($curr_month=0; $curr_month <=11; $curr_month++)
            $year_array[$curr_month] = Date_Calc::getCalendarMonth(sprintf("%02d",$curr_month+1),$year,$format);

        return $year_array;
    } // end func getCalendarYear


    /**
     * Converts a date to number of days since a distant unspecified epoch.
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @return  integer         Number of days
     */
    public static function dateToDays($day,$month,$year)
    {

        $century = substr($year,0,2);
        $year = substr($year,2,2);

        if ($month > 2) {
            $month -= 3;
        } else {
            $month += 9;
            if ($year) {
                $year--;
            } else {
                $year = 99;
                $century --;
            }
        }

        return ( floor((  146097 * $century)    /  4 ) +
                floor(( 1461 * $year)        /  4 ) +
                floor(( 153 * $month +  2) /  5 ) +
                    $day +  1721119);
    } // end func dateToDays


    /**
     * Converts number of days to a distant unspecified epoch.
     *
     * @param   integer $days   Number of days
     * @param   string  $format Format for returned date
     * @return  string          Date in specified format
     */
    public static function daysToDate($days,$format="%Y-%m-%d")
    {
        $days       -= 1721119;
        $century    = floor(( 4 * $days -  1) /  146097);
        $days       = floor(4 * $days - 1 - 146097 * $century);
        $day        = floor($days /  4);

        $year       = floor(( 4 * $day +  3) /  1461);
        $day        = floor(4 * $day +  3 -  1461 * $year);
        $day        = floor(($day +  4) /  4);

        $month      = floor(( 5 * $day -  3) /  153);
        $day        = floor(5 * $day -  3 -  153 * $month);
        $day        = floor(($day +  5) /  5);

        if ($month < 10) {
            $month +=3;
        } else {
            $month -=9;
            if ($year++ == 99) {
                $year = 0;
                $century++;
            }
        }

        $century = sprintf("%02d",$century);
        $year = sprintf("%02d",$year);

        return(Date_Calc::dateFormat($day,$month,$century.$year,$format));
    } // end func daysToDate


    /**
     * Calculates the date of the Nth weekday of the month.
     * Example: the second Saturday of January 2000.
     *
     * @param   string  $occurance      Occurance: 1=first, 2=second, 3=third, etc.
     * @param   string  $dayOfWeek      0=Sunday, 1=Monday, etc.
     * @param   string  $month          Month in format MM
     * @param   string  $year           Year in format CCYY
     * @param   string  $format         Format for returned date
     * @return  string              Date in given format
     */
    public static function NWeekdayOfMonth($occurance,$dayOfWeek,$month,$year,$format="%Y-%m-%d")    {

        $year = sprintf("%04d",$year);
        $month = sprintf("%02d",$month);

        $DOW1day = sprintf("%02d",(($occurance - 1) * 7 + 1));
        $DOW1 = Date_Calc::dayOfWeek($DOW1day,$month,$year);

        $wdate = ($occurance - 1) * 7 + 1 +
                (7 + $dayOfWeek - $DOW1) % 7;

        if ($wdate > Date_Calc::daysInMonth($month,$year)) {
            if ($occurance == 5) {
                // Getting the last day overshot the month, go back a week
                $wdate -= 7;
                return Date_Calc::dateFormat($wdate,$month,$year,$format);
            } else {
                // For $occurance === 1 through 4 this is an error
                return -1;
            }
        } else {
            return(Date_Calc::dateFormat($wdate,$month,$year,$format));
        }
    } // end func NWeekdayOfMonth


    /**
     * Formats the date in the given format, much like strfmt().
     * This function is used to alleviate the problem with 32-bit numbers
     * for dates pre 1970 or post 2038, as strfmt() has on most systems.
     * Most of the formatting options are compatible.
     *
     * Formatting options:
     *
     * * %a     abbreviated weekday name (Sun, Mon, Tue)
     * * %A     full weekday name (Sunday, Monday, Tuesday)
     * * %b     abbreviated month name (Jan, Feb, Mar)
     * * %B     full month name (January, February, March)
     * * %d     day of month (range 00 to 31)
     * * %e     day of month, single digit (range 0 to 31)
     * * %E     number of days since unspecified epoch (integer)
     *             (%E is useful for passing a date in a URL as
     *             an integer value. Then simply use
     *             daysToDate() to convert back to a date.)
     * * %j     day of year (range 001 to 366)
     * * %m     month as decimal number (range 1 to 12)
     * * %n     newline character (\n)
     * * %t     tab character (\t)
     * * %w     weekday as decimal (0 = Sunday)
     * * %U     week number of current year, first sunday as first week
     * * %y     year as decimal (range 00 to 99)
     * * %Y     year as decimal including century (range 0000 to 9999)
     * * %%     literal '%'
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   string  $format Format for returned date
     * @return  string          Date in given format
     */
    public static function dateFormat($day,$month,$year,$format='%y-%m-%d')
    {
        if (!Date_Calc::isValidDate($day,$month,$year)) {
            $year = Date_Calc::dateNow("%Y");
            $month = Date_Calc::dateNow("%m");
            $day = Date_Calc::dateNow("%d");
        }

        $output = "";

        for ($strpos = 0; $strpos < strlen($format); $strpos++) {
            $char = substr($format,$strpos,1);
            if  ($char == "%") {
                $nextchar = substr($format,$strpos + 1,1);
                switch($nextchar) {
                case "a":
                    $output .= Date_Calc::getWeekdayAbbrname($day,$month,$year);
                    break;
                case "A":
                    $output .= Date_Calc::getWeekdayFullname($day,$month,$year);
                    break;
                case "b":
                    $output .= Date_Calc::getMonthAbbrname($month);
                    break;
                case "B":
                    $output .= Date_Calc::getMonthFullname($month);
                    break;
                case "d":
                    $output .= sprintf("%02d",$day);
                    break;
                case "e":
                    $output .= $day;
                    break;
                case "E":
                    $output .= Date_Calc::dateToDays($day,$month,$year);
                    break;
                case "j":
                    $output .= Date_Calc::julianDate($day,$month,$year);
                    break;
                case "m":
                    $output .= sprintf("%02d",$month);
                    break;
                case "n":
                    $output .= "\n";
                    break;
                case "t":
                    $output .= "\t";
                    break;
                case "w":
                   $output .= Date_Calc::dayOfWeek($day,$month,$year);
                    break;
                case "U":
                    $output .= Date_Calc::weekOfYear($day,$month,$year);
                    break;
                case "y":
                    $output .= substr($year,2,2);
                    break;
                case "Y":
                    $output .= $year;
                    break;
                case "%":
                    $output .= "%";
                    break;
                default:
                    $output .= $char.$nextchar;
                }
                $strpos++;
            }
            else {
                $output .= $char;
            }
        }
        return $output;
    } // end func dateFormat


    /**
     * Returns the current local year in format CCYY.
     *
     * @return  string  Current year in format CCYY
     */
    public static function getYear()
    {
        return Date_Calc::dateNow("%Y");
    } // end func getYear

    /**
     * Returns the current local month in format MM.
     *
     * @return  string  Current month in format MM
     */
    public static function getMonth()
    {
        return Date_Calc::dateNow("%m");
    } // end func getMonth


    /**
     * Returns the current local day in format DD.
     *
     * @return  string      Current day in format DD
     */
    public static function getDay()
    {
        return Date_Calc::dateNow("%d");
    } // end func getDay


    /**
     * Returns the full month name for the given month.
     *
     * @param   string  $month  Month in format MM
     * @return  string          Full month name
     */
    public static function getMonthFullname($month)
    {
        $month = (int)$month;

        if(empty($month))
            $month = Date_Calc::dateNow("%m");

        $month_names = Date_Calc::getMonthNames();
        return $month_names[$month];
        // getMonthNames returns months with correct indexes
        //return $month_names[($month - 1)];

    } // end func getMonthFullname


    /**
     * Returns the abbreviated month name for the given month.
     *
     * @uses    Date_Calc::getMonthFullname
     * @param   string  $month   Month in format MM
     * @param   integer $length Optional length of abbreviation, default is 3
     * @return  string      Abbreviated month name
     */
    public static function getMonthAbbrname($month,$length=3)
    {
        $month = (int)$month;

        if(empty($month))
            $month = Date_Calc::dateNow("%m");
        return substr(Date_Calc::getMonthFullname($month), 0, $length);
    } // end func getMonthAbbrname


    /**
     * Returns the full weekday name for the given date.
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @return  string          Full month name
     */
    public static function getWeekdayFullname($day="",$month="",$year="")
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }

        $weekday_names = Date_Calc::getWeekDays();
        $weekday = Date_Calc::dayOfWeek($day,$month,$year);
        return $weekday_names[$weekday];
    } // end func getWeekdayFullname

    /**
     * Returns the abbreviated weekday name for the given date.
     *
     * @param   string  $day    Day in format DD, default current local day
     * @param   string  $month  Month in format MM, default current local month
     * @param   string  $year   Year in format CCYY, default current local year
     * @param   integer $length Optional length of abbreviation, default is 3
     *
     * @access public
     *
     * @return string full month name
     * @see Date_Calc::getWeekdayFullname
     */

    public static function getWeekdayAbbrname($day="",$month="",$year="",$length=3)
    {
        if (empty($year)) {
            $year = Date_Calc::dateNow("%Y");
        }
        if (empty($month)) {
            $month = Date_Calc::dateNow("%m");
        }
        if (empty($day)) {
            $day = Date_Calc::dateNow("%d");
        }
        return substr(Date_Calc::getWeekdayFullname($day,$month,$year), 0, $length);
    } // end func getWeekdayFullname


    /**
     * Returns the numeric month from the month name or an abreviation
     *
     * Both August and Aug would return 8.
     * Month name is case insensitive.
     *
     * @param   string  $month  Month name
     * @return  integer         Month number
     */
    public static function getMonthFromFullName($month)
    {
        $month = strtolower($month);
        $months = Date_Calc::getMonthNames();
        while (list($id, $name) = each($months)) {
            if (ereg($month, strtolower($name))) {
                return $id;
            }
        }
        return 0;
    }


    /**
     * Retunrs an array of month names
     *
     * Used to take advantage of the setlocale function to return
     * language specific month names.
     * @todo cache values to some global array to avoid preformace hits when called more than once.
     *
     * @returns array An array of month names
     */
    public static function getMonthNames()
    {
        $months = array();
        for ($i=1; $i<13; $i++) {
            $months[$i] = strftime('%B', mktime(0, 0, 0, $i, 1, 2001));
        }
        return($months);
    }


    /**
     * Returns an array of week days.
     *
     * Used to take advantage of the setlocale function to
     * return language specific week days.
     * @todo cache values to some global array to avoid preformace hits when called more than once.
     *
     * @return  array An array of week day names
     */
    public static function getWeekDays()
    {
        $weekdays = array();
        for ($i=0; $i<7; $i++) {
            $weekdays[$i] = strftime('%A', mktime(0, 0, 0, 1, $i, 2001));
        }
        return($weekdays);
    }


    /**
     * Returns the week of the month in which a date falls.
     *
     * @param   integer $day    Day of the month
     * @return  integer         Week number
     */
    public static function weekOfMonth($day)
    {
        $prevweek = $day - 7;
        if ($prevweek > 21)
            $instance = 5;
        elseif ($prevweek > 14)
            $instance = 4;
        elseif ($prevweek > 7)
            $instance = 3;
        elseif ($prevweek > 0)
            $instance = 2;
        else
            $instance = 1;

        return $instance;
    }


    /**
     * Returns the end date of the month.
     *
     * @param   integer $month  Month
     * @param   integer $year   Year
     * @param   string  $format Format to use for the date
     * @return  string  Formatted date for last day
     */
    public static function endOfMonth($month='',$year='',$format='%Y-%m-%d')
    {
        if(empty($year))
            $year = self::dateNow("%Y");
        if(empty($month))
            $month = self::dateNow("%m");
        $day = self::daysInMonth($month,$year);

        return self::dateFormat($day,$month,$year,$format);
     }

} // end class Date_Calc

/**
 * For compatibility with plugins that have used the lgDate class.
 */
class lgDate extends Date_Calc
{}

?>
