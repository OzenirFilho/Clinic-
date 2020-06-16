<?php
namespace App\Entidades\Sca;

use Illuminate\Database\Eloquent\Model;

//Perfis de acesso
class M0091 extends Model {
    /**
     * Table Name
     */
    protected $table = 'sca.t0091';
    /**
     * Primary Key
     */
    protected $primaryKey = 'id_log_acesso';
    /*
    * Disable Timestamps
    */
    public $timestamps = false;

    protected $fillable = [
        "id_sessao",
        "dt_acesso",
        "hr_acesso",
        "cd_usuario",
        "nm_usuario",
        "nr_ip_acesso",
        "ds_nome_maquina",
        "ds_browser",
        "ds_sis_oper",
        "ds_res_tela",
    ];
}
