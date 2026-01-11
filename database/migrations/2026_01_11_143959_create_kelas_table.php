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
        Schema::create('kelas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('tingkat');
            $table->uuid('wali_kelas_id')->nullable();
            $table->uuid('jurusan_id')->nullable();
            $table->integer('kapasitas')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('wali_kelas_id')->references('id')->on('fungsionaris')->onDelete('set null');
            $table->foreign('jurusan_id')->references('id')->on('jurusans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
