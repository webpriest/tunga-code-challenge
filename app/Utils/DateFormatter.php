<?php

namespace App\Utils;

use Carbon\Carbon;

class DateFormatter
{
    /**
     * Convert date to Database format (Y-m-d)
     */
    public static function dbFormat($date)
    {
        return strftime("%Y-%m-%d", strtotime($date));
    }
    
    /**
     * Calculate age from date of birth
     */
    public static function getAge($date_of_birth)
    {
        return Carbon::parse(self::dbFormat($date_of_birth))->diff(Carbon::now())->y;
    }
}