<?php

namespace App\Http\Controllers;

use App\Classes\ApiCatchErrors;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\Common\SuccessResponse;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(){
        try {
            $categories = Category::paginate();
            $resource = CategoryResource::collection($categories);
            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throw($e);
        }
    }

    public function store(CategoryStoreRequest $request){
        DB::beginTransaction();
        try {
            $category = Category::create([
                'name'=>$request['name'],
                'status'=>1
            ]);
            DB::commit();
            $resource = new CategoryResource($category);
            return new SuccessResponse(['message' => 'Category saved', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }
    
    public function update($id,CategoryStoreRequest $request){
        DB::beginTransaction();
        try {
            Category::find($id)->update([
                'name'=>$request['name'],
            ]);
            $category = Category::find($id);
            DB::commit();
            $resource = new CategoryResource($category);
            return new SuccessResponse(['message' => 'Category update', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }
    
    public function show($id){
        try {
            $category = Category::find($id);
            $resource = new CategoryResource($category);
            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throw($e);
        }
    }
}
