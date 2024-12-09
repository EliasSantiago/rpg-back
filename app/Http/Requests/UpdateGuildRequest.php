<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Guild;
use App\Models\Guilds;

class UpdateGuildRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // A autorização pode ser ajustada conforme necessário
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $guildId = $this->route('guildId');

        $rules = [
            'name' => 'nullable|string|max:255|unique:guilds,name,' . $guildId,
            'description' => 'nullable|string|max:255',
            'max_players' => 'required|integer|min:1',
            'leader_id' => 'required|exists:users,id',
        ];

        $rules['max_players'] = function ($attribute, $value, $fail) use ($guildId) {
            $guild = Guilds::findOrFail($guildId);
            $currentPlayerCount = $guild->users()->count();

            if ($value < $currentPlayerCount) {
                $numToRemove = $currentPlayerCount - $value;
                $name = $numToRemove > 1 ? "jogadores" : "jogador";
                return $fail("Remova $numToRemove $name antes de reduzir a quantidade máxima de jogadores desta guilda.");
            }
        };

        return $rules;
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => 'Já existe uma guilda com esse nome.',
            'max_players.required' => 'O número máximo de jogadores é obrigatório.',
            'max_players.integer' => 'O número máximo de jogadores deve ser um inteiro.',
            'leader_id.exists' => 'O líder selecionado não existe.',
        ];
    }
}
