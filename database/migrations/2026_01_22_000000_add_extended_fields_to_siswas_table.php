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
        Schema::table('siswas', function (Blueprint $table) {
            // Personal Info
            $table->string('nipd')->nullable()->after('nama_lengkap');
            $table->string('nik')->nullable()->after('tanggal_lahir');
            $table->string('agama')->nullable()->after('nik');
            $table->string('rt')->nullable()->after('alamat');
            $table->string('rw')->nullable()->after('rt');
            $table->string('dusun')->nullable()->after('rw');
            $table->string('kelurahan')->nullable()->after('dusun');
            $table->string('kecamatan')->nullable()->after('kelurahan');
            $table->string('kode_pos')->nullable()->after('kecamatan');
            $table->string('jenis_tinggal')->nullable()->after('kode_pos');
            $table->string('alat_transportasi')->nullable()->after('jenis_tinggal');
            $table->string('telepon')->nullable()->after('alat_transportasi');
            $table->string('email')->nullable()->after('no_hp');
            $table->string('skhun')->nullable()->after('email');
            $table->string('penerima_kps')->nullable()->after('skhun');
            $table->string('no_kps')->nullable()->after('penerima_kps');

            // Data Ayah
            $table->string('ayah_tahun_lahir')->nullable()->after('nama_ayah');
            $table->string('ayah_pendidikan')->nullable()->after('ayah_tahun_lahir');
            $table->string('ayah_pekerjaan')->nullable()->after('ayah_pendidikan');
            $table->string('ayah_penghasilan')->nullable()->after('ayah_pekerjaan');
            $table->string('ayah_nik')->nullable()->after('ayah_penghasilan');

            // Data Ibu
            $table->string('ibu_tahun_lahir')->nullable()->after('nama_ibu');
            $table->string('ibu_pendidikan')->nullable()->after('ibu_tahun_lahir');
            $table->string('ibu_pekerjaan')->nullable()->after('ibu_pendidikan');
            $table->string('ibu_penghasilan')->nullable()->after('ibu_pekerjaan');
            $table->string('ibu_nik')->nullable()->after('ibu_penghasilan');

            // Data Wali
            $table->string('nama_wali')->nullable()->after('ibu_nik');
            $table->string('wali_tahun_lahir')->nullable()->after('nama_wali');
            $table->string('wali_pendidikan')->nullable()->after('wali_tahun_lahir');
            $table->string('wali_pekerjaan')->nullable()->after('wali_pendidikan');
            $table->string('wali_penghasilan')->nullable()->after('wali_pekerjaan');
            $table->string('wali_nik')->nullable()->after('wali_penghasilan');

            // Additional Info
            $table->string('no_peserta_ujian')->nullable()->after('wali_nik');
            $table->string('no_seri_ijazah')->nullable()->after('no_peserta_ujian');
            $table->string('penerima_kip')->nullable()->after('no_seri_ijazah');
            $table->string('no_kip')->nullable()->after('penerima_kip');
            $table->string('nama_di_kip')->nullable()->after('no_kip');
            $table->string('no_kks')->nullable()->after('nama_di_kip');
            $table->string('no_akta_lahir')->nullable()->after('no_kks');
            $table->string('bank')->nullable()->after('no_akta_lahir');
            $table->string('no_rekening_bank')->nullable()->after('bank');
            $table->string('rekening_atas_nama')->nullable()->after('no_rekening_bank');
            $table->string('layak_pip')->nullable()->after('rekening_atas_nama');
            $table->string('alasan_layak_pip')->nullable()->after('layak_pip');
            $table->string('kebutuhan_khusus')->nullable()->after('alasan_layak_pip');
            $table->integer('anak_ke')->nullable()->after('sekolah_asal');
            $table->decimal('lintang', 10, 8)->nullable()->after('anak_ke');
            $table->decimal('bujur', 11, 8)->nullable()->after('lintang');
            $table->string('no_kk')->nullable()->after('bujur');
            $table->integer('berat_badan')->nullable()->after('no_kk');
            $table->integer('tinggi_badan')->nullable()->after('berat_badan');
            $table->integer('lingkar_kepala')->nullable()->after('tinggi_badan');
            $table->integer('jml_saudara_kandung')->nullable()->after('lingkar_kepala');
            $table->decimal('jarak_rumah_km', 8, 2)->nullable()->after('jml_saudara_kandung');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn([
                'nipd', 'nik', 'agama', 'rt', 'rw', 'dusun', 'kelurahan', 'kecamatan', 'kode_pos',
                'jenis_tinggal', 'alat_transportasi', 'telepon', 'email', 'skhun', 'penerima_kps', 'no_kps',
                'ayah_tahun_lahir', 'ayah_pendidikan', 'ayah_pekerjaan', 'ayah_penghasilan', 'ayah_nik',
                'ibu_tahun_lahir', 'ibu_pendidikan', 'ibu_pekerjaan', 'ibu_penghasilan', 'ibu_nik',
                'nama_wali', 'wali_tahun_lahir', 'wali_pendidikan', 'wali_pekerjaan', 'wali_penghasilan', 'wali_nik',
                'no_peserta_ujian', 'no_seri_ijazah', 'penerima_kip', 'no_kip', 'nama_di_kip', 'no_kks',
                'no_akta_lahir', 'bank', 'no_rekening_bank', 'rekening_atas_nama', 'layak_pip', 'alasan_layak_pip',
                'kebutuhan_khusus', 'anak_ke', 'lintang', 'bujur', 'no_kk', 'berat_badan', 'tinggi_badan',
                'lingkar_kepala', 'jml_saudara_kandung', 'jarak_rumah_km'
            ]);
        });
    }
};
