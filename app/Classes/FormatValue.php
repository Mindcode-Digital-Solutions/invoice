<?php

namespace App\Classes;

class FormatValue
{
    public static function webHTTP($url)
    {
        if ($url != null && !strpos($url, '://')) {
            return 'http://' . $url;
        }
        return $url;
    }
    
}
