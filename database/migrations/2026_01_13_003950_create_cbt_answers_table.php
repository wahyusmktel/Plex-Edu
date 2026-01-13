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
        Schema::create('cbt_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('session_id');
            $table->uuid('question_id');
            $table->uuid('option_id')->nullable(); // For Multiple Choice
            $table->text('essay_answer')->nullable(); // For Essay
            $table->integer('poin_didapat')->default(0);
            $table->boolean('is_graded')->default(false);
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('cbt_sessions')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('cbt_questions')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('cbt_options')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_answers');
    }
};
