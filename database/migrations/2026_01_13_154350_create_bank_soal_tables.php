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
        Schema::create('bank_soals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('subject_id');
            $table->uuid('teacher_id'); // fungsionaris_id
            $table->string('title');
            $table->enum('level', ['X', 'XI', 'XII']);
            $table->enum('status', ['private', 'public'])->default('private');
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('fungsionaris')->onDelete('cascade');
        });

        Schema::create('bank_soal_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bank_soal_id');
            $table->enum('jenis_soal', ['pilihan_ganda', 'essay']);
            $table->text('pertanyaan');
            $table->string('gambar')->nullable();
            $table->integer('poin')->default(0);
            $table->timestamps();

            $table->foreign('bank_soal_id')->references('id')->on('bank_soals')->onDelete('cascade');
        });

        Schema::create('bank_soal_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bank_soal_question_id');
            $table->text('opsi');
            $table->string('gambar')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            $table->foreign('bank_soal_question_id')->references('id')->on('bank_soal_questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_soal_options');
        Schema::dropIfExists('bank_soal_questions');
        Schema::dropIfExists('bank_soals');
    }
};
