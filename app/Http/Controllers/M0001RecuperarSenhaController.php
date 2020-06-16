<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Entidades\Sca\M0001 as Usuario;
use App\Notifications\RecuperarSenhaMailable;
use App\Http\Requests\M0001RecuperarSenhaRequest;
use App\Http\Requests\M0001InicializaSenhaRequest;

/**
 * Controller de Recuperação de Senhas
 */
class M0001RecuperarSenhaController extends Controller
{
    /**
     * A visão de recuperação
     *
     * @return view
     */
    public function index(){
        return view('auth.recuperar');
    }
    /**
     * Request de Nova Senha
     *
     * @return void
     */
    public function RecupSenhaReq(M0001RecuperarSenhaRequest $request){
        $u = Usuario::where('ds_email_usu', $request->email)->first();

        $msg = 'Enviamos um e-mail com uma senha temporária.<br>Por favor, verifique sua caixa de entrada ou spam.';

        if($u){
            $senha = Usuario::randomSenha(8);
            $hashSenha=Hash::make($senha);
            // Resetar a senha do usuário
            DB::statement("UPDATE t0001 set st_usuario = \"2\", ds_snh_usu=\"$hashSenha\" WHERE cd_usuario = $u->cd_usuario") || abort(500, 'Erro interno no servidor');
            // Queue o e-mail e retornar mensagem
            $u->notify(new RecuperarSenhaMailable([
                'usuario'=>$u->primeiroNome(),
                'senha' => $senha,
            ]));
        }
        else {
            // E-mail não existe na base
            // Disparar um Log. Mas retornar a mesma mensagem.
        }
        return redirect()->route('login')->with('info', $msg);
    }
}
