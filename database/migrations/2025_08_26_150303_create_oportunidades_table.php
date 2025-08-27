<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('oportunidades', function (Blueprint $table) {
            $table->uuid('guid')->unique();
            $table->string('cliente')->nullable();
            $table->string('clienteCodigo')->nullable();
            $table->integer('codigo')->nullable();
            $table->string('complemento')->nullable();
            $table->string('contato')->nullable();
            $table->dateTime('dataAtualizacao')->nullable();
            $table->dateTime('dataCriacao')->nullable();
            $table->dateTime('dataPrevista')->nullable();
            $table->string('descricao')->nullable();
            $table->json('extra')->nullable();
            $table->string('filial')->nullable();
            $table->uuid('funil')->nullable();
            $table->double('latitude')->nullable();
            $table->string('local')->nullable();
            $table->string('localEvento')->nullable();
            $table->double('longitude')->nullable();
            $table->string('motivo')->nullable();
            $table->string('numeroProjeto')->nullable();
            $table->text('observacoesEstrategia')->nullable();
            $table->string('orcamento')->nullable();
            $table->string('origem')->nullable();
            $table->string('probabilidade')->nullable();
            $table->string('responsaveis')->nullable(); // JSON ou string, dependendo do uso
            $table->string('responsavel')->nullable();
            $table->string('status')->nullable();
            $table->decimal('valorTotal', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oportunidades');
    }
};
