<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->uuid('guid')->unique();
            $table->string('nome');
            $table->string('apelidoFantasia')->nullable();
            $table->string('cpfcnpj')->nullable();
            $table->string('inscricaoEstadual')->nullable();
            $table->string('email')->nullable();
            $table->string('emailAdicional')->nullable();
            $table->string('telefone')->nullable();
            $table->string('celular')->nullable();
            $table->string('endereco')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('municipio')->nullable();
            $table->string('municipioIbge')->nullable();
            $table->string('uf')->nullable();
            $table->string('cep')->nullable();
            $table->string('pais')->nullable();
            $table->text('observacoes')->nullable();
            $table->string('origem')->nullable();
            $table->string('situacao')->nullable();
            $table->string('status')->nullable();
            $table->string('ramo')->nullable();
            $table->string('setor')->nullable();
            $table->string('setorialAreaAtuacao')->nullable();
            $table->string('tipo')->nullable();
            $table->string('tipoContribuinte')->nullable();
            $table->string('regimeTributario')->nullable();
            $table->string('site')->nullable();
            $table->string('consumidorFinal')->nullable();
            $table->string('pagamentoCondicao')->nullable();
            $table->string('pagamentoForma')->nullable();
            $table->string('pagamentoTipo')->nullable();
            $table->string('banco')->nullable();
            $table->string('numeroBanco')->nullable();
            $table->string('agencia')->nullable();
            $table->string('contaCorrente')->nullable();
            $table->string('codigo')->nullable();
            $table->date('dataNascimento')->nullable();
            $table->date('dataAtualizacao')->nullable();
            $table->integer('pessoa')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
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
        Schema::dropIfExists('clients');
    }
};