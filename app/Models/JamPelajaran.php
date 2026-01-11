<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class JamPelajaran extends Model
{
    use HasUuids;

    protected $fillable = [
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];
}
