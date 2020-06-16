<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Valida o request para inicialização de senha
 */
class M0001InicializaSenhaRequest extends FormRequest
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
        'confirmed' => 'As senhas digitadas não conferem.'
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'confirmed|required|min:8|regex:/(?=.{8,})(?=.*[a-z])(?=.*[0-9])\w+/',
        ];
    }

    public function messages()
    {
        return $this->mensagens;
    }
}
