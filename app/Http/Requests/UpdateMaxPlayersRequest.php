<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaxPlayersRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'max_players' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'max_players.required' => 'O campo limite de jogadores é obrigatório.',
            'max_players.integer'  => 'O limite de jogadores deve ser um número inteiro.',
            'max_players.min'      => 'O limite de jogadores deve ser no mínimo 1.',
        ];
    }
}
