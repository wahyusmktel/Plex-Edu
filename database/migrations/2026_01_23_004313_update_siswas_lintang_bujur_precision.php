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
            $table->decimal('lintang', 12, 8)->nullable()->change();
            $table->decimal('bujur', 12, 8)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->decimal('lintang', 10, 8)->nullable()->change();
            $table->decimal('bujur', 11, 8)->nullable()->change();
        });
    }
};
