<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPelanggaran extends Model
{
    use HasFactory, HasUuids, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'nama',
        'jenis',
        'poin',
        'status',
    ];

    public function pelanggaranSiswa()
    {
        return $this->hasMany(PelanggaranSiswa::class);
    }

    public function pelanggaranPegawai()
    {
        return $this->hasMany(PelanggaranPegawai::class);
    }
}
