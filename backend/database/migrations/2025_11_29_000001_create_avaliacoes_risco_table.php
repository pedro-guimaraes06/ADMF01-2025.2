<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvaliacoesRiscoTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta tabela armazena as avaliações de risco de dengue.
     * Nota: A tabela é chamada 'dengue_2025' mas funciona como avaliacoes_risco.
     *
     * @return void
     */
    public function up()
    {
        // Não criar a tabela se já existir (suporta importação de dados existentes)
        if (Schema::hasTable('dengue_2025')) {
            return;
        }

        Schema::create('dengue_2025', function (Blueprint $table) {
            $table->id();
            
            // Campos clínicos e epidemiológicos existentes
            // Sintomas clássicos
            $table->integer('FEBRE')->nullable();
            $table->integer('MIALGIA')->nullable();
            $table->integer('CEFALEIA')->nullable();
            $table->integer('EXANTEMA')->nullable();
            $table->integer('VOMITO')->nullable();
            $table->integer('NAUSEA')->nullable();
            $table->integer('DOR_COSTAS')->nullable();
            $table->integer('CONJUNTVIT')->nullable();
            $table->integer('ARTRALGIA')->nullable();
            $table->integer('DOR_RETRO')->nullable();
            $table->integer('PETEQUIA_N')->nullable();
            $table->integer('LEUCOPENIA')->nullable();
            $table->integer('LACO')->nullable();
            
            // Sinais de alarme
            $table->integer('ALRM_HIPOT')->nullable();
            $table->integer('ALRM_PLAQ')->nullable();
            $table->integer('ALRM_VOM')->nullable();
            $table->integer('ALRM_SANG')->nullable();
            $table->integer('ALRM_HEMAT')->nullable();
            $table->integer('ALRM_ABDOM')->nullable();
            $table->integer('ALRM_LETAR')->nullable();
            $table->integer('ALRM_HEPAT')->nullable();
            $table->integer('ALRM_LIQ')->nullable();
            
            // Sinais de gravidade
            $table->integer('GRAV_PULSO')->nullable();
            $table->integer('GRAV_CONV')->nullable();
            $table->integer('GRAV_ENCH')->nullable();
            $table->integer('GRAV_EXTRE')->nullable();
            $table->integer('GRAV_HIPOT')->nullable();
            $table->integer('GRAV_HEMAT')->nullable();
            $table->integer('GRAV_MELEN')->nullable();
            $table->integer('GRAV_METRO')->nullable();
            $table->integer('GRAV_SANG')->nullable();
            $table->integer('GRAV_AST')->nullable();
            $table->integer('GRAV_MIOC')->nullable();
            $table->integer('GRAV_CONSC')->nullable();
            $table->integer('GRAV_ORGAO')->nullable();
            
            // Dados demográficos
            $table->string('CS_SEXO', 1)->nullable();
            $table->string('SG_UF', 2)->nullable();
            $table->integer('ID_MN_RESI')->nullable();
            $table->integer('NU_IDADE_N')->nullable();
            $table->integer('SEM_NOT')->nullable();
            
            // Campos calculados para análise
            $table->float('TENDENCIA_TEMPORAL')->nullable();
            $table->integer('SINTOMAS_TOTAL')->nullable();
            $table->integer('ALARMES_TOTAIS')->nullable();
            $table->integer('GRAVIDADE_TOTAL')->nullable();
            
            // Campos de avaliação de risco (AHP)
            $table->float('score_epidemiologia')->nullable()->comment('Score do critério epidemiológico');
            $table->float('score_gravidade')->nullable()->comment('Score do critério de gravidade clínica');
            $table->float('score_sintomas')->nullable()->comment('Score do critério de sintomas');
            $table->float('score_sociodemografico')->nullable()->comment('Score do critério sociodemográfico');
            $table->float('score_final')->nullable()->comment('Score final calculado (0-1)');
            $table->string('nivel_risco', 20)->nullable()->comment('Nível de risco: Baixo, Médio ou Alto');
            $table->text('justificativa')->nullable()->comment('Explicação da classificação');
            $table->json('input_json')->nullable()->comment('JSON com os dados de entrada da avaliação');
            
            // Índices para otimizar consultas
            $table->index('nivel_risco');
            $table->index('SG_UF');
            $table->index('SEM_NOT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dengue_2025');
    }
}
