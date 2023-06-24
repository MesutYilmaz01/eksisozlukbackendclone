<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HeaderListRequest extends FormRequest
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
            'id' => 'integer',
            'header' => 'string',
            'slug' => 'string',
            'created_at' => 'date|date_format:Y-m-d',
            'starts_with' => 'string',
            'with_pagination' => 'boolean',
            'page' => 'integer|min:1',
            'per_page' => 'integer|max:100'
        ];
    }

    protected function prepareForValidation()
    {
        if (!$this->has('with_pagination')) {
            $this->merge(['with_pagination' => true]);
        }
        if (!$this->has('page')) {
            $this->merge(['page' => 1]);
        }
        if (!$this->has('per_page')) {
            $this->merge(['per_page' => 50]);
        }
    }
}
