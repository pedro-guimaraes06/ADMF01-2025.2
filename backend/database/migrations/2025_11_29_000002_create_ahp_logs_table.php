<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAhpLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ahp_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('avaliacao_id')->comment('ID da avaliação de risco');
            $table->string('criterio', 50)->comment('Nome do critério AHP');
            $table->string('subcriterio', 100)->nullable()->comment('Subcritério específico');
            $table->decimal('peso', 5, 4)->comment('Peso do critério no AHP');
            $table->decimal('valor_original', 10, 4)->nullable()->comment('Valor antes da normalização');
            $table->decimal('valor_normalizado', 5, 4)->comment('Valor normalizado (0-1)');
            $table->decimal('score_parcial', 5, 4)->comment('Contribuição para score final');
            $table->text('observacao')->nullable()->comment('Informações adicionais');
            $table->timestamps();

            // Chave estrangeira
            $table->foreign('avaliacao_id')
                  ->references('id')
                  ->on('avaliacoes_risco')
                  ->onDelete('cascade');

            // Índices
            $table->index('avaliacao_id');
            $table->index('criterio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ahp_logs');
    }
}
