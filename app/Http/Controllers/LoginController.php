<?php

namespace App\Http\Controllers;

use App\Helpers\Auditoria;
use Illuminate\Http\Request;
use Sinergi\BrowserDetector\Os;
use Illuminate\Support\Facades\Auth;
use Sinergi\BrowserDetector\Browser;
use App\Entidades\Sca\M0001 as Usuario;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\M0001LoginRequest;
/**
 * Controlador de Login
 * Responsável por todas as funcionalidades de login, bem como as devidas ações de auditoria relativas às rotinas de login.
 */
class LoginController extends Controller
{
    /**
     * A tela de login
     *
     * @return view
     */
    public function show(){
        if (Auth::check()) {
            return redirect('/');
        }
        Session::forget('ultimaAtividade');
        return view('auth.login')->with('status', false);
    }
    /**
     * Efetuar a autenticação do usuário manualmente.
     *
     * @param M0001LoginRequest $req
     * @return void
     */
    public function autenticar(M0001LoginRequest $req){
        //Request é válido
        $auth = Usuario::obterColunasDeAutenticacao($req->except('_token', 'srstn'));
        if (Auth::attempt($auth)){
            $browser = new Browser();
            $os = new Os();
            //Incluir a atividade de login no log
            Auditoria::atividade("LogSucessoLogin", "4", "Login feito com sucesso. CPF: ".$req->cpf, [
                "id_sessao"=> session()->getId(),
                "dt_acesso" => now()->format('Ymd'),
                "hr_acesso" => now()->format('His'),
                "cd_usuario" => auth()->user()->cd_usuario,
                "nm_usuario" => auth()->user()->nome(),
                "nr_ip_acesso" => Auditoria::getUserIP(),
                "ds_nome_maquina" => "Sucesso no login. CPF: ".$req->cpf,
                "ds_browser" => $browser->getName(),
                "ds_sis_oper"=> $os->getName(),
                "ds_res_tela" => $req->srstn,
            ]);
            return redirect()->route('painel.index');
        }
        else {
            //Incluir o erro de tentativa de login no log.
            Auditoria::atividade("LogFalhaLogin", "4", "Falha em tentativa de login. CPF: ".$req->cpf.PHP_EOL.$this->informaSeCPFExisteNaBase($req->cpf), [
                "dt_login_erro" => now()->format('Ymd'),
                "hr_login_erro" => now()->format('His'),
                "nr_ip_login_erro" => Auditoria::getUserIP(),
                "ds_maq_login_erro" => "Falha em tentativa de login. CPF: ".$req->cpf,
            ]);
            return redirect()->back()->withErrors(['login'=>'CPF e/ou senha inválidos.']);
        }
    }
    /**
     * Efetuar o logout do usuário
     *
     * @param request $req
     * @return void
     */
    public function logUserOut(request $req){
        if(!(Auth::check())){
            //TODO(?) fim de sessão também deve ser logado?
            Session::forget('ultimaAtividade');
            return redirect()->route('login')->with('warning', 'Você foi desconectado por tempo ocioso de sessão');
        }
        $browser = new Browser();
        $os = new Os();
        //Obter o número de CPF do usuário
        $cpf = Auth()->user()->nr_cpf;
            //Incluir a atividade de logout no log
            Auditoria::atividade("LogLogout", "4", "Usuário fez logout. CPF: ".$cpf, [
                "id_sessao"=> session()->getId(),
                "dt_acesso" => now()->format('Ymd'),
                "hr_acesso" => now()->format('His'),
                "cd_usuario" => auth()->user()->cd_usuario,
                "nm_usuario" => auth()->user()->nome(),
                "nr_ip_acesso" => Auditoria::getUserIP(),
                "ds_nome_maquina" => "Sucesso no login. CPF: ".$cpf,
                "ds_browser" => $browser->getName(),
                "ds_sis_oper"=> $os->getName(),
                "ds_res_tela" => $req->srstn,
            ]);
            Session::flush();
            Auth::logout();
            return redirect()->route('login')->with('success', 'Obrigado por usar nossos serviços. Volte sempre!');
    }
    /**
     * Gera uma mensagem informando se existe ou não um CPF na base
     * @return string
     */
    protected function informaSeCPFExisteNaBase($cpf){
        $c = Usuario::where('nr_cpf',$cpf)->count();
        return ($c==1)? 'CPF existe na base de dados.' : '*Alerta - Este CPF não existe na base de dados*';
    }
}
