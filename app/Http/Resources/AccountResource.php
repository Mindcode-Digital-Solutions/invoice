<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
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
            'name'=>$this->name,
            'project_name'=>$this->project->name ?? '',
            'status'=>$this->status,
            'total_credits'=>$this->accountsTransactions()->credit()->sum('amount'),
            'total_debits'=>$this->accountsTransactions()->debit()->sum('amount'),
        ];
    }
}
