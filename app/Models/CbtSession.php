<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CbtSession extends Model
{
    use HasUuids;

    protected $fillable = [
        'cbt_id',
        'siswa_id',
        'start_time',
        'end_time',
        'skor',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function cbt()
    {
        return $this->belongsTo(Cbt::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function answers()
    {
        return $this->hasMany(CbtAnswer::class, 'session_id');
    }
}
