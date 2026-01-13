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
        Schema::create('cbt_kelas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cbt_id');
            $table->uuid('kelas_id');
            $table->timestamps();
            
            $table->foreign('cbt_id')->references('id')->on('cbts')->onDelete('cascade');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_kelas');
    }
};
