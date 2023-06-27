<?php

namespace App\Http\Resources\Entry;

use App\Http\Resources\Header\HeaderResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EntryResource extends JsonResource
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
            'header' => $this->whenLoaded('header', function() {
                return new HeaderResource($this->header);
            }),
            'message' => $this->message,
            'user' => $this->whenLoaded('user', function() {
                return new UserResource($this->user);
            }),
            'likes_count' => $this->whenLoaded('likes', function() {
                return $this->likes->count();
            }),
        ];
    }
}
