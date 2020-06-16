<?php

namespace App\Http\Controllers;

use App\Entidades\Sca\M0001;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\M0001InicializaSenhaRequest;

/**
 * O usuário só pode ter acesso aos recursos do sistema caso este tenha definido uma senha
 * de acesso.
 */
class M0001InicializarController extends Controller
{
    public function index(){
        return view('auth.inicializar');
    }
    /**
     * Define a senha do usuário no BD
     */
    public function persisteAlteracaoDeSenha(M0001InicializaSenhaRequest $request){
        $usuario = Auth::user();
        $usuario->ds_snh_usu=$request->password;
        $usuario->save();
        DB::statement("UPDATE sca.t0001 set st_usuario = \"0\" WHERE cd_usuario = ".Auth::user()->cd_usuario);
        return redirect()->route('painel.index')->with('success', 'Você alterou sua senha com sucesso.');
    }
}
