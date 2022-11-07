<?php

namespace App\Classes;

use Exception;
use Illuminate\Support\Facades\Log;

class ActivityLog
{
    public static function log($message ='default', $performedOn = null,$data = null, $causer = null, $logName = null)
    {
        try{
            $new = activity(strtolower($logName));
            if (isset($causer) && $causer != null)  {$new = $new->causedBy($causer);}
            if (isset($performedOn) && $performedOn != null)  {$new = $new->performedOn($performedOn);}
            if (isset($data) && $data != null)  {$new = $new->withProperties($data);}
            $new->log($message);
        }
        catch(Exception $e){
            Log::critical("Error while creating activity log : ".$e->getMessage());
            Log::info($e);
        }
    }

    // ActivityLog::log('New company created',$company,$request->validated(),Auth::user());


}
