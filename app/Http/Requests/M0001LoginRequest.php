<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * The login validation request
 */
class M0001LoginRequest extends FormRequest
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

    protected $mensagens = [
        'required' => 'Você não pode deixar campos obrigatórios em branco.',
        'regex' => 'A senha deve possuir no mínimo 8 caracteres. Além disso deve ter ao menos uma letra e um número.',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cpf' => 'required|digits:11|numeric',
            // TODO: 8 Chars
            'password' => 'required|min:8'
        ];
    }
    public function messages()
    {
        return $this->mensagens;
    }
}
