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
        Schema::create('cbt_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cbt_id');
            $table->enum('jenis_soal', ['pilihan_ganda', 'essay']);
            $table->text('pertanyaan');
            $table->string('gambar')->nullable();
            $table->integer('poin')->default(0);
            $table->timestamps();

            $table->foreign('cbt_id')->references('id')->on('cbts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_questions');
    }
};
