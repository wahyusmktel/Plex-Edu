<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelanggaranPegawai extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'fungsionaris_id',
        'master_pelanggaran_id',
        'tanggal',
        'deskripsi',
        'tindak_lanjut',
    ];

    public function fungsionaris()
    {
        return $this->belongsTo(Fungsionaris::class);
    }

    public function masterPelanggaran()
    {
        return $this->belongsTo(MasterPelanggaran::class);
    }
}
