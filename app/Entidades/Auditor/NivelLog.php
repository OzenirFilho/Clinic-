<?php

namespace App\Entidades\Auditor;

use Illuminate\Database\Eloquent\Model;
/**
 * Classe principal de Auditoria
 */
class NivelLog extends Model {

    public function niveis(){
        return [
            "1" => [
                "nome" => "Debug",
                "canal" => ["phpstdout"],
            ],
            "2" => [
                "nome" => "Info",
                "canal" => ["phpstdout"],
            ],
            "3" => [
                "nome" => "Nota",
                "canal" => ["phpstdout"],
            ],
            "4" => [
                "nome" => "Aviso",
                "canal" => ["phpstdout"],
            ],
            "5" => [
                "nome" => "Erro",
                "canal" => ["phpstdout"],
            ],
            "6" => [
                "nome" => "Critico",
                "canal" => ["phpstdout"],
            ],
            "7" => [
                "nome" => "Alerta",
                "canal" => ["phpstdout"],
            ],
            "8" => [
                "nome" => "EMERGENCIA",
                "canal" => ["phpstdout"],
            ],
        ];
    }

}
