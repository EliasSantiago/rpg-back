<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUser extends FormRequest
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
            'email'    => ['required', 'string', 'email', 'min:3', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
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
            'email.required'                 => 'O campo email é obrigatório.',
            'email.email'                    => 'O email deve ser um endereço de email válido.',
            'password.required'              => 'O campo senha é obrigatório.',
            'password.min'                   => 'A senha deve ter pelo menos :min caracteres.',
            'password.max'                   => 'A senha não pode ter mais de :max caracteres.',
        ];
    }
}
