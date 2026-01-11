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
        Schema::create('fungsionaris', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('nip')->unique();
            $table->string('nik')->unique();
            $table->string('nama');
            $table->string('posisi');
            $table->enum('jabatan', ['guru', 'pegawai']);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            
            // Optional Data (Tab 2)
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fungsionaris');
    }
};
