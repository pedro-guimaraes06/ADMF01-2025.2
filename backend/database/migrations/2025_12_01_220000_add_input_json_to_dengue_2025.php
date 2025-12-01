<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInputJsonToDengue2025 extends Migration
{
    public function up(): void
    {
        Schema::table('dengue_2025', function (Blueprint $table) {
            if (!Schema::hasColumn('dengue_2025', 'input_json')) {
                $table->longText('input_json')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('dengue_2025', function (Blueprint $table) {
            if (Schema::hasColumn('dengue_2025', 'input_json')) {
                $table->dropColumn('input_json');
            }
        });
    }
}
