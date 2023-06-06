<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'token_type' => 'Bearer',
            'token' => $this->resource["token"]
        ];
    }
}
