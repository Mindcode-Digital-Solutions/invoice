<?php

namespace App\Http\Controllers;

use App\Classes\ApiCatchErrors;
use App\Classes\DatePicker;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\Common\SuccessResponse;
use App\Http\Resources\TransactionResource;
use App\Models\AccountsTransaction;
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
            if(!empty($request['accounts'])){
                $query = $query->whereHas('accountsTransactions',function($q) use($request){
                    $q->whereIn('account_id',$request['accounts']);
                });
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
            $amount = $this->calculateTotalAmount($request['credit_accounts']);
            $transaction = Transaction::create([
                'amount'=>$amount,
                'date'=> DatePicker::format($request['date']),
                'type'=> $request['type'],
                'ref'=> $request['ref'],
                'description'=> $request['description'],
                'status'=>1
            ]);
            $this->storeOrUpdateAccountsTransactions($transaction->id,$request['credit_accounts'],$request['debit_accounts']);
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
            $amount = $this->calculateTotalAmount($request['credit_accounts']);
            Transaction::find($id)->update([
                'amount'=>$amount,
                'date'=> DatePicker::format($request['date']),
                'type'=> $request['type'],
                'ref'=> $request['ref'],
                'description'=> $request['description']
            ]);
            $transaction = Transaction::find($id);
            $this->storeOrUpdateAccountsTransactions($transaction->id,$request['credit_accounts'],$request['debit_accounts']);
            DB::commit();
            $resource = new TransactionResource($transaction);
            return new SuccessResponse(['message' => 'Transaction update', 'data' => $resource]);
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }

    private function calculateTotalAmount($accounts){
        $amount = 0;
        foreach($accounts as $account){
            $amount += $account['amount'];
        }
        return $amount;
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

    private function storeOrUpdateAccountsTransactions($transactionId,$credits,$debits){
        $creditIdArray   = [];
        AccountsTransaction::where('transaction_id',$transactionId)->whereNotIn('account_id',$creditIdArray)->where('type','credit')->delete();
        foreach($credits as $account){
            AccountsTransaction::updateOrCreate([
                'transaction_id'=>$transactionId,
                'account_id'=>$account['account_id'],             
                'type'=>'credit'
            ],[
                'amount'=>$account['amount'],
            ]);
        }

        $debitIdArray   = [];
        AccountsTransaction::where('transaction_id',$transactionId)->whereNotIn('account_id',$debitIdArray)->where('type','debit')->delete();
        foreach($debits as $account){
             AccountsTransaction::updateOrCreate([
                'transaction_id'=>$transactionId,
                'account_id'=>$account['account_id'],                
                'type'=>'debit',
            ],[
                'amount'=>$account['amount'],
            ]);
        }
    }
}
