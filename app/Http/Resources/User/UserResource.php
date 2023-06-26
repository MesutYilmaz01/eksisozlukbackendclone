<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Entry\EntryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'bioghraphy' => $this->biography,
            'created_at' => $this->created_at,
            'followers_count' => $this->whenLoaded('followers', function() {
                return $this->followers->count();
            }),
            'followed_count' => $this->whenLoaded('followed', function() {
                return $this->followed->count();
            }),
            'entries' => $this->whenLoaded('entries', function() {
                return EntryResource::collection($this->entries);
            })
        ];
    }
}
