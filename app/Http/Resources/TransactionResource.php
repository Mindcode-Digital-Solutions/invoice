<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'amount'=>$this->amount,
            'ref'=>$this->ref,
            'type'=>$this->type,
            'date'=>$this->date,
            'description'=>$this->description,
            'credit_accounts'=> AccountTransactionResource::collection($this->accountsTransactions()->credit()->get()),
            'debit_accounts'=> AccountTransactionResource::collection($this->accountsTransactions()->debit()->get())
        ];
    }
}
