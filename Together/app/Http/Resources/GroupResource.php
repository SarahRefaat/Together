<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'group_id' => $this->id,
            'address'=>$this->address,
            'max_member_number'=>$this->max_member_number,
            'name' => $this->name,
            'description' => $this->description,
            'current_number_of_members'=>$this->current_number_of_members,
            'status'=>$this->status,
            'level'=>$this->level,
            'photo' => $this->photo,
            'interest_id'=>$this->interest_id,
            'duration'=>$this->duration,
            'id'=>$this->admin_id
        ];
    }
}
