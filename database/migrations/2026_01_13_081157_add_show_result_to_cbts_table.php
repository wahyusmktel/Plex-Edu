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
            $table->boolean('show_result')->default(true)->after('skor_maksimal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbts', function (Blueprint $table) {
            $table->dropColumn('show_result');
        });
    }
};
