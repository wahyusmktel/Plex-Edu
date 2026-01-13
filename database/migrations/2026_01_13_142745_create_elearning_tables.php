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
        Schema::create('e_learnings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('subject_id');
            $table->uuid('teacher_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('fungsionaris')->onDelete('cascade');
        });

        Schema::create('e_learning_chapters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('e_learning_id');
            $table->string('title');
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('e_learning_id')->references('id')->on('e_learnings')->onDelete('cascade');
        });

        Schema::create('e_learning_modules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('chapter_id');
            $table->enum('type', ['material', 'assignment', 'exercise', 'exam']);
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('file_path')->nullable();
            $table->uuid('cbt_id')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('chapter_id')->references('id')->on('e_learning_chapters')->onDelete('cascade');
            $table->foreign('cbt_id')->references('id')->on('cbts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_learning_modules');
        Schema::dropIfExists('e_learning_chapters');
        Schema::dropIfExists('e_learnings');
    }
};
