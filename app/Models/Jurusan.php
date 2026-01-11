<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Jurusan extends Model
{
    use HasUuids;

    protected $fillable = [
        'nama',
        'deskripsi',
        'is_active',
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
}
