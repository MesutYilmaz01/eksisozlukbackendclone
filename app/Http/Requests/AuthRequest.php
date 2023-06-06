<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'email' => 'required|email|max:50|exists:users,email',
            'password' =>'required|min:6|max:10'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Please type a valid email.',
            'email.exists:users,email' => 'There is not registered user with this email.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password should be minimum 6 character.',
            'password.max' => 'Password should be maximum 10 character.'
        ];
    }
}
