<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvaliacoesRiscoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliacoes_risco', function (Blueprint $table) {
            $table->id();
            $table->text('input_json')->comment('JSON com os dados de entrada da avaliação');
            $table->decimal('score_epidemiologia', 5, 4)->comment('Score epidemiológico (peso 0.45)');
            $table->decimal('score_gravidade', 5, 4)->comment('Score gravidade clínica (peso 0.35)');
            $table->decimal('score_sintomas', 5, 4)->comment('Score sintomas (peso 0.15)');
            $table->decimal('score_sociodemografico', 5, 4)->comment('Score sociodemográfico (peso 0.05)');
            $table->decimal('score_final', 5, 4)->comment('Score final AHP (0-1)');
            $table->string('nivel_risco', 20)->comment('Baixo, Médio ou Alto');
            $table->text('justificativa')->nullable()->comment('Explicação da classificação');
            $table->timestamps();

            // Índices para consultas frequentes
            $table->index('nivel_risco');
            $table->index('created_at');
            $table->index('score_final');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avaliacoes_risco');
    }
}
