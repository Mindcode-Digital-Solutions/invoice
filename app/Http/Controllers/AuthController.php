<?php

namespace App\Http\Controllers;

use App\Classes\ApiCatchErrors;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\Common\ErrorResponse;
use App\Http\Resources\Common\SuccessResponse;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        DB::beginTransaction();
        try {
            $user = User::where('username', $request['username'])->first();
            if ($user == null) {
                return (new ErrorResponse(['message' => 'User does not exist']))->response()->setStatusCode(422);
            }
            $token = $user->createToken('user_token')->accessToken;
            DB::commit();
            $resource = new UserResource($user);
            return new SuccessResponse(['data'=>['token' => $token, 'user' => $resource,'message'=>'User logged successfully']]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }
}
