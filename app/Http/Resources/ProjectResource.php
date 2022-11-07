<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'total'=>$this->total,
            'formatted_total'=>number_format($this->total,2),
            'start_date'=>$this->start_date,
            'due_date'=>$this->due_date,
            'status'=>$this->status
        ];
    }
}
