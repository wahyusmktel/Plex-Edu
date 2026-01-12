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
        Schema::create('pelanggaran_pegawais', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('fungsionaris_id');
            $table->uuid('master_pelanggaran_id');
            $table->date('tanggal');
            $table->text('deskripsi')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->timestamps();

            $table->foreign('fungsionaris_id')->references('id')->on('fungsionaris')->onDelete('cascade');
            $table->foreign('master_pelanggaran_id')->references('id')->on('master_pelanggarans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggaran_pegawais');
    }
};
