<?php
namespace App\Entidades\Sca;

use Illuminate\Database\Eloquent\Model;

//Perfis de acesso
class M0090 extends Model {
    /**
     * Table Name
     */
    protected $table = 'sca.t0090';
    /**
     * Primary Key
     */
    protected $primaryKey = 'cd_login_erro';
    /*
    * Disable Timestamps
    */
    public $timestamps = false;

    protected $fillable = [
        "dt_login_erro",
        "hr_login_erro",
        "nr_ip_login_erro",
        "ds_maq_login_erro",
        "cd_usuario_blq",
        "dt_login_liber",
        "hr_login_liber",
        "cd_usuario_lib",
    ];
}
