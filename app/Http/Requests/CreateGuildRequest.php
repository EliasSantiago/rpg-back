<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGuildRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:guilds,name',
            'description' => 'nullable|string|max:255',
            'max_players' => 'required|integer|min:1',
            'leader_id' => 'required|exists:users,id',
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'O nome da guilda é obrigatório.',
            'name.unique' => 'Já existe uma guilda com esse nome.',
            'max_players.required' => 'O número máximo de jogadores é obrigatório.',
            'max_players.integer' => 'O número máximo de jogadores deve ser um inteiro.',
            'leader_id.exists' => 'O líder selecionado não existe.',
        ];
    }
}
