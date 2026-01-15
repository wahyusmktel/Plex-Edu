<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Slider extends Model
{
    use HasFactory, HasUuids, \App\Traits\BelongsToSchool;

    protected $fillable = [
        'school_id',
        'judul',
        'deskripsi',
        'gambar',
        'waktu_mulai',
        'waktu_selesai',
        'is_permanen',
        'link',
    ];
}
