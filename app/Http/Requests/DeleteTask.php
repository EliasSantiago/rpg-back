<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteTask extends FormRequest
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
            'id' => ['required', 'integer', Rule::exists('tasks')->where(function ($query) {
                $id = intval($this->route('id'));
                $query->where('id', $id);
            })],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'O campo ID é obrigatório.',
            'id.integer' => 'O campo ID deve ser um número inteiro.',
            'id.exists' => 'O ID fornecido não corresponde a uma tarefa existente.',
        ];
    }
}
