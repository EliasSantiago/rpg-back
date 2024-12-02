<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTask extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title'         => 'required|string|min:3|max:255',
            'description'   => 'required|min:3|max:1000',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required'         => 'O campo título é obrigatório.',
            'title.min'              => 'O título deve ter pelo menos :min caracteres.',
            'title.max'              => 'O título não pode ter mais de :max caracteres.',
            'description.required'   => 'O campo descrição é obrigatório.',
            'description.min'        => 'A descrição deve ter pelo menos :min caracteres.',
            'description.max'        => 'A descrição não pode ter mais de :max caracteres.',
        ];
    }
}
