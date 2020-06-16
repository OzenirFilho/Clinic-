<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ControleSessao
{
    protected $session;
    protected $timeout;

    public function __construct(Store $session){
        $this->session = $session;
        $this->timeout = Auth::user()->tempoSessao();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $sessionVar = 'ultimaAtividade';
        $isLoggedIn = Auth::check();
        if (!session($sessionVar)){
            $this->session->put($sessionVar, time());
        }
        elseif (time() - $this->session->get($sessionVar) > $this->timeout) {
            $request->session()->forget($sessionVar);
            auth()->logout();
            return redirect()->route('login')->with('warning', 'Sessão desativada por inatividade.');
        }
        $isLoggedIn ? $this->session->put($sessionVar, time()) : $this->session->forget('ultimaAtividade');

        return $next($request);
    }
}
