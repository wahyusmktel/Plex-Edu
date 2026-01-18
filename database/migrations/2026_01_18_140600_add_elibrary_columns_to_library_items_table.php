<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('library_items', function (Blueprint $table) {
            if (!Schema::hasColumn('library_items', 'tipe')) {
                $table->enum('tipe', ['ebook', 'audiobook', 'videobook'])->default('ebook')->after('category');
            }
            if (!Schema::hasColumn('library_items', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('cover_image');
            }
            if (!Schema::hasColumn('library_items', 'durasi')) {
                $table->integer('durasi')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('library_items', 'jumlah_halaman')) {
                $table->integer('jumlah_halaman')->nullable()->after('durasi');
            }
            if (!Schema::hasColumn('library_items', 'penerbit')) {
                $table->string('penerbit')->nullable()->after('author');
            }
            if (!Schema::hasColumn('library_items', 'tahun_terbit')) {
                $table->string('tahun_terbit')->nullable()->after('penerbit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('library_items', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'is_active', 'durasi', 'jumlah_halaman', 'penerbit', 'tahun_terbit']);
        });
    }
};
