<?php

namespace App\Utils;

use Carbon\Carbon;

class DateFormatter
{
    public static function dbFormat($date)
    {
        return strftime("%Y-%m-%d", strtotime($date));
    }
    
    public static function getAge($date_of_birth)
    {
        return Carbon::parse(self::dbFormat($date_of_birth))->diff(Carbon::now())->y;
    }
}