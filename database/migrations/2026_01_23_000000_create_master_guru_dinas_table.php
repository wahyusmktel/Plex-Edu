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
        Schema::create('master_guru_dinas', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary();
            $blueprint->string('nama')->nullable();
            $blueprint->string('nik')->nullable();
            $blueprint->string('nuptk')->nullable();
            $blueprint->string('nip')->nullable();
            $blueprint->string('jenis_kelamin', 1)->nullable();
            $blueprint->string('tempat_lahir')->nullable();
            $blueprint->date('tanggal_lahir')->nullable();
            $blueprint->string('status_tugas')->nullable();
            $blueprint->string('tempat_tugas')->nullable();
            $blueprint->string('npsn')->nullable();
            $blueprint->string('kecamatan')->nullable();
            $blueprint->string('kabupaten_kota')->nullable();
            $blueprint->string('no_hp')->nullable();
            $blueprint->string('sk_cpns')->nullable();
            $blueprint->date('tanggal_cpns')->nullable();
            $blueprint->string('sk_pengangkatan')->nullable();
            $blueprint->date('tmt_pengangkatan')->nullable();
            $blueprint->string('jenis_ptk')->nullable();
            $blueprint->string('jabatan_ptk')->nullable();
            $blueprint->string('pendidikan')->nullable();
            $blueprint->string('bidang_studi_pendidikan')->nullable();
            $blueprint->string('bidang_studi_sertifikasi')->nullable();
            $blueprint->string('status_kepegawaian')->nullable();
            $blueprint->string('pangkat_golongan')->nullable();
            $blueprint->date('tmt_pangkat')->nullable();
            $blueprint->integer('masa_kerja_tahun')->default(0);
            $blueprint->integer('masa_kerja_bulan')->default(0);
            $blueprint->string('mata_pelajaran_diajarkan')->nullable();
            $blueprint->integer('jam_mengajar_perminggu')->default(0);
            $blueprint->string('jabatan_kepsek')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_guru_dinas');
    }
};
