<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('kelas_id');
            $table->uuid('subject_id');
            $table->uuid('jam_id');
            $table->uuid('school_setting_id'); // Active year/semester
            $table->string('hari');
            $table->timestamps();

            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('jam_id')->references('id')->on('jam_pelajarans')->onDelete('cascade');
            $table->foreign('school_setting_id')->references('id')->on('school_settings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
