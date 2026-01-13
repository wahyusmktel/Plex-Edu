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
        Schema::create('teacher_certificates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('teacher_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->year('year');
            $table->enum('expiry_type', ['date', 'year', 'none'])->default('none');
            $table->date('expiry_date')->nullable();
            $table->year('expiry_year')->nullable();
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('fungsionaris')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_certificates');
    }
};
