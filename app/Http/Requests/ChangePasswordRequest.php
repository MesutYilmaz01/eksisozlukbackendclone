<?php

namespace App\Http\Requests;

use App\Rules\UnwantedAttribute;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'biography' => new UnwantedAttribute,
            'old_password' => 'required|min:6|max:10',
            'new_password' => 'required|confirmed|min:6|max:10'
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => 'Old password required.',
            'old_password.min' => 'Old password must be more than 6 character.',
            'old_password.max' => 'Old password must be less than 10 character.',
            'new_password.required' => 'New password required.',
            'new_password.min' => 'New password must be more than 6 character.',
            'new_password.max' => 'New password must be less than 10 character.',
            'new_password.confirmed' => 'New passwords are not match.',
        ];
    }
}
