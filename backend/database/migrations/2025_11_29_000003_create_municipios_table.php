<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100)->comment('Nome do município');
            $table->string('uf', 2)->comment('Unidade Federativa');
            $table->string('codigo_ibge', 7)->unique()->comment('Código IBGE (7 dígitos)');
            $table->integer('populacao')->nullable()->comment('População estimada');
            $table->decimal('densidade_demografica', 10, 2)->nullable()->comment('Habitantes por km²');
            $table->string('regiao_saude', 100)->nullable()->comment('Região de saúde');
            $table->timestamps();

            // Índices
            $table->index('uf');
            $table->index('codigo_ibge');
            $table->index(['nome', 'uf']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipios');
    }
}
