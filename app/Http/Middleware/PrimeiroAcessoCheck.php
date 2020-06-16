<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
/**
 * O usuário só pode acessar os recursos do sistema caso o status seja diferente de 2 (primeiro acesso)
 */
class PrimeiroAcessoCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if(Auth::user()->st_usuario=="2"){
            return redirect()->route('usuario.inicializar');
        }
        else {
            return $next($request);
        }
    }
}
