<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VersaoInicial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('usuario_id')->unsigned();
            $table->date('data_extracao');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->timestamps();
        });
        
        Schema::create('lotacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->integer('lider_id')->unsigned();
            $table->foreign('lider_id')->references('id')->on('users');
            $table->timestamps();
        });
        
        Schema::create('relatorios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('carga_id')->unsigned();
            $table->integer('lotacao_id')->unsigned();
            $table->text('conteudo');
            $table->text('analise');
            $table->text('acoes');
            $table->foreign('carga_id')->references('id')->on('cargas');
            $table->foreign('lotacao_id')->references('id')->on('lotacoes');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analises');
        Schema::dropIfExists('relatorios');
        Schema::dropIfExists('lotacoes');
        Schema::dropIfExists('cargas');
    }
}
