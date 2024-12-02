<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUser extends FormRequest
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
    public function rules()
    {
        return [
            'name'                  => ['required', 'string', 'min:3', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'unique:users,email', 'min:3', 'max:255'],
            'password'              => ['required', 'string', 'min:6', 'max:255'],
            'password_confirmation' => ['required', 'string', 'min:6', 'same:password', 'max:255'],
            'rpg_class_id'          => ['required', 'exists:rpg_classes,id'], // Validação para o campo rpg_class_id
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
            'name.required'                  => 'O campo nome é obrigatório.',
            'name.min'                       => 'O nome deve ter pelo menos :min caracteres.',
            'name.max'                       => 'O nome não pode ter mais de :max caracteres.',
            'email.required'                 => 'O campo email é obrigatório.',
            'email.email'                    => 'O email deve ser um endereço de email válido.',
            'email.unique'                   => 'Este email já está em uso.',
            'password.required'              => 'O campo senha é obrigatório.',
            'password.min'                   => 'A senha deve ter pelo menos :min caracteres.',
            'password.max'                   => 'A senha não pode ter mais de :max caracteres.',
            'password_confirmation.required' => 'A confirmação de senha é obrigatória.',
            'password_confirmation.min'      => 'A confirmação de senha deve ter pelo menos :min caracteres.',
            'password_confirmation.same'     => 'A confirmação de senha não coincide com a senha.',
            'password_confirmation.max'      => 'A confirmação de senha não pode ter mais de :max caracteres.',
            'rpg_class_id.required'          => 'A classe RPG é obrigatória.',
            'rpg_class_id.exists'            => 'A classe RPG selecionada não existe.',
        ];
    }
}
