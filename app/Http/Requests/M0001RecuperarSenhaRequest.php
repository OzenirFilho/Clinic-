<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class M0001RecuperarSenhaRequest extends FormRequest
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
        if(env('DISABLE_CAPTCHA_IN_DEV') && env('APP_DEBUG')){
            return [
                'email' => 'required|email',
                // 'g-recaptcha-response' => 'required|captcha'
            ];
        }
        return [
            'email' => 'required|email',
            'g-recaptcha-response' => 'required|captcha'
        ];
    }
    public function messages(){
        return [
            'required' => 'Por favor, responda a verificação anti-robô',
            'captcha' => 'Erro na Validação do Captcha.',
        ];
    }
}
