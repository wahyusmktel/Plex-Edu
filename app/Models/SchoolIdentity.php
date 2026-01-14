<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SchoolIdentity extends Model
{
    use HasUuids;

    protected $fillable = [
        'nama_sekolah',
        'npsn',
        'alamat',
        'desa_kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'provinsi',
        'status_sekolah',
    ];
}
