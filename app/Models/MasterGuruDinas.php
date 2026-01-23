<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterGuruDinas extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_guru_dinas';

    protected $fillable = [
        'nama',
        'nik',
        'nuptk',
        'nip',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'status_tugas',
        'tempat_tugas',
        'npsn',
        'kecamatan',
        'kabupaten_kota',
        'no_hp',
        'sk_cpns',
        'tanggal_cpns',
        'sk_pengangkatan',
        'tmt_pengangkatan',
        'jenis_ptk',
        'jabatan_ptk',
        'pendidikan',
        'bidang_studi_pendidikan',
        'bidang_studi_sertifikasi',
        'status_kepegawaian',
        'pangkat_golongan',
        'tmt_pangkat',
        'masa_kerja_tahun',
        'masa_kerja_bulan',
        'mata_pelajaran_diajarkan',
        'jam_mengajar_perminggu',
        'jabatan_kepsek',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_cpns' => 'date',
        'tmt_pengangkatan' => 'date',
        'tmt_pangkat' => 'date',
        'masa_kerja_tahun' => 'integer',
        'masa_kerja_bulan' => 'integer',
        'jam_mengajar_perminggu' => 'integer',
    ];
}
