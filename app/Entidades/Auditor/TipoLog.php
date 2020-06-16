<?php

namespace App\Entidades\Auditor;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Auditoria;

class TipoLog extends Model {
/**===================================
 * Tipos de logs
 * As funções abaixo definem os tipos de logs que são possíveis, além de suas características
 * =====================================
 */


    /**
     * Falha de Login
     *
     * @return array
     */
    public static function LogFalhaLogin(){
        return [
            //Exemplo
            "classe" => \App\Entidades\Sca\M0090::class,
            "nome" => "Log - Falha de Login",
            //Dados esperados pelo log. Vêm do banco
            "dados" => [
                "dt_login_erro" => "string:8:data",
                "hr_login_erro" => "string:6:hora",
                "nr_ip_login_erro" => "ip",
                "ds_maq_login_erro" => "string",
                "nr_nivel_log" => "int",
                //TODO: Ignore second verification block if first is Int.
                "cd_usuario_blq" => "int",
                //The check above will verify if it's a valid integer. So there's no need to check length or other parameter like that.
                "dt_login_liber" => "string:8:data",
                "hr_login_liber" => "string:6:hora",
                "cd_usuario_lib" => "int",
                // "" => ":",
            ],
            //Estes campos não furam a validação
            "camposOpcionais" => [
                "cd_usuario_blq",
                "cd_usuario_lib",
                "dt_login_liber",
                "hr_login_liber",
            ],
            //Nível mínimo de notificação (e-mail, Telegram, etc.)
            "notificarAcimaDe" => 4
        ];
    }
    public static function LogSucessoLogin(){
        return [
            //Exemplo
            "classe" => \App\Entidades\Sca\M0091::class,
            "nome" => "Log - Sucesso no Login",
            //Dados esperados pelo log. Vêm do banco
            "dados" => [
                "id_sessao" => "string",
                "dt_acesso" => "string:8:data",
                "nr_nivel_log" => "int",
                "hr_acesso" => "string:6:hora",
                "cd_usuario" => "int",
                "nm_usuario" => "string",
                "nr_ip_acesso" => "ip",
                "ds_nome_maquina" => "string",
                "ds_browser" => "string",
                "ds_sis_oper" => "string",
                "ds_res_tela" => "string",
                // "" => ":",
            ],
            //Estes campos não furam a validação
            "camposOpcionais" => [

            ],
            //Nível mínimo de notificação (e-mail, Telegram, etc.)
            "notificarAcimaDe" => 4
        ];
    }
    public static function LogLogout(){
        return [
            //Exemplo
            "classe" => \App\Entidades\Sca\M0091::class,
            "nome" => "Log - Logout de usuário",
            //Dados esperados pelo log. Vêm do banco
            "dados" => [
                "id_sessao" => "string",
                "dt_acesso" => "string:8:data",
                "nr_nivel_log" => "int",
                "hr_acesso" => "string:6:hora",
                "cd_usuario" => "int",
                "nm_usuario" => "string",
                "nr_ip_acesso" => "ip",
                "ds_nome_maquina" => "string",
                "ds_browser" => "string",
                "ds_sis_oper" => "string",
                "ds_res_tela" => "string",
                // "" => ":",
            ],
            //Estes campos não furam a validação
            "camposOpcionais" => [

            ],
            //Nível mínimo de notificação (e-mail, Telegram, etc.)
            "notificarAcimaDe" => 4
        ];
    }

    /**
     * Funções para obtenção de cada um dos tipos de dados relacionados nos tipos de auditoria acima.
     * @return array
     */
    public static function gettersTipos(){
        return [
            'data' => now()->format('Ymd'),
            'hora' => now()->format('His'),
            'ip' => request()->getClientIp(true),
        ];
    }


/**===================================
 * Validações
 * As funções abaixo efetuam validações dos tipos acima.
 * =====================================
 */


/**
 * Regras de validação para cada tipo de campo especificado nos diferentes tipos de log. A validação é feita por expressão regular.
 * A regra é o primeiro parâmetro na descrição de um campo nos tipos.
 * @return void
 */
public static function regExValidadores(){
    //E lá vamos nós. Não se assuste lendo esta parte do código.
    return [
        //Qualquer caractere, menos vazio
        "string" => [
            "regex"=>'^(?!\s*$).+^',
            "erro"=>"String vazio. Por favor forneça um string válido."
        ],
        //Qualquer inteiro positivo (unsigned). Para chaves.
        "int" => [
            "regex" => '^\d+$^',
            "erro" => 'Inteiro inválido. Forneça um número inteiro positivo correto.'
        ],
        //Qualquer data até o ano 9999, no formato YYYYMMDD
        "data" => [
            "regex" => '(((\d{4})(0[13578]|10|12)(0[1-9]|[12][0-9]|3[01]))|((\d{4})(0[469]|11)(0[1-9]|[12][0-9]|30))|((\d{4})(02)(0[1-9]|1[0-9]|2[0-8]))|([0-9][0-9][02468]40229)|([0-9][0-9][02468]80229)|([0-9][0-9][13579]20229)|([0-9][0-9][13579]60229)|([0-9][0-9][02468]00229))',
            "erro" => 'Data em formato incorreto. Deve ser YYYYMMDD.'
        ],
        // HHMMSS - 24H
        "hora" =>
        [
            "regex"=> '^(?:(?:([01]?\d|2[0-3]))([0-5]\d))([0-5]\d)$^',
            "erro" => 'Hora inválida. Formato deve ser HHMMSS 24h.'
        ],
        // IPv4 e IPv6 (respire fundo)
        "ip" => [
            "regex"=>"((^\s*((([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))\s*$)|(^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$))",

            "erro" => "Este não é um IPv4 ou IPv6 válido."
        ],
    ];
}





/**
 * Validar um tipo de log contra dados fornecidos
 *
 * @param [class] $tipo
 * @param [array] $dados
 * @return void
 */
public static function validarTipo($tipo, $dados){
    // A counter
    $errors = [];
    foreach ($dados as $dado => $value) {
        $validacao = TipoLog::validaCampoTipo($dado, $value, $tipo);
        if (is_array($validacao)) {
            //Ocorreu um erro
            $errors[]=$validacao['erro'];
        }
    }
    if(count($errors)>0){
        //Union Operator
        $result = array_merge(["Tipo de evento originador da falha de: *".$tipo['nome'].'*'], $errors);
        return $result;
    }
    else {
        return true;
    }
}
/**
 * Validar um campo em específico de acordo com as regras estabelecidas nos arrays de validação.
 *
 * @param [type] $campo
 * @param [type] $dado
 * @return void
 */
public static function validaCampoTipo($campo, $dado, $tipo){
    //Validar o compliance de campos
    if(!array_key_exists($campo,$tipo['dados'])){
        //Falha específica para o canal de notificação
        Auditoria::notifyToTelegram('Alerta', 'Impossível gerar log de tipo: *'.$tipo['nome'].'*.
O campo informado: ```'.$campo.'``` não foi encontrado no rol de propriedades do tipo de log especificado. Provável causa: falha em declaração no código. Possivelmente um erro de digitação.');
        //Falha genérica para a interface, caso apareça para o usuário.
        throw new \Exception('Ocorreu uma falha de validação de logs. Por favor volte mais tarde.');

    }
    //Validar o tipo do campo e regras
    $arrTipo = explode(':',$tipo['dados'][$campo]);
    //Obter as expressões regulares para validar diferentes tipos
    $regras = TipoLog::regExValidadores();
    //Verificar o tamanho do array de validação para o campo inspecionado neste momento
    //Verificar se o tipo existente não está entre as exclusões
    foreach($tipo['camposOpcionais'] as $opc){
        if($campo==$opc && $dado==''){
            //Simplesmente retornar true
            return true;
        }
        //Senão, seguir o fluxo de validação. Se um campo obrigatório não estiver preenchido, este deve ser validado assim como todos os outros.
    }
    switch (count($arrTipo)) {
        //O array de regras de validação para este campo possui 3 parâmetros
        case 3:
            if(!(preg_match($regras[$arrTipo[0]]['regex'], $dado))){
                return ["erro"=>$regras[$arrTipo[0]]['erro'].' Campo: ```'.$campo.'```valor informado: '.$dado];
            }
            if(strlen($dado)<>$arrTipo[1]){
                return ["erro"=>'Tamanho do campo ```'.$campo.'```incorreto. Deveria ser '.$arrTipo[1].' atualmente é '.strlen($dado).'.'];
            }
            if(!(preg_match($regras[$arrTipo[2]]['regex'], $dado))){
                return ["erro"=>$regras[$arrTipo[2]]['erro'].' Campo: ```'.$campo.'```valor informado: '.$dado];
            }

            break;
        //O array de validação para este campo possui apenas 2 parâmetros
        case 2:
            if(!(preg_match($regras[$arrTipo[0]]['regex'], $dado))){
                return ["erro"=>$regras[$arrTipo[0]]['erro'].' Campo: ```'.$campo.'```valor informado: '.$dado];
            }
            if(strlen($dado)<>$arrTipo[1]){
                return ["erro"=>'Tamanho do campo ```'.$campo.'```incorreto. Deveria ser '.$arrTipo[1].' atualmente é '.strlen($dado).'.'];
            }
            break;
        case 1:
            if(!(preg_match($regras[$arrTipo[0]]['regex'], $dado))){
                return ["erro"=>$regras[$arrTipo[0]]['erro'].' Campo: ```'.$campo.'```valor informado: '.$dado];
            }
            break;
        // Erro no formato informado
        default:
            // Erro de validação
            break;

        }
    return true;


    // dd($arrTipo,$dado);
}





/**
 * Nomes dos níveis de log
 *
 * @return array
 */
public static function getNiveisDeLog(){
    return [
        1 =>'Debug',
        2 =>'Informação',
        3 =>'Nota',
        4 =>'Aviso',
        5 =>'ERRO',
        6 =>'CRÍTICO',
        7 =>'ALERTA',
        8 =>'EMERGÊNCIA',
    ];
}






}
