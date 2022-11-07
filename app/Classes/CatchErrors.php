<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CatchErrors
{
    public static function rollback($e, $message = 'Something went wrong! Process not completed', $route = null)
    {
        DB::rollBack();
        return self::throw($e, $message, $route);
    }

    public static function throw($e, $message = 'Something went wrong! Process not completed', $route = null)
    {
        Log::info($e);
        $message == null ? $message = $e->getMessage() : $message;
        if ($route == null) {
            return redirect()->back()->withErrors(['error' => $message]);
        }
        return redirect($route)->withErrors(['error' => $message]);
    }

}
