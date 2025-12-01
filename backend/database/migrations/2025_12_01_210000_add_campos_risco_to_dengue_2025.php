<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposRiscoToDengue2025 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dengue_2025', function (Blueprint $table) {
            if (!Schema::hasColumn('dengue_2025', 'score_epidemiologia')) {
                $table->float('score_epidemiologia')->nullable();
            }
            if (!Schema::hasColumn('dengue_2025', 'score_gravidade')) {
                $table->float('score_gravidade')->nullable();
            }
            if (!Schema::hasColumn('dengue_2025', 'score_sintomas')) {
                $table->float('score_sintomas')->nullable();
            }
            if (!Schema::hasColumn('dengue_2025', 'score_sociodemografico')) {
                $table->float('score_sociodemografico')->nullable();
            }
            if (!Schema::hasColumn('dengue_2025', 'score_final')) {
                $table->float('score_final')->nullable();
            }
            if (!Schema::hasColumn('dengue_2025', 'nivel_risco')) {
                $table->string('nivel_risco', 20)->nullable();
            }
            if (!Schema::hasColumn('dengue_2025', 'justificativa')) {
                $table->text('justificativa')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dengue_2025', function (Blueprint $table) {
            $table->dropColumn([
                'score_epidemiologia',
                'score_gravidade',
                'score_sintomas',
                'score_sociodemografico',
                'score_final',
                'nivel_risco',
                'justificativa',
            ]);
        });
    }
}
