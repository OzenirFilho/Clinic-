<?php

namespace App\Console\Commands;

use App\Entidades\Sca\M0001 as Usuario;
use App\Entidades\Sca\M0002 as Perfil;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class AmbienteTeste extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teste:instalar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando faz o seed de dados necessários para testar a plataforma manualmente';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        // Test database connection
        try {
            $this->info("--------------------------------------------");
            $this->info("Conexão com o banco de dados encontrada.");
            $this->info("--------------------------------------------");
            $this->call('migrate:fresh');
            $this->call('db:seed');
            $this->info("Parece que deu tudo certo.");
            $this->info("Obtendo os dados do Administrador...");
            $u = Usuario::where('cd_perm_usu', Perfil::where('nm_perm_usu', 'Administrador')->first()->cd_perm_usu)->first() or die('Erro ao obter o Admin');
            $this->info("--------------------------------------------");
            $this->info("Dados de acesso do Administrador");
            $this->info("--------------------------------------------");
            $this->warn("CPF: ". $u->nr_cpf);
            $this->warn("Senha: adm12345");
            $this->info("--------------------------------------------");
            $this->info("O Admin não precisa alterar a senha no primeiro acesso");
            $this->info("--------------------------------------------");
          // $this->exec();
        } catch (\Exception $e) {
            $this->comment("Banco de dados não configurado. Ou não acessível deste daemon.");
            $this->comment("Gere um .env e coloque os parâmetros de conexão adequados.");
            $this->comment('Detalhes do erro: '.$e);
        }
    }
}
