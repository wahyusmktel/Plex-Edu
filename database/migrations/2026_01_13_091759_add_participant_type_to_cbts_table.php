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
        Schema::table('cbts', function (Blueprint $table) {
            $table->enum('participant_type', ['all', 'kelas', 'siswa'])->default('all')->after('show_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbts', function (Blueprint $table) {
            $table->dropColumn('participant_type');
        });
    }
};
