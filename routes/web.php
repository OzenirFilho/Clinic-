<?php

use App\Notifications\RecuperarSenhaMailable;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');
//Rotas de Display
Route::get('/login', 'LoginController@show')->name('login')->middleware('guest');
Route::get('/esqueci-minha-senha', 'M0001RecuperarSenhaController@index')->name('senha.recuperar');
Route::post('/esqueci-minha-senha', 'M0001RecuperarSenhaController@RecupSenhaReq')->name('senha.recuperar.request');
// Rotas de Ação
Route::post('/login', 'LoginController@autenticar')->name('login.do');

// Rotas Protegidas do sistema. Mapear todas aqui.
Route::middleware(['auth', 'usuario.inicializado', 'controle.sessao'])->group(function () {
    //Agrupar todas as rotas dentro do prefixo Painel
    Route::prefix('painel')->group(function(){
        Route::get('/', 'PainelController@index')->name('painel.index');
        Route::get('/auditor', 'Auditor\AuditorController@index')->name('auditor.index');
    });
});

//Rotas que gerariam erro de Too Many Redirects se esivessem cercadas pelo segundo middleware (usuario.inicializado)
Route::middleware(['auth'])->group(function () {
    //Agrupar todas as rotas administrativas com o prefixo "Painel"
    Route::prefix('painel')->group(function (){

        Route::get('usuario/inicializar', 'M0001InicializarController@index')->name('usuario.inicializar');
        Route::post('usuario/inicializar', 'M0001InicializarController@persisteAlteracaoDeSenha')->name('usuario.inicializar.do');

        Route::get('usuarios/gerenciar', function(){
            return "Rota de gestão de Usuários";
        })->name('usuarios.index');

        Route::post('logout', 'LoginController@logUserOut')->name('logout');
    });
});

