<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('library_items')) {
            Schema::create('library_items', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('school_id')->nullable();
                $table->string('judul');
                $table->text('deskripsi')->nullable();
                $table->string('penulis')->nullable();
                $table->string('penerbit')->nullable();
                $table->string('tahun_terbit')->nullable();
                $table->enum('tipe', ['ebook', 'audiobook', 'videobook'])->default('ebook');
                $table->string('cover_image')->nullable();
                $table->string('file_path')->nullable();
                $table->integer('durasi')->nullable()->comment('Duration in minutes for audio/video');
                $table->integer('jumlah_halaman')->nullable()->comment('Number of pages for ebook');
                $table->string('kategori')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('library_items');
    }
};
