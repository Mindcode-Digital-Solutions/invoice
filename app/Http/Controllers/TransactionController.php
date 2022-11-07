<?php

namespace App\Http\Controllers;

use App\Classes\ApiCatchErrors;
use App\Classes\DatePicker;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\Common\SuccessResponse;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request){
        try {
            $query = Transaction::query();
            if($request['from_date'] != null && $request['to_date'] != null ){
                $query = $query->whereBetween('date',[ DatePicker::format($request['from_date']),DatePicker::format($request['to_date'])]);
            }
            if($request['ref'] != null ){
                $query = $query->where('ref','like','%'.$request['ref'].'%');
            }
            if($request['description'] != null ){
                $query = $query->where('ref','like','%'.$request['description'].'%');
            }
            if($request['type'] != null ){
                $query = $query->where('type',$request['type']);
            }
            if($request['account_id'] != null ){
                $query = $query->where('account_id',$request['account_id']);
            }
            if($request['person_id'] != null ){
                $query = $query->where('person_id',$request['person_id']);
            }
            $transactions = $query->paginate($request['page'] ?? 20);
            $resource = TransactionResource::collection($transactions);
            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throw($e);
        }
    }

    public function store(TransactionRequest $request){
        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'account_id'=> $request['account_id'],
                'person_id'=> $request['person_id'],
                'category_id'=> $request['category_id'],
                'project_id'=> $request['project_id'],
                'amount'=>$request['amount'],
                'date'=> DatePicker::format($request['date']),
                'type'=> $request['type'],
                'ref'=> $request['ref'],
                'description'=> $request['description'],
                'status'=>1
            ]);
            DB::commit();
            $resource = new TransactionResource($transaction);
            return new SuccessResponse(['message' => 'Transaction saved', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }
    
    public function update($id,TransactionRequest $request){
        DB::beginTransaction();
        try {
            Transaction::find($id)->update([
                'account_id'=> $request['account_id'],
                'person_id'=> $request['person_id'],
                'category_id'=> $request['category_id'],
                'project_id'=> $request['project_id'],
                'amount'=>$request['amount'],
                'date'=> DatePicker::format($request['date']),
                'type'=> $request['type'],
                'ref'=> $request['ref'],
                'description'=> $request['description'],
            ]);
            $transaction = Transaction::find($id);
            DB::commit();
            $resource = new TransactionResource($transaction);
            return new SuccessResponse(['message' => 'Transaction update', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }
    
    public function show($id){
        try {
            $transaction = Transaction::find($id);
            $resource = new TransactionResource($transaction);
            return new SuccessResponse(['data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::throw($e);
        }
    }

}
