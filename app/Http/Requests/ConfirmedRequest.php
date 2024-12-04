<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// app/Http/Requests/ConfirmedRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmedRequest extends FormRequest
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
            'confirmed' => 'required|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'confirmed.required' => 'O campo de confirmação é obrigatório.',
            'confirmed.boolean'  => 'O campo de confirmação deve ser verdadeiro ou falso.',
        ];
    }
}
