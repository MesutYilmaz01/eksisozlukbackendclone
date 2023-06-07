<?php

namespace App\Http\Requests;

use App\Rules\UnwantedAttribute;
use Illuminate\Foundation\Http\FormRequest;

class ChangeEmailRequest extends FormRequest
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
            'avatar' => new UnwantedAttribute,
            'biography' => new UnwantedAttribute,
            'birthday' => new UnwantedAttribute,
            'gender' => new UnwantedAttribute,
            'email' => 'required|unique:users,email|email|max:50',
            'password' => 'required|min:6|max:10',
        ];
    }
    
    public function messages()
    {
        return [
            'email.required' => 'Email is required.',
            'email.unique:users,email' => 'This email has already registered.',
            'email.email' => 'Email is invalid',
            'email.max' => 'Email must not be more than 50 character.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must not be less than 6 character.',
            'password.max' => 'Password must not be more than 10 character.',
        ];
    }
}
