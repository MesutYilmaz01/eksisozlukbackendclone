<?php

namespace App\Http\Resources\Header;

use Illuminate\Http\Resources\Json\JsonResource;

class HeaderListResource extends JsonResource
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
            'entry_count' => $this->whenLoaded('entries', function() {
                return $this->entries->count();
            })
        ];
    }
}
