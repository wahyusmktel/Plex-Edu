<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelanggaranSiswa extends Model
{
    use HasFactory, HasUuids, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'siswa_id',
        'master_pelanggaran_id',
        'tanggal',
        'deskripsi',
        'tindak_lanjut',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function masterPelanggaran()
    {
        return $this->belongsTo(MasterPelanggaran::class);
    }
}
