<?php

namespace App\Http\Requests;

use App\Rules\UnwantedAttribute;
use Illuminate\Foundation\Http\FormRequest;

class ChangeBiographyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_type' => new UnwantedAttribute,
            'username' => [new UnwantedAttribute],
            'email' => new UnwantedAttribute,
            'avatar' => new UnwantedAttribute,
            'biography' => 'min:3|max:50',
            'paassword' => new UnwantedAttribute,
            "birthday" => new UnwantedAttribute,
            "gender" => new UnwantedAttribute
        ];
    }
}
