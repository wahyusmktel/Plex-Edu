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
        Schema::table('siswas', function (Blueprint $table) {
            // Drop old unique indexes
            $table->dropUnique('siswas_nis_unique');
            $table->dropUnique('siswas_nisn_unique');

            // Add new composite unique indexes
            $table->unique(['school_id', 'nis'], 'siswas_school_nis_unique');
            $table->unique(['school_id', 'nisn'], 'siswas_school_nisn_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropUnique('siswas_school_nis_unique');
            $table->dropUnique('siswas_school_nisn_unique');

            $table->unique('nis');
            $table->unique('nisn');
        });
    }
};
