<?php
namespace App\Permissions;

use App\Role;
use App\Permission;
use App\Entidades\Sca\M0002 as Perfil;

trait TemPermissoesTrait {

public function perfis() {
    return $this->hasMany(Perfil::class, 'cd_perm_usu', 'cd_perm_usu');
}
public function temPerfil(...$perfis)
    {
        foreach ($perfis as $perfil) {
            if ($this->perfis->contains('nm_apelido_perm',$perfil)) {
                return true;
            }
        }
        return false;
    }
}
