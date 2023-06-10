<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
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
            'username' => 'required|exists:users,username',
            'message' => 'required|min:1'
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Username is required.',
            'username.exists:users,username' => 'There is not any user with this username.',
            'message.required' => 'Message is required',
        ];
    }
}
