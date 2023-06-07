<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            //'user_type' => 
            //'avatar' =>
            //'biography' => 
            'username' => 'required|unique:users,username|min:3|max:25',
            'email' => 'required|unique:users,email|email|max:50',
            'password' => 'required|min:6|max:10',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Username is required.',
            'username.unique:users,username' => 'This username has already taken.',
            'username.min' => 'Username must not be less than 3 character.',
            'username.max' => 'Username must not be more than 25 character.',
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
