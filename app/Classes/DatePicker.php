<?php

namespace App\Classes;

class DatePicker
{
    public static function format($date)
    {
        if ($date == null) return $date;
        return  date('Y-m-d', strtotime($date));
    }
}
