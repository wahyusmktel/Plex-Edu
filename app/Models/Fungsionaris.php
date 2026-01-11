<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fungsionaris extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'fungsionaris';

    protected $fillable = [
        'user_id',
        'nip',
        'nik',
        'nama',
        'posisi',
        'jabatan',
        'status',
        'no_hp',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'pendidikan_terakhir',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
