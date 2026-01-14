<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ERaport extends Model
{
    use HasFactory, HasUuids, \App\Traits\BelongsToSchool;

    protected $table = 'e_raports';

    protected $fillable = [
        'school_id',
        'siswa_id',
        'semester',
        'tahun_pelajaran',
        'file_name',
        'file_path',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
