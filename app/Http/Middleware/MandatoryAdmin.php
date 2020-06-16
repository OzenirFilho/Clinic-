<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
/**
 * Classes protegidas por este middleware só podem ser acessadas por Admins
 */
class MandatoryAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()->temPerfil('admin')){
            return $next($request);
        }
        //TODO: Logar este evento
        return redirect()->route('painel.index')->with('warning', 'Você não tem permissão para acessar este recurso. Esta ação foi registrada no log de auditoria.');
    }
}
