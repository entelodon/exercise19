<?php


namespace App\Helpers;


use App\Exceptions\InvalidDate;
use App\Exceptions\InvalidTimezone;
use Carbon\Carbon;
use DateTimeZone;

class DateHelper
{
    private function __construct() //This is a class that should never be instanced
    {
    }

    /**
     * @param string $date
     * @param string $format
     * @param string|null $timezone
     * @return Carbon
     * @throws InvalidTimezone
     */
    public static function getDateAsCarbon(string $date, string $format = 'Y-m-d', string $timezone = null):Carbon
    {
        $carbon = Carbon::createFromFormat($format, $date);
        if($timezone) {
            if (in_array($timezone, self::getValidTimezones())) {
                $carbon->timezone($timezone);
            }else{
                throw new InvalidTimezone('The timezone provided to the DateHelper::getDateAsCarbon is not a valid one.');
            }
        }
        return $carbon;
    }

    /**
     * @return array
     */
    public static function getValidTimezones(): array
    {
        return DateTimeZone::listIdentifiers();
    }

    /**
     * @param $date
     * @param string $format
     * @return string
     * @throws InvalidDate
     * @throws InvalidTimezone
     */
    public static function getFormattedDate($date, $format = 'Y-m-d'):string {
        if($date instanceof Carbon){
            return $date->format($format);
        }elseif (is_string($date)){
            return self::getDateAsCarbon($date)->format($format);
        }
        throw new InvalidDate('The date provided to the DateHelper::getFormattedDate is invalid. Valid types are string and an instance of Carbon');
    }
}
