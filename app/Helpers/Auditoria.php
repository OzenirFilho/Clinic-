<?php
use Telegram\Bot\Api;

use App\Entidades\Sca\M0090 as EntFalhaLog;
use App\Entidades\Auditor\TipoLog;
use Illuminate\Support\Facades\Auth;
use App\Entidades\Auditor\Auditor as AuditorEnt;

namespace App\Helpers;

class Auditoria
{

    // Propriedades da classe
    protected $nomeUsuario;

    /**
     * Construtor.
     */
    public function __construct(){
        $this->nomeUsuario = (!\Auth::user()) ? 'Anonimo' : auth()->user()->nome();
    }

    /**
     * Ação de log. Registra um log no sistema. (tipos: LogFalhaLogin)
     *
     * @param [string] $tipo (Opções disponíveis: LogFalhaLogin)
     * @param [int] $nivel
     * @param [string] $mensagem
     * @param [array] $dados
     * @return boolean
     */
    public static function atividade($tipo, $nivel, $mensagem, $dados){
        $niveis = \App\Entidades\Auditor\TipoLog::getNiveisDeLog();
        // Pilha de verificações
        // 1 - Verificar se os dados estão setados
        if (!Auditoria::bulkIsset($tipo, $nivel, $mensagem, $dados)){
            // TODO:Notificar o grupo do Telegram aqui

            throw new \Exception('Falha crítica de log! Não é possível satisfazer as condições para registrar o log. Parâmetros faltando.'.Auditoria::dadosParaFalhasDeLog());
        }
        // 2 - Verificar se o tipo em questão existe existe. O tipo é uma função pública em TipoLog
        if (!method_exists(\App\Entidades\Auditor\TipoLog::class, $tipo)){
            //TODO: Notificar no Telegram desta falha

            throw new \Exception('Falha de log! Chamada para tipo de log inexistente: "'.$tipo.'".'.Auditoria::dadosParaFalhasDeLog());
        }
        // 3 - Instanciar a estrutura do tipo de log
        $t = \App\Entidades\Auditor\TipoLog::$tipo();
        //Efetuar verificações de cada entrada aqui
        // 4 - Verificar se os dados batem com as exigências do tipo.
        $result = Auditoria::verificarDadosDeLog($t, $dados);
        //Houve um ou mais erros na validação de dados contra os parâmetros exigidos.
        if(is_array($result)){
            Auditoria::notifyToTelegramWithArrayOfErrors('Alerta - Falha na validação para gravar logs', $result);

            throw new \Exception('Falha de validação de log. Por favor contate o administrador do sistema.');
        }
        // 5 - Verificar se o nível de log é válido
        //Tamanho do string deve ser 1
        if(!(strlen($nivel)==1)) {
            Auditoria::notifyToTelegram('Alerta - Falha no registro de log','Nível de log incoreto. Nível muito grande. Deve estar entre 1 e 7.
Tipo: '.$tipo);
            throw new \Exception('Falha de validação de log. Por favor contate o administrador do sistema. Erro: nível incoreto. Muito grande.');
        }
        //Nível de log está sempre entre 1 e 8
        /**
         * São eles (do mais baixo pro mais alto)
         * 1 - Debug
         * 2 - Informação
         * 3 - Nota
         * 4 - Aviso
         * 5 - Erro
         * 6 - Crítico
         * 7 - Alerta
         * 8 - Emergência
         */
        if(!preg_match('(^[1-8]{1})',$nivel)){
            Auditoria::notifyToTelegram('Alerta - Falha no registro de log','Nível de log incoreto.
Tipo: '.$tipo);
            throw new \Exception('Falha de validação de log. Por favor contate o administrador do sistema. Erro: nível incorreto. Deve estar ente os níveis do rol de níveis de erro.');
        }
        //Neste ponto as condições para registro de log devem estar satisfeitas. Qualquer erro até esse ponto vai parar a operação e reportar uma falha.

        //Agora, que todas as condições foram satisfeitas sem erros, vamos persistir a informação no BD.
        //Nome da classe a classe relacionada ao tipo de auditoria
        $classe = $t['classe'];
        //Instanciar dinamicamente a classe em questão
        $l = new $classe();
        $array = \App\Entidades\Auditor\TipoLog::gettersTipos();
        //Preencher todos os campos
        foreach($t['dados'] as $campo=>$parametros){
            //Nível de log é declarado na chamada do método de registro
            if($campo=='nr_nivel_log') {
                $l->nr_nivel_log=$nivel;
            }
            else {
                //Verificar se o campo é obrigatório ou não
                if(in_array($campo, $t['camposOpcionais'])){
                    //Se for opcional, verificar se ele foi declarado
                    if(isset($dados[$campo])){
                        //Se não tiver null ou vazio, atribuir ao Model.
                        if(!(is_null($dados[$campo])) && !(empty($dados[$campo]))){
                            $l->{$campo}=$dados[$campo];
                        }
                    }
                }
                //Campo obrigatório
                else {
                    $l->{$campo}=$dados[$campo];
                }
            }
        }
        $l->save();
        //Agora que salvamos o registro no BD, vamos verificar se é necessário enviar uma notificação.
        if($nivel>=$t['notificarAcimaDe']) {
            Auditoria::notifyToTelegram($niveis[$nivel],'Notificação de evento. Foi registrado um log de tipo '.$tipo.' com o status:*'.PHP_EOL.$mensagem.PHP_EOL.'*Esta é uma notificação automática');
        }
        // Se chegamos até aqui sem BOs então deu tudo certo
        return true;
    }

    /**
     * Verifica, em lotes, se variáveis estão setadas e não são null.
     * @return boolean
     * @param arrays $array
     */
    protected static function bulkIsset(... $array){
        foreach($array as $arrayElement) {
            if(!isset($arrayElement) || is_null($arrayElement)){
                return false;
            }
        }
        return true;
    }
    /**
     * Verificação e sanitização de dados do log.  Esta função verifica se todos os requisitos de um tipo de log foram satisfeitos antes de autorizar a persistência do log.
     *
     * @param [class] $tipo
     * @param [array] $dados
     * @return boolean
     */
    protected static function verificarDadosDeLog($tipo, $dados){
        $a = \App\Entidades\Auditor\TipoLog::validarTipo($tipo, $dados);
        return $a;
    }



    /**
     * Envia aos canais necessários falhas de log
     *
     * @param [type] $alerta
     * @param [type] $usuario
     * @param [type] $tipo
     * @param [type] $mensagem
     * @param [type] $dados
     * @return void
     */

    /**
     * DRY: Padronização de dados sobre usuário em falhas
     *
     * @return string
     */
    protected static function dadosParaFalhasDeLog(){
        $user = (!\Auth::user()) ? 'Anônimo (não logado)' : auth()->user()->nome();
        return 'Usuário: '.$user. '. Data/Hora do evento: '.now().PHP_EOL.'IP: '.request()->getClientIp(true);
    }
    /**
     * Sends Markdown formatted text to a Telegram channel
     *
     * @return void
     */
    public static function notifyToTelegram($titulo, $message){
        if(!env('TELEGRAM_NOTIFICATION_ENABLE')) {
            Auditoria::writeToStdOut('Notificação de Log para o Telegram não enviada (desativada nas configurações de ambiente).');
        }
        else {
            $c = new \GuzzleHttp\Client();
            $titulo = "*".$titulo."*".PHP_EOL;
            $dados = Auditoria::dadosParaFalhasDeLog();
            try{
                $c->get("https://api.telegram.org/".env("TELEGRAM_BOT_TOKEN")."/sendMessage?chat_id=".env("TELEGRAM_CHANNEL_ID")."&parse_mode=markdown&text=".urlencode($titulo.$message.PHP_EOL.$dados));
            }
            catch(\GuzzleHttp\Exception\RequestException $e){
                Auditoria::writeToStdErr('ERRO: falha ao enviar notificação ao bot do Telegram.');
            }
        }

    }
    /**
     * Envia uma notificação de erro com um array dos erros encontrados.
     *
     * @return void
     */
    public static function notifyToTelegramWithArrayOfErrors($titulo, $array){
        if(!env('TELEGRAM_NOTIFICATION_ENABLE')) {
            Auditoria::writeToStdOut('Crítico: Notificação para o Telegram não enviada (desativada nas configurações de ambiente). Podem haver erros de validação impedindo a gravação de logs. Verificar onde o Helper Auditoria foi chamado por último nas alterações.');
        }
        else {
            $c = new \GuzzleHttp\Client();
            $titulo = "*".$titulo."*".PHP_EOL;
            $message='';
            $dados = Auditoria::dadosParaFalhasDeLog();
            foreach ($array as $item) {
                $message .=$item.PHP_EOL;
            }
            try{
                $c->get("https://api.telegram.org/".env("TELEGRAM_BOT_TOKEN")."/sendMessage?chat_id=".env("TELEGRAM_CHANNEL_ID")."&parse_mode=markdown&text=".urlencode($titulo.$message.PHP_EOL.$dados));
            }
            catch(\GuzzleHttp\Exception\RequestException $e){
                Auditoria::writeToStdErr('ERRO: falha ao enviar notificação ao bot do Telegram.');
            }
        }

    }

    public static function writeToStdErr($message) {
        $fh = fopen('php://stderr','w');
            fwrite($fh, $message . PHP_EOL);
        fclose($fh);
    }
    public static function writeToStdOut($message) {
        $fh = fopen('php://stdout','w');
            fwrite($fh, $message . PHP_EOL);
        fclose($fh);
    }

    public static function getUserIP(){
        return request()->getClientIp(true);
    }



}
