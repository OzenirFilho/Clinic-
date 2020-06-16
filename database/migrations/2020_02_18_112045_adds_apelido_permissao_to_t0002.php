<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddsApelidoPermissaoToT0002 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        Schema::table('t0002', function (Blueprint $table) {
            $table->string('nm_apelido_perm')->comment('Apelido para classe. Será utilizado para checar permissões. Assim o nome da permissão pode ter acentos e espaços')->nullable();
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
        Schema::table('t0002', function (Blueprint $table) {
            $table->dropColumn('nm_apelido_perm');
        });
    }
}
