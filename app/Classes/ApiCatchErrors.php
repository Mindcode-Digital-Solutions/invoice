<?php

namespace App\Classes;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiCatchErrors
{
    public static function rollback($e,$message = 'Something went wrong! Process not completed')
    {
        DB::rollBack();
        self::throw($e,$message);
    }

    public static function throw($e, $message = 'Something went wrong! Process not completed')
    {
        Log::info($e);
        throw new HttpResponseException(response()->json(["message"=> $message], 500));
    }

    public static function validationError($validator){
        throw new HttpResponseException(response()->json(["message"=>$validator->errors()->first()], 422));
    }
    
}
