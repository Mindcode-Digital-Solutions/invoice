<?php

namespace App\Http\Controllers;

use App\Classes\ApiCatchErrors;
use App\Http\Requests\AccountStoreRequest;
use App\Http\Resources\AccountResource;
use App\Http\Resources\Common\SuccessResponse;
use App\Models\Account;
use Exception;
use Illuminate\Support\Facades\DB;

class AccountsController extends Controller
{
    public function index(){
        try {
            $records = Account::paginate();
            $resource = AccountResource::collection($records);
            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throw($e);
        }
    }

    public function store(AccountStoreRequest $request){
        DB::beginTransaction();
        try {
            $record = Account::create([
                'name'=>$request['name'],
                'project_id'=>$request['project_id'],
                'status'=>1
            ]);
            DB::commit();
            $resource = new AccountResource($record);
            return new SuccessResponse(['message' => 'Record saved', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }
    
    public function update($id,AccountStoreRequest $request){
        DB::beginTransaction();
        try {
            Account::find($id)->update([
                'name'=>$request['name'],
                'project_id'=>$request['project_id'],
            ]);
            $record = Account::find($id);
            DB::commit();
            $resource = new AccountResource($record);
            return new SuccessResponse(['message' => 'Record update', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }
    
    public function show($id){
        try {
            $record = Account::find($id);
            $resource = new AccountResource($record);
            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throw($e);
        }
    }
}
