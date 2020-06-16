<?php
namespace App\Entidades\Sca;

use Illuminate\Database\Eloquent\Model;

//Perfis de acesso
class M0002 extends Model {
    /**
     * Table Name
     */
    protected $table = 'sca.t0002';
    /**
     * Primary Key
     */
    protected $primaryKey = 'cd_perm_usu';
    /*
    * Disable Timestamps
    */
    public $timestamps = false;

    /**
     * Um array com os tempos de expiração
     *
     * @return array
     */
    public static function getListaTemposExpiracaoSessao(){
        return [
            0 => 30,
            1 => 60,
            2 => 300,
            // Um ano. O máximo de expiração de um cabeçalho.
            3 => 31557600
        ];
    }
}
