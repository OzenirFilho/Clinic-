<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddsAutoIncrementToM0001 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
            Schema::table('t0001', function (Blueprint $table) {
            $table->bigIncrements('cd_usuario')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t0001', function (Blueprint $table) {
            //
        });
    }
}
