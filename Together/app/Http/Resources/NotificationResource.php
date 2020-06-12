<?php

namespace App\Http\Resources;
use App\Http\Resources\GroupResource;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Group;

class NotificationResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'img' => $this->img,
            'info' => $this->info,
            'group'=>new GroupResource(Group::find($this->group_id)),
        ];
    }
}
