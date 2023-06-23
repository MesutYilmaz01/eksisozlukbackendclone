<?php

namespace App\Http\Resources\Header;

use App\Http\Resources\Entry\EntryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class HeaderResource extends JsonResource
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
            'header' => $this->header,
            'slug' => $this->slug,
            'entries' => $this->whenLoaded('entries', function() {
                return EntryResource::collection($this->entries);
            })
        ];
    }
}
