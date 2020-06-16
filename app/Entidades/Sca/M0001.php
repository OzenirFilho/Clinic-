<?php

namespace App\Entidades\Sca;

use App\Entidades\Sca\M0002 as Perfil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Permissions\TemPermissoesTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
/**
 * Schema SCA tabela T001
 */
class M0001 extends Authenticatable
{
    use Notifiable;
    use TemPermissoesTrait;
    /**
     * Table Name
     */
    protected $table = 'sca.t0001';
    /**
     * Primary Key
     */
    protected $primaryKey = 'cd_usuario';
    /*
    * Disable Timestamps
    */
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nm_usuario',
        'nm_apelido',
        'dt_nasc_usu',
        'in_sexo_usu',
        'ds_email_usu',
        'nr_cel_usu',
        'cd_perm_usu',
        'dt_exp_snh_usu',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'ds_snh_usu'
    ];

    /**
     * Overriding laravel defaults
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->ds_snh_usu;
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        // Return email address only...
        return $this->ds_email_usu;
    }

    /**
     * Faz um hash da senha na hora de persistir ao banco
     */
    public function setDsSnhUsuAttribute($value){
        $this->attributes['ds_snh_usu'] = Hash::make($value);
    }
    /**
     * Obter os nomes de coluna de autenticação do banco
     *
     * @param [type] $request
     * @return array
     */
    public static function obterColunasDeAutenticacao($request){
        return [
            'nr_cpf' => $request['cpf'],
            'password'=>$request['password']
        ];
    }


    // Funções de obtenção de dados com nomes mais amigáveis

    public function nome(){
        return $this->nm_usuario;
    }
    public function primeiroNome(){
        $arrNome = explode(" ",$this->nm_usuario);
        return $arrNome[0];
    }

    public function save(array $options = []){
        parent::save($options);
        DB::statement("UPDATE t0001 set st_usuario = \"2\" WHERE cd_usuario = $this->cd_usuario");
    }
    /**
     * Retorna o tempo de sessão de um usuário com base nas regras estabelecidas pelo modelo M0002
     *
     * @return int
     */
    public function tempoSessao(){
        $lista = Perfil::getListaTemposExpiracaoSessao();
        $tempo = $this->perfis()->first()->nr_tempo_sessao;
        return $lista[$tempo];
    }

    public static function randomSenha($length) {
    $pool = array_merge(range(0,9), range('a', 'z'));
    $key = '';
    for($i=0; $i < $length; $i++) {
        $key .= $pool[mt_rand(0, count($pool) - 1)];
    }
    return $key;
    }

}
