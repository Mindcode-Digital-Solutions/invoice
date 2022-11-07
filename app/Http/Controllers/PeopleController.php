<?php

namespace App\Http\Controllers;

use App\Classes\ApiCatchErrors;
use App\Http\Requests\PeopleStoreRequest;
use App\Http\Resources\Common\SuccessResponse;
use App\Http\Resources\PeopleResource;
use App\Models\People;
use Exception;
use Illuminate\Support\Facades\DB;

class PeopleController extends Controller
{
    public function index(){
        try {
            $records = People::paginate();
            $resource = PeopleResource::collection($records);
            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throw($e);
        }
    }

    public function store(PeopleStoreRequest $request){
        DB::beginTransaction();
        try {
            $record = People::create([
                'name'=>$request['name'],
                'nick_name'=>$request['nick_name'],
                'status'=>1
            ]);
            DB::commit();
            $resource = new PeopleResource($record);
            return new SuccessResponse(['message' => 'Record saved', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }
    
    public function update($id,PeopleStoreRequest $request){
        DB::beginTransaction();
        try {
            People::find($id)->update([
                'name'=>$request['name'],
                'nick_name'=>$request['nick_name'],
            ]);
            $record = People::find($id);
            DB::commit();
            $resource = new PeopleResource($record);
            return new SuccessResponse(['message' => 'Record update', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }
    
    public function show($id){
        try {
            $record = People::find($id);
            $resource = new PeopleResource($record);
            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throw($e);
        }
    }
}
