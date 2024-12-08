<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemoveUserFromGuildRequest extends FormRequest
{
    /**
     * Determine se o usuário está autorizado a fazer essa requisição.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return [];
    }

    public function getUserIdFromRoute()
    {
        return $this->route('userId');
    }
}
