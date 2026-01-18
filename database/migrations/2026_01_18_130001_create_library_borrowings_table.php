<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_borrowings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id')->nullable();
            $table->uuid('library_item_id');
            $table->uuid('siswa_id');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->integer('durasi_hari');
            $table->enum('status', ['active', 'returned', 'expired'])->default('active');
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('library_item_id')->references('id')->on('library_items')->onDelete('cascade');
            $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_borrowings');
    }
};
