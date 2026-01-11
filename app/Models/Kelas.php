<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Kelas extends Model
{
    use HasUuids;

    protected $fillable = [
        'nama',
        'tingkat',
        'wali_kelas_id',
        'jurusan_id',
        'kapasitas',
        'keterangan',
    ];

    public function waliKelas()
    {
        return $this->belongsTo(Fungsionaris::class, 'wali_kelas_id');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}
