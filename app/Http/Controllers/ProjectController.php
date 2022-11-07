<?php

namespace App\Http\Controllers;

use App\Classes\ApiCatchErrors;
use App\Classes\DatePicker;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Resources\Common\SuccessResponse;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index(){
        try {
            $records = Project::paginate();
            $resource = ProjectResource::collection($records);
            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throw($e);
        }
    }

    public function store(ProjectStoreRequest $request){
        DB::beginTransaction();
        try {
            $record = Project::create([
                'name'=>$request['name'],
                'total'=>$request['total'],
                'start_date'=>DatePicker::format($request['start_date']),
                'due_date'=>DatePicker::format($request['due_date']),
                'status'=>1
            ]);
            DB::commit();
            $resource = new ProjectResource($record);
            return new SuccessResponse(['message' => 'Record saved', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }
    
    public function update($id,ProjectStoreRequest $request){
        DB::beginTransaction();
        try {
            Project::find($id)->update([
                'name'=>$request['name'],
                'total'=>$request['total'],
                'start_date'=>DatePicker::format($request['start_date']),
                'due_date'=>DatePicker::format($request['due_date']),
                'status'=>1
            ]);
            $record = Project::find($id);
            DB::commit();
            $resource = new ProjectResource($record);
            return new SuccessResponse(['message' => 'Record update', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }
    
    public function show($id){
        try {
            $record = Project::find($id);
            $resource = new ProjectResource($record);
            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throw($e);
        }
    }
}
