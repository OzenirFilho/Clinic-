<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AtualizaCampoSenhaScaT0001 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        // Alterar o tamanho do campo para usar hashes BCrypt
        Schema::table('t0001', function (Blueprint $table) {
            $table->string('ds_snh_usu', 100)->change();
            $table->string('ds_snh_usu_ant', 100)->change();
            $table->string('nr_cpf', 11)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        Schema::table('t0001', function (Blueprint $table) {
            $table->string('ds_snh_usu', 40)->change();
            $table->string('ds_snh_usu_ant', 40)->change();
            $table->bigInteger('nr_cpf', 11)->change();
        });
    }
}