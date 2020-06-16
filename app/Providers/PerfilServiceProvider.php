<?php
namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class PerfilServiceProvider extends ServiceProvider {

    public function boot(){
        Blade::directive('perfil', function ($perfil) {
            return "<?php if (Auth::user()->temPerfil(".$perfil.")): ?>";
        });
        Blade::directive('endperfil', function ($perfil) {
            return "<?php endif; ?>";
        });
    }
}
