<?php

use App\Entidades\Sca\M0001 as Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entidades\Sca\M0002 as Perfil;

class AmbienteTesteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        Perfil::firstOrCreate(
            [
                "nm_perm_usu"=>"Usuário",
                "st_perm_usu" => "1",
                "nr_tempo_sessao" => "3",
                "tp_expiracao_senha" => "2",
                "nr_max_login" => "3",
                'nm_apelido_perm' => 'usuario'
            ]
        ) or die("Houve um problema ao criar permissões");
        Perfil::firstOrCreate(
            [
                "nm_perm_usu"=>"Administrador",
                "st_perm_usu" => "1",
                "nr_tempo_sessao" => "3",
                "tp_expiracao_senha" => "2",
                "nr_max_login" => "3",
                'nm_apelido_perm' => 'admin'
            ]
        ) or die("Houve um problema ao criar permissões");

        factory(Usuario::class, 10)->create() or die("Houve um problema ao popular os usuários");
        $adm = factory(Usuario::class, 1)->create([
            "nm_usuario" => "Administrador",
            "cd_perm_usu" => Perfil::where('nm_perm_usu', 'Administrador')->first()->cd_perm_usu,
            "ds_snh_usu"=>"adm12345"
        ])->first() or die("Houve um problema ao criar o Administrador");

        DB::statement("UPDATE t0001 set st_usuario = \"0\" WHERE cd_usuario = $adm->cd_usuario") or die("Erro ao mudar o status do Administrador");
    }
}
